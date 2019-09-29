<?php
/**
 * @author Andrea Maccis <andrea.maccis@gmail.com>
 */

namespace Amaccis\Tai64DateTime;

use DateTime;
use InvalidArgumentException;

class Tai64 {

    // bytes 40 00 00 00 00 00 00 00 hexadecimal represent the second that began 1970 TAI
    // source: https://cr.yp.to/libtai/tai64.html
    private const TAI_EPOCH = '4000000000000000';
    private const ALLOWED_SINCE = '1972-01-01';
    private const ALLOWED_BIGGER_THAN = "4611686019134860333";

    public const DATE_FORMAT_YYYYMMDD = 'Y-m-d';
    public const DATE_FORMAT_YYYYMMDDHHIISS = 'Y-m-d H:i:s';
    public const DATE_FORMAT_SECONDS = 'U';
    public const DATE_FORMAT_TAI64 = 'TAI64';

    private const STANDARD_UTC = 'UTC';
    private const STANDARD_TAI = 'TAI';

    // this probably should be moved elsewhere
    // source: http://maia.usno.navy.mil/ser7/leapsec.dat
    private const LEAP_SECONDS = [
        '1972-01-01' => '10.0',
        '1972-07-01' => '11.0',
        '1973-01-01' => '12.0',
        '1974-01-01' => '13.0',
        '1975-01-01' => '14.0',
        '1976-01-01' => '15.0',
        '1977-01-01' => '16.0',
        '1978-01-01' => '17.0',
        '1979-01-01' => '18.0',
        '1980-01-01' => '19.0',
        '1981-07-01' => '20.0',
        '1982-07-01' => '21.0',
        '1983-07-01' => '22.0',
        '1985-07-01' => '23.0',
        '1988-01-01' => '24.0',
        '1990-01-01' => '25.0',
        '1991-01-01' => '26.0',
        '1992-07-01' => '27.0',
        '1993-07-01' => '28.0',
        '1994-07-01' => '29.0',
        '1996-01-01' => '30.0',
        '1997-07-01' => '31.0',
        '1999-01-01' => '32.0',
        '2006-01-01' => '33.0',
        '2009-01-01' => '34.0',
        '2012-07-01' => '35.0',
        '2015-07-01' => '36.0',
        '2017-01-01' => '37.0',
        // the future leap seconds entries need to be added here
    ];

    /**
     * @param string $hexString
     * @return string
     */
    public static function externalTAI64FormatToStringDateTime(string $hexString) : string
    {
        $seconds = intval(hexdec(substr($hexString, 0, 16)));
        // works only for values bigger than 4611686019134860333 seconds
        if ($seconds < self::ALLOWED_BIGGER_THAN) {
            throw new InvalidArgumentException('Only values bigger than 4611686019134860333 seconds are allowed');
        }
        $lowerLimit = pow(2, 62);
        // upper limit not needed currently
        // $upperLimit = pow(2, 63);
        $externalTAI64Format = $seconds - $lowerLimit;
        $adjustedDate = self::adjust(DateTime::createFromFormat(self::DATE_FORMAT_SECONDS, $externalTAI64Format));
        return $adjustedDate->format(self::DATE_FORMAT_YYYYMMDDHHIISS);

    }

    /**
     * @param DateTime $dateTime
     * @return string
     */
    public static function dateTimeToExternalTAI64Format(DateTime $dateTime) : string
    {

        $date = $dateTime->format(self::DATE_FORMAT_YYYYMMDD);
        // works only for dates since 1972-01-01
        if ($date < self::ALLOWED_SINCE) {
            throw new InvalidArgumentException('Only dates since 1972-01-01 are allowed');
        }
        $adjustedDateTime = self::adjust($dateTime, self::STANDARD_TAI);
        $timestamp = $adjustedDateTime->format(self::DATE_FORMAT_SECONDS);
        $taiBeginning = hexdec(self::TAI_EPOCH);
        return dechex((int) $taiBeginning + (int) $timestamp);

    }

    /**
     * @param DateTime $dateTimeToAdjust
     * @param string $standard
     * @return DateTime
     */
    public static function adjust(DateTime $dateTimeToAdjust, string $standard = self::STANDARD_UTC) : DateTime
    {

        $leapSeconds = self::LEAP_SECONDS;
        $dateToAdjust = $dateTimeToAdjust->format(self::DATE_FORMAT_YYYYMMDD);
        if (!array_key_exists($dateToAdjust, $leapSeconds)) {
            $leapSeconds[$dateToAdjust] = '0';
            ksort($leapSeconds);
            $keys = array_keys($leapSeconds);
            $key = (array_search($dateTimeToAdjust->format(self::DATE_FORMAT_YYYYMMDD), $keys, true) - 1);
            $leapSecondsValue = $leapSeconds[$keys[$key]];
        } else {
            $leapSecondsValue = $leapSeconds[$dateToAdjust];
        }
        $seconds = number_format($leapSecondsValue, 0, '.', '');
        // only UTC and TAI are allowed, UTC = default
        $modify = ($standard == self::STANDARD_TAI) ? sprintf('+%s seconds', $seconds) : sprintf('-%s seconds', $seconds);
        return $dateTimeToAdjust->modify($modify);

    }

}