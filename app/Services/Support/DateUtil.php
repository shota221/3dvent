<?php

namespace App\Services\Support;

use Carbon\Carbon;

use App\Exceptions\DateUtilException;

class DateUtil
{

    const
        ZERO_DATE               = '0000-00-00',
        ZERO_DATETIME           = '0000-00-00 00:00:00',
        DAY_START_TIME          = '00:00:00',
        DAY_END_TIME            = '23:59:59',
        DATE_FORMAT             = 'Y-m-d',
        MONTH_FORMAT            = 'Y-m',
        DATE_FORMAT_JP          = 'Y年n月j日',
        MONTH_FORMAT_JP         = 'Y年n月',
        DATE_FORMAT_CHAR        = 'Ymd',
        DATE_FORMAR_CHAR_SHORT  = 'ymd',
        TIME_FORMAT             = 'H:i:s',
        DATETIME_FORMAT         = 'Y-m-d H:i:s',
        DATETIME_MS_FORMAT      = 'Y-m-d H:i:s.u',
        DATETIME_FORMAT_JP      = 'Y年n月j日 G時i分',
        DATETIME_FORMAT_CHAR    = 'YmdHis';

    /**
     * うるう年などcarbonは勝手に翌日にしてしまうのでチェックを実行
     * 
     * @param  [type] $format [description]
     * @param  [type] $str    [description]
     * @return [type]         [description]
     */
    private static function createFromFormat($format, $str)
    {
        $date = Carbon::createFromFormat($format, $str);

        if ($date->format($format) !== $str) {
            throw new DateUtilException('指定日付が不正です。$str=' . $str);
        }

        return $date;
    }

    public static function isZeroDate(string $date)
    {
        return self::ZERO_DATE === $date;
    }

    public static function isZeroDatetime(string $date)
    {
        return self::ZERO_DATETIME === $date;
    }

    public static function now()
    {
        return Carbon::now();
    }

    public static function yesterdayStart()
    {
        return Carbon::yesterday(); // 00:00:00
    }

    public static function tomorrowStart()
    {
        return Carbon::tomorrow(); // 00:00:00
    }

    public static function todayStart()
    {
        return Carbon::today(); // 00:00:00
    }

    public static function todayEnd()
    {
        return self::dayEnd(Carbon::today());
    }

    public static function dayStart(Carbon $date)
    {
        return Carbon::parse(self::toDateStr($date) . ' ' . self::DAY_START_TIME);
    }

    public static function dayEnd(Carbon $date)
    {
        return Carbon::parse(self::toDateStr($date) . ' ' . self::DAY_END_TIME);
    }

    public static function monthAgo(Carbon $date, int $ago = 1)
    {
        return $date->copy()->subMonth($ago);
    }

    public static function dayAgo(Carbon $date, int $ago = 1)
    {
        return $date->copy()->subDay($ago);
    }

    public static function hourAgo(Carbon $date, int $ago = 1)
    {
        return $date->copy()->subHour($ago);
    }

    public static function hourLater(Carbon $date, int $later = 1)
    {
        return $date->copy()->addHour($later);
    }

    public static function toMonthStr(Carbon $date)
    {
        return $date->format(self::MONTH_FORMAT);
    }

    public static function toMonthStrJp(Carbon $date)
    {
        return $date->format(self::MONTH_FORMAT_JP);
    }

    public static function toDateStr(Carbon $date)
    {
        return $date->format(self::DATE_FORMAT);
    }

    public static function toDateStrJp(Carbon $date)
    {
        return $date->format(self::DATE_FORMAT_JP);
    }

    public static function toDateChar(Carbon $date)
    {
        return $date->format(self::DATE_FORMAT_CHAR);
    }

    public static function toDatetimeStr(Carbon $date)
    {
        return $date->format(self::DATETIME_FORMAT);
    }

    public static function toDatetimeMsStr(Carbon $date)
    {
        return $date->format(self::DATETIME_MS_FORMAT);
    }

    public static function toDatetimeStrJp(Carbon $date)
    {
        return $date->format(self::DATETIME_FORMAT_JP);
    }

    public static function toDatetimeChar(Carbon $date)
    {
        return $date->format(self::DATETIME_FORMAT_CHAR);
    }

    public static function toTimeStr(Carbon $date)
    {
        return $date->format(self::TIME_FORMAT);
    }

    public static function isBetweenDateTimeToAnother(Carbon $date, Carbon $from, Carbon $to)
    {
        return $date->between($from, $to);
    }


    /**
     * @param string $str
     * @param [type] $format
     * @return Carbon
     */
    public static function parseToDate(string $str, $format = null)
    {
        try {
            $format = $format ?? self::DATE_FORMAT;

            if ($format !== self::DATE_FORMAT) {
                $date =  self::createFromFormat($format, $str);

                // createFromFormatは現在時刻を勝手に付与するためまた文字に戻し、初期化の引数にあたえる
                $str = $date->format(self::DATE_FORMAT);
            }

            return new Carbon($str);
        } catch (\InvalidArgumentException $e) {
            throw new DateUtilException(('パースに失敗 $str=' . $str . ' $format=' . $format), $e);
        }
    }

    /**
     * @param string $str
     * @param [type] $format
     * @return Carbon
     */
    public static function parseToDatetime(string $str, $format = null)
    {
        try {
            if (is_null($format)) {
                if (strpos($str, '.') !== false) {
                    // microtime stringの場合
                    $format = self::DATETIME_MS_FORMAT;
                }
            }

            $format = $format ?? self::DATETIME_FORMAT;

            return self::createFromFormat($format, $str);
        } catch (\InvalidArgumentException $e) {
            throw new DateUtilException(('パースに失敗 $str=' . $str . ' $format=' . $format), $e);
        }
    }
}
