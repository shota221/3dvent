<?php

namespace App\Services\Support;

use App\Exceptions;

use Illuminate\Support\Facades\Storage;

use App\Models;

use Illuminate\Support\Facades\File as BaseFile;

use ZipArchive;

/**
 * ファイル操作UTILクラス
 * 
 * @see Illuminate\Filesystem\FilesystemAdapter
 */
class FileUtil
{

    /**
     * [storageUrl description]
     * @param  string $path [description]
     * @param  string $disk [description]
     * @return [string]       [description]
     */
    private static function storageUrl(string $path, string $disk)
    {
        return Storage::disk($disk)->url($path);
    }

    /**
     * [storageExists description]
     * @param  string $path [description]
     * @param  string $disk [description]
     * @return [bool]       [description]
     */
    private static function storageExists(string $path, string $disk)
    {
        return Storage::disk($disk)->exists($path);
    }

    /**
     * [putContent description]
     * @param  string $path   [description]
     * @param  [type] $stream [description]
     * @param  string $disk   [description]
     * @return [string]         [description]
     */
    private static function putContent(string $path, $stream, string $disk, string $option)
    {
        try {
            if ($stream instanceof \Symfony\Component\HttpFoundation\File\File) {
                $result = (bool) Storage::disk($disk)->putFileAs(self::dirname($path), $stream, self::basename($path), $option);
            } else 
            if (is_string($stream)) {
                // stream is filepath
                $result = (bool) Storage::disk($disk)->putFile($path, $stream, $option);
            } else {
                $result = (bool) Storage::disk($disk)->put($path, $stream, $option);
            }

            // if (is_resource($stream)) {
            //     fclose($stream);
            // }

            if (!$result) {
                throw new Exceptions\UtilFileException('ファイル保存に失敗しました。');
            }

            return $result;
        } catch (\Exception $e) {
            throw new Exceptions\UtilFileException('ファイル保存に失敗しました。', $e);
        }
    }

    /**
     * [storageDeleteFiles description]
     * @param  [array|string] $path [description]
     * @param  string $disk [description]
     * @return [type]       [description]
     */
    private static function storageDeleteFiles($path, string $disk)
    {
        try {
            Storage::disk($disk)->delete($path);
        } catch (\Exception $e) {
            throw new Exceptions\UtilFileException('ファイル削除に失敗しました。path=' . var_export($path, true), $e);
        }
    }

    /**
     * [storageDeleteDirs description]
     * @param  [array|string] $path [description]
     * @param  string $disk [description]
     * @return [type]       [description]
     */
    private static function storageDeleteDirs($path, string $disk)
    {
        $path = is_array($path) ? $path : [$path];

        $storage = Storage::disk($disk);

        try {
            array_walk(
                $path,
                function ($pathStr) use ($storage) {
                    $storage->deleteDirectory($pathStr);
                }
            );
        } catch (\Exception $e) {
            throw new Exceptions\UtilFileException('ファイルディレクトリ削除に失敗しました。dit=' . var_export($path, true), $e);
        }
    }

    // PUBLIC //

    public static function name(string $path)
    {
        return BaseFile::name($path);
    }

    public static function basename(string $path)
    {
        // TODO マルチバイト対応
        setlocale(LC_ALL, 'ja_JP.UTF-8');

        return BaseFile::basename($path);
    }

    public static function dirname(string $path)
    {
        return BaseFile::dirname($path);
    }

    public static function extension(string $path)
    {
        return BaseFile::extension($path);
    }

    public static function size(string $path)
    {
        return BaseFile::size($path);
    }

    public static function getContent(string $url)
    {
        if (!self::exists($url)) {
            throw new Exceptions\UtilFileException('ファイルが存在しません。 path=' . $url);
        }

        return file_get_contents($url);
    }

    public static function getUploadedFileOriginalName(\Illuminate\Http\UploadedFile $uploadedFile)
    {
        return $uploadedFile->getClientOriginalName();
    }

    public static function getUploadedFileOriginalExtension(\Illuminate\Http\UploadedFile $uploadedFile)
    {
        return $uploadedFile->getClientOriginalExtension();
    }

    public static function getUploadedFileOriginalSize(\Illuminate\Http\UploadedFile $uploadedFile)
    {
        return $uploadedFile->getClientSize();
    }

    public static function getUploadedFilePath(\Illuminate\Http\UploadedFile $uploadedFile)
    {
        return $uploadedFile->getRealPath();
    }

    public static function s3PrivateDir()
    {
        // private/admin/tmp/{filename}
        return 'private';
    }

    public static function s3PrivateTempDir()
    {
        // private/admin/tmp/{filename}
        return 'private' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;
    }

    public static function soundSamplingPath($filename, $os)
    {
        return 'private/sound_sampling/' . $os . '/' . $filename;
    }

    /**
     * バッチTEMPファイルURLを取得
     * 
     * @param  string $filename [description]
     * @return [type]           [description]
     */
    public static function batchTempFileUrl(string $filename)
    {
        return self::batchTempDir() . DIRECTORY_SEPARATOR . $filename;
    }

    public static function batchTempFilePath(string $filename)
    {
        return self::batchTempDirPath() . DIRECTORY_SEPARATOR . $filename;
    }

    public static function batchTempDirPath()
    {
        return self::basename(self::batchTempDir());
    }

    public static function batchTempDir()
    {
        $dir = self::localUrl('batch_tmp');

        if (!self::exists($dir) && !BaseFile::makeDirectory($dir, 0755, true, true)) {
            throw new Exceptions\UtilFileException('ディレクトリ作成に失敗しました。 dir=' . $dir);
        }

        return $dir;
    }

    /**********************************************
     *  取得
     * 
     * @param  [string] $path [description]
     * @return string url
     **********************************************/

    /**
     * S3clientを取得
     * 
     * @return [type] [description]
     */
    public static function s3Client()
    {
        return Storage::disk('s3')->getAdapter()->getClient();
    }

    /**
     * S3バケット名取得
     * 
     * @return [type] [description]
     */
    public static function s3Bucket()
    {
        return Storage::disk('s3')->getAdapter()->getBucket();
    }

    /**********************************************
     * URL取得
     * 
     * @param  [string] $path [description]
     * @return string url
     **********************************************/

    public static function localUrl(string $path)
    {
        return self::storageUrl($path, 'local');
    }

    public static function publicUrl(string $path)
    {
        return self::storageUrl($path, 'public');
    }

    public static function s3Url(string $path)
    {
        return self::storageUrl($path, 's3');
    }

    public static function tmpUrl(string $path)
    {
        ///storage/tmp
        $tmp_dir = self::storageUrl('tmp', 'local');

        if (!self::exists($tmp_dir) && !BaseFile::makeDirectory($tmp_dir, 0755, true, true)) {
            throw new Exceptions\UtilFileException('ディレクトリ作成に失敗しました。 dir=' . $tmp_dir);
        }

        $file_path = $tmp_dir . DIRECTORY_SEPARATOR . $path;

        return $file_path;
    }

    /**
     * S3ファイルのメタデータを取得
     * 
     * @param  string $path [description]
     * @return [type]       [description]
     * 
     *   ["path"]=>string(57) "private/mozo/tmp/20190704090818_point_card_migrations.csv"
     *   ["dirname"]=>string(16) "private/mozo/tmp"
     *   ["basename"]=>string(40) "20190704090818_point_card_migrations.csv"
     *   ["extension"]=>string(3) "csv"
     *   ["filename"]=>string(36) "20190704090818_point_card_migrations"
     *   ["timestamp"]=>int(1562199514)
     *   ["size"]=>int(114379)
     *   ["mimetype"]=>string(29) "text/x-comma-separated-values"
     *   ["metadata"]=>array(0) {}
     *   ["storageclass"]=>string(0) ""
     *   ["etag"]=>string(34) ""8d457fac9271dfc6c10b607f2cee1381""
     *   ["versionid"]=>string(0) ""
     *   ["type"]=>string(4) "file"
     */
    public static function s3Metadata(string $path)
    {
        // call headObject
        return Storage::disk('s3')->getMetadata($path);
    }



    /**********************************************
     * 存在チェック
     * 
     * @param  [string] $path [description]
     * @return bool 
     **********************************************/

    public static function exists(string $path)
    {
        return BaseFile::exists($path);
    }

    public static function defaultExists(string $path)
    {
        return self::storageExists($path, Storage::getDefaultDriver());
    }

    public static function localExists(string $path)
    {
        return self::storageExists($path, 'local');
    }

    public static function publicExists(string $path)
    {
        return self::storageExists($path, 'public');
    }

    public static function s3Exists(string $path)
    {
        return self::storageExists($path, 's3');
    }

    /**********************************************
     * 保存
     * 
     * @param  [string|array] $path [description]
     * @param  [] $stream [description]
     * @return string $path
     **********************************************/

    public static function putLocal(string $path, $stream)
    {
        return self::putContent($path, $stream, 'local', 'private');
    }

    public static function putBatchTemp(string $filename, $stream)
    {
        return self::putLocal(self::batchTempFilePath($filename), $stream);
    }

    public static function putPublic(string $path, $stream)
    {
        return self::putContent($path, $stream, 'public', 'public');
    }

    public static function putS3Private(string $path, $stream)
    {
        return self::putContent($path, $stream, 's3', 'private');
    }

    public static function putS3Public(string $path, $stream)
    {
        return self::putContent($path, $stream, 's3', 'public');
    }

    public static function putSoundSamplingFile(string $filename, $contents, $os)
    {
        try {
            $result = Storage::disk('local')->put(self::soundSamplingPath($filename, $os), $contents);

            if (!$result) {
                throw new Exceptions\UtilFileException('ファイル保存に失敗しました。');
            }

            return $result;
        } catch (\Exception $e) {
            throw new Exceptions\UtilFileException('ファイル保存に失敗しました。', $e);
        }
    }

    /**********************************************
     * 削除
     * 
     * @param  [string|array] $path [description]
     * void
     **********************************************/

    public static function delete($path)
    {
        if (!BaseFile::delete($path)) {
            throw new Exceptions\UtilFileException('ファイル削除に失敗しました。path=' . var_export($path, true));
        }
    }

    public static function deleteLocal($path)
    {
        self::storageDeleteFiles($path, 'local');
    }

    public static function deletePublic($path)
    {
        self::storageDeleteFiles($path, 'public');
    }

    public static function deleteS3($path)
    {
        self::storageDeleteFiles($path, 's3');
    }

    public static function deleteDirLocal($path)
    {
        self::storageDeleteDirs($path, 'local');
    }

    public static function deleteDirPublic($path)
    {
        self::storageDeleteDirs($path, 'public');
    }

    public static function deleteDirS3($path)
    {
        self::storageDeleteDirs($path, 's3');
    }

    /**
     * zip生成
     *
     * @param string $zip_path //zip保存先
     * @param string ...$files //zipに格納するファイル群。それぞれ'|'が含まれている場合、それより前がパス、それより後がzipに格納する際のファイル名を表す。
     * @return void
     */
    public static function createZip(string $zip_path, string ...$files)
    {
        $zip = new ZipArchive();

        $zip->open($zip_path, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);

        foreach ($files as $file) {
            if (strpos($file, '|')) {
                $file = explode('|', $file);
                $file_path = $file[0];
                $file_name = $file[1];
            } else {
                $file_path = $file;
                $file_name = self::basename($file_path);
            }
            if (!$zip->addFile($file_path, $file_name)) continue;
        }

        $zip->close();

        return $zip_path;
    }
}
