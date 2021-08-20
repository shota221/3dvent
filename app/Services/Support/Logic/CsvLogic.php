<?php

namespace App\Services\Support\Logic;

use App\Exceptions;

use App\Models;
use App\Repositories as Repos;
use App\Services\Support;
use App\Services\Support\Converter;
use stdClass;
use Validator;

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
        int $chunkSize = 500,
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
            if (!$stream = fopen($path_to_save, 'w')) throw new Exceptions\CsvLogicException('php://tempファイル生成オープンに失敗');

            if (!fputcsv($stream, $this->utf8ToSjis($header))) throw new Exceptions\CsvLogicException('ファイル書き込み(ヘッダ行)に失敗');

            $searchQuery->chunk($chunkSize, function ($entities) use ($stream, $convertFunction) {
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
     * CSV処理
     * 
     * ClosureのExceptionはキャッチしていない
     *
     * @param  string      $fileUrl           [description]
     * @param  array|null  $header            [description]
     * @param  array       $validationRule    [0 => 'required|string', 1 => 'required|numeric'] 列値のバリデーション
     * @param  \Closure    $procedureFunction [description]
     * @param  int|integer $chunkSize         [description]
     * @return [type]                         [description]
     */
    public function processCsv(
        string $fileUrl,
        array $header = null,
        array $validationRule,
        \Closure $procedureFunction,
        int $chunkSize = 5000
    ) {
        \Log::info('CSV読み込み処理を実行します。fileUrl=' . $fileUrl);

        \Log::debug('START MEMORY=' . memory_get_usage(FALSE));

        $file = null;

        // 処理完了行数
        $finishedRowCount = 0;

        try {
            try {
                // ファイルの読み込み throwable RuntimeException
                $file = new \SplFileObject($fileUrl);

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

            if (!is_null($header)) {
                $firstRow = $file->current();

                if (!empty(array_diff($header, $firstRow))) {
                    throw new Exceptions\LogicException('ヘッダ行がマッチしないため読み込みをキャンセルします。header=' . var_export($firstRow, true));
                }

                // 処理済み件数に追加
                $finishedRowCount = $finishedRowCount + 1;

                $file->next();
            }

            while ($file->valid()) {
                $row = $file->current();

                // 行バリーデーション
                $validation = Validator::make($row, $validationRule);

                if ($validation->fails()) {
                    $errors = [];

                    foreach ($validation->failed() as $attribute => $result) {
                        $errors['列-' . $attribute] = $result;
                    }

                    throw new Exceptions\LogicException(
                        '行データが不正です。読み込みをキャンセルします。'
                            . ' errors=' . var_export($errors, true)
                            . ' translated=' . var_export($validation->errors()->getMessages(), true)
                    );
                }

                $rows[] = $row;

                if (count($rows) === $chunkSize) {
                    // 一旦処理 処理完了行数更新
                    $procedureFunction($rows);

                    // 処理済み件数に追加
                    $finishedRowCount = $finishedRowCount + $chunkSize;

                    //
                    $rows = [];
                }

                $file->next();
            }

            // 残りを処理
            if (!empty($rows)) {
                // throws Exceptions\LogicException
                $procedureFunction($rows);

                // 処理済み件数に追加
                $finishedRowCount = $finishedRowCount + count($rows);
            }

            \Log::info('処理完了行数(ヘッダ行含む)=' . $finishedRowCount);

            return $finishedRowCount;
        } catch (Exceptions\LogicException $e) {
            $message = 'processCsv CSV処理に失敗しました。 previous message=' . $e->getMessage();

            throw new Exceptions\CsvLogicException($message, $fileUrl, $finishedRowCount, $e);
        } finally {
            $file = null;

            \Log::debug('END MEMORY=' . memory_get_usage(FALSE));
        }
    }
}
