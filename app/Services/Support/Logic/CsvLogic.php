<?php

namespace App\Services\Support\Logic;

use App\Exceptions;

use App\Models;
use App\Repositories as Repos;
use App\Services\Support;
use App\Services\Support\Converter;
use stdClass;
use Validator;

use function Psy\debug;

trait CsvLogic
{
    private function replacelFToCrlf(array $row)
    {
        return str_replace(PHP_EOL, "\r\n", array_values($row));
    }

    private function utf8ToSjis(array $row)
    {
        return mb_convert_encoding(array_values($row), 'SJIS-win', 'UTF-8');
    }

    private function sjisToUtf8(array $row)
    {
        return mb_convert_encoding(array_values($row), 'UTF-8', 'SJIS');
    }

    //CSVフォーマット作成
    public function createCsvFormat(array $header, array $example = [])
    {
        $stream = null;

        $stream = fopen('php://output', 'w');
        fputcsv($stream, $this->utf8ToSjis($header));
        if (!empty($example)) fputcsv($stream, $this->utf8ToSjis($this->replacelFToCrlf($example)));
        fclose($stream);
    }

    //arrayからCSV作成
    public function createCsvFromArray(
        string $filename,
        array $header = [],
        array $data_array = []
    ) {
        $stream = null;

        $finishedRowCount = 0;

        try {

            // 2MB以上はファイル生成以下はメモリに展開
            if (!$stream = fopen('php://output', 'w')) throw new Exceptions\CsvLogicException('php://tempファイル生成オープンに失敗');

            if (!fputcsv($stream, $this->utf8ToSjis($header))) throw new Exceptions\CsvLogicException('ファイル書き込み(ヘッダ行)に失敗');

            foreach ($data_array as $row) {
                if (!fputcsv($stream, $this->utf8ToSjis($this->replacelFToCrlf($row)))) {
                    throw new Exceptions\LogicException('ファイル書き込みに失敗');
                }
            }
        } catch (Exceptions\LogicException $e) {
            throw new Exceptions\CsvLogicException(
                'createSearchDataCsv CSV作成に失敗しました。',
                $filename,
                $finishedRowCount,
                $e
            );
        } finally {
            if ($stream) {
                if (!fclose($stream)) {
                    throw new Exceptions\CsvLogicException('FILE STREAM CLOSEに失敗しました。', $filename);
                }
            }

            \Log::debug('END MEMORY=' . memory_get_usage(FALSE));
        }
    }

    /**
     * 検索結果CSV作成
     * 
     * ClosureのExceptionはキャッチしていない
     * 
     * @param  string                                $filename        [description]
     * @param  array                                 $header          [description]
     * @param  \Closure                              $convertFunction [description]
     * @param  \Illuminate\Database\Eloquent\Builder $searchQuery     [description]
     * @param  int|integer                           $chunkSize       [description]
     * @return [type]                                                 [description]
     */
    public function createSearchDataCsv(
        string $filename,
        array $header,
        \Closure $convertFunction,
        \Illuminate\Database\Eloquent\Builder $searchQuery,
        int $chunkSize = 5000,
        string $path_to_save = null
    ) {
        \Log::info('CSV作成を実行します。filename=' . $filename);

        \Log::debug('START MEMORY=' . memory_get_usage(FALSE));

        $stream = null;

        // 処理完了行数
        $finishedRowCount = 0;

        $path_to_save = $path_to_save ?? 'php://output';

        try {
            // if (! touch($fileUrl)) throw new Exceptions\LogicException('ファイル作成に失敗');

            // if (! $stream = fopen($fileUrl, 'w')) throw new Exceptions\LogicException('ファイルオープンに失敗');

            // 2MB以上はファイル生成以下はメモリに展開
            if (!$stream = fopen($path_to_save, 'w')) throw new Exceptions\CsvLogicException($path_to_save . 'ファイル生成オープンに失敗');

            if (!fputcsv($stream, $this->utf8ToSjis($header))) throw new Exceptions\CsvLogicException('ファイル書き込み(ヘッダ行)に失敗');

            $searchQuery->chunk($chunkSize, function ($entities) use ($stream, $convertFunction, $chunkSize, &$finishedRowCount) {
                // throws Exceptions\LogicException;
                $rows = $convertFunction($entities);

                foreach ($rows as $row) {
                    if (!fputcsv($stream, $this->utf8ToSjis($this->replacelFToCrlf($row)))) {
                        throw new Exceptions\LogicException('ファイル書き込みに失敗');
                    }
                }
            });
        } catch (Exceptions\LogicException $e) {
            throw new Exceptions\CsvLogicException(
                'createSearchDataCsv CSV作成に失敗しました。',
                $filename,
                $finishedRowCount,
                $e
            );
        } finally {
            if ($stream) {
                if (!fclose($stream)) {
                    throw new Exceptions\CsvLogicException('FILE STREAM CLOSEに失敗しました。', $filename);
                }
            }

            \Log::debug('END MEMORY=' . memory_get_usage(FALSE));
        }
    }

    /** 
     *　TODO　チャンク処理時、処理が中断しないように修正
     *  CSV処理
     *
     * @param  string      $fileUrl           [description]
     * @param  array|null  $map_attribute_to_header            [key1 => header_name1, key2 => header_name2,...]
     * @param  array       $map_attribute_to_validation_rule   [key1 => 'required|string', key2 => 'required|numeric',...] 列値のバリデーション
     * @param  array       $dupulicate_confirmation_targets    [key1,key2,...] 重複確認対象用($map_attribute_to_headerのkeyを指定)
     * @param  \Closure    $procedureFunction [description]
     * @param  int|integer $chunkSize         [description]
     * @return void                         
     */
    public function processCsv(
        string $file_url,
        array  $map_attribute_to_header,
        array  $map_attribute_to_validation_rule,
        array  $dupulicate_confirmation_targets = null,
        \Closure $procedure_function,
        int $chunk_size = 5000
    ) {
        \Log::info('CSV読み込み処理を実行します。file_url=' . $file_url);

        \Log::debug('START MEMORY=' . memory_get_usage(FALSE));

        $file = null;

        //　重複確認格納配列
        if (!is_null($dupulicate_confirmation_targets)) $dupulicate_confirmation_list = [];

        // 処理完了件数
        $finished_row_count = 0;

        try {
            try {
                // ファイルの読み込み
                $file = new \SplFileObject($file_url);

                $file->setFlags(
                    \SplFileObject::READ_CSV |       // CSV 列として行を読み込む
                        \SplFileObject::READ_AHEAD |     // 先読み/巻き戻しで読み出す。
                        \SplFileObject::SKIP_EMPTY |     // 空行は読み飛ばす
                        \SplFileObject::DROP_NEW_LINE    // 行末の改行を読み飛ばす
                );
            } catch (\Exception $e) {
                throw new Exceptions\LogicException('ファイル読み込みに失敗');
            }

            $file->next();

            $first_row = $this->sjisToUtf8($file->current());
            $header    = array_values($map_attribute_to_header);

            if (!empty(array_diff($header, $first_row))) {
                throw new Exceptions\InvalidCsvException('validation.csv_header_error');
            }

            // 処理済み件数に追加
            $finished_row_count = $finished_row_count + 1;

            $file->next();

            while ($file->valid()) {
                $row = $this->sjisToUtf8($file->current());

                $map_attribute_to_row = [];

                foreach (array_keys($map_attribute_to_header) as $key => $attr) {
                    $map_attribute_to_row[$attr] = $row[$key];
                }

                // 行バリデーション
                $validation = Validator::make($map_attribute_to_row, $map_attribute_to_validation_rule);

                if ($validation->fails()) {
                    $row_nums = $file->key();
                    throw new Exceptions\InvalidCsvException('validation.csv_row_error', compact('row_nums'), $finished_row_count);
                }


                // 重複確認
                if (!is_null($dupulicate_confirmation_targets)) {
                    foreach ($dupulicate_confirmation_targets as $dupulicate_confirmation_target) {

                        if (!array_key_exists($dupulicate_confirmation_target, $dupulicate_confirmation_list)) {
                            $dupulicate_confirmation_list[$dupulicate_confirmation_target] = [];
                        }

                        if (in_array($map_attribute_to_row[$dupulicate_confirmation_target], $dupulicate_confirmation_list[$dupulicate_confirmation_target])) {
                            $error_row = $file->key();
                            $attribute = $map_attribute_to_header[$dupulicate_confirmation_target];

                            throw new Exceptions\InvalidCsvException('validation.csv_duplicated_row', compact('error_row', 'attribute'), $finished_row_count);
                        }

                        if (!empty($map_attribute_to_row[$dupulicate_confirmation_target])) {
                            $dupulicate_confirmation_list[$dupulicate_confirmation_target][] = $map_attribute_to_row[$dupulicate_confirmation_target];
                        }
                    }
                }

                $rows[] = $map_attribute_to_row;

                if (count($rows) === $chunk_size) {
                    // 一旦処理　処理完了行数更新
                    $procedure_function($rows);
                    // 処理済み件数に追加
                    $finished_row_count = $finished_row_count + $chunk_size;

                    $rows = [];
                }

                $file->next();
            }

            // 残りを処理
            if (!empty($rows)) {
                $procedure_function($rows);
                // 処理済み件数に追加
                $finished_row_count = $finished_row_count + count($rows);
            }

            \Log::info('処理完了行数(ヘッダ行含む)=' . $finished_row_count);

            return;
        } catch (Exceptions\LogicException $e) {
            $message = 'processCsv CSV処理に失敗しました。 previous message=' . $e->getMessage();

            throw new Exceptions\CsvLogicException($message, $file_url, $finished_row_count, $e);
        } catch (Exceptions\InvalidException $e) {
            $message = $e->getMessage();

            throw new Exceptions\InvalidCsvException($message, [], $finished_row_count);
        } finally {
            $file = null;

            \Log::debug('END MEMORY=' . memory_get_usage(FALSE));
        }
    }

    /** 
     * CSV処理(すべての行バリデーション済)
     * 
     * ClosureのExceptionはキャッチしていない
     *
     * @param  string      $file_url           [description]
     * @param  array       $header             [description]
     * @param  \Closure    $procedure_function [description]
     * @param  int|integer $chunk_size         [description]
     * @return [type]                         [description]
     */
    public function processValidatedCsv(
        string $file_url,
        array $header,
        \Closure $procedure_function,
        int $chunk_size = 5000
    ) {
        \Log::info('CSV読み込み処理を実行します。file_url=' . $file_url);

        \Log::debug('START MEMORY=' . memory_get_usage(FALSE));

        $file = null;

        // 処理完了行数
        $finishedRowCount = 0;

        try {
            try {
                // ファイルの読み込み throwable RuntimeException
                $file = new \SplFileObject($file_url);

                $file->setFlags(
                    \SplFileObject::READ_CSV |           // CSV 列として行を読み込む
                        \SplFileObject::READ_AHEAD |       // 先読み/巻き戻しで読み出す。
                        \SplFileObject::SKIP_EMPTY |         // 空行は読み飛ばす
                        \SplFileObject::DROP_NEW_LINE    // 行末の改行を読み飛ばす
                );
            } catch (\Exception $e) {
                throw new Exceptions\LogicException('ファイル読み込みに失敗');
            }

            $file->next();

            // ヘッダ行処理済み
            $finishedRowCount ++;

            $file->next();

            while ($file->valid()) {
                $row = $this->sjisToUtf8($file->current());

                $map_attribute_to_row = [];

                foreach ($header as $key => $attr) {
                    $map_attribute_to_row[$attr] = $row[$key];
                }

                $rows[] = $map_attribute_to_row;

                if (count($rows) === $chunk_size) {
                    // 一旦処理 処理完了行数更新
                    $procedure_function($rows);

                    // 処理済み件数に追加
                    $finishedRowCount = $finishedRowCount + $chunk_size;

                    $rows = [];
                }

                $file->next();
            }

            // 残りを処理
            if (!empty($rows)) {
                // throws Exceptions\LogicException
                $procedure_function($rows);

                // 処理済み件数に追加
                $finishedRowCount = $finishedRowCount + count($rows);
            }

            \Log::info('処理完了行数(ヘッダ行含む)=' . $finishedRowCount);

            return;
        } catch (Exceptions\LogicException $e) {
            $message = 'processCsv CSV処理に失敗しました。 previous message=' . $e->getMessage();

            throw new Exceptions\CsvLogicException($message, $file_url, $finishedRowCount, $e);
        } finally {
            $file = null;

            \Log::debug('END MEMORY=' . memory_get_usage(FALSE));
        }
    }
}
