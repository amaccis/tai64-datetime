<?php

namespace Amaccis\Tai64;

use Amaccis\Tai64\Enum\TimeStandard;

class Tai64Tools implements Tai64ToolsInterface {

    public readonly int $tai64LabelMinimumValue;

    public readonly int $tai64LabelEpoch;

    public readonly int $tai64LabelMaximumValue;

    public readonly array $leapSeconds;

    public function __construct()
    {

        $this->tai64LabelMinimumValue = 0;
        $this->tai64LabelEpoch = 2**62;
        $this->tai64LabelMaximumValue = PHP_INT_MAX;    // is equal to (2^63)−1 for 64-bit systems
        // source: https://maia.usno.navy.mil/ser7/tai-utc.dat
        $this->leapSeconds = [
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

    }

    /**
     * @param string $tai64Label
     * @return \DateTime
     */
    public function tai64LabelToTaiDateTime(string $tai64Label): \DateTime
    {

        if (!ctype_xdigit($tai64Label)) {
            throw new \InvalidArgumentException('A TAI64 label must be an hexadecimal string');
        }
        $second = intval(hexdec($tai64Label));
        if ($second < $this->tai64LabelEpoch) {
            $taiSecond = $this->tai64LabelEpoch - $second;
            return \DateTime::createFromFormat('U', -($taiSecond));
        } else {
            $taiSecond = $second - $this->tai64LabelEpoch;
            return \DateTime::createFromFormat('U', $taiSecond);
        }

    }

    /**
     * @param \DateTime $taiDateTime
     * @return string
     */
    public function taiDateTimeToTai64Label(\DateTime $taiDateTime): string
    {

        $taiSecond = (int) $taiDateTime->format('U');
        return dechex($this->tai64LabelEpoch + $taiSecond);

    }

    /**
     * @param \DateTime $utcDateTime
     * @return \DateTime
     */
    public function utcDateTimeToTaiDateTime(\DateTime $utcDateTime): \DateTime
    {

        return $this->adjustDateTimeWithLeapSeconds($utcDateTime, TimeStandard::TAI);

    }

    /**
     * @param \DateTime $taiDateTime
     * @return \DateTime
     */
    public function taiDateTimeToUtcDateTime(\DateTime $taiDateTime): \DateTime
    {

        return $this->adjustDateTimeWithLeapSeconds($taiDateTime, TimeStandard::UTC);

    }

    /**
     * @param \DateTime $dateTimeToAdjust
     * @param TimeStandard $timeStandard
     * @return \DateTime
     */
    private function adjustDateTimeWithLeapSeconds(\DateTime $dateTimeToAdjust, TimeStandard $timeStandard) : \DateTime
    {

        $leapSeconds = $this->leapSeconds;
        $leadSecondsApplicationStartingDate = array_keys($leapSeconds)[0];
        $dateToAdjust = $dateTimeToAdjust->format('Y-m-d');
        if ($dateToAdjust >= $leadSecondsApplicationStartingDate) {
            if (!array_key_exists($dateToAdjust, $leapSeconds)) {
                $leapSeconds[$dateToAdjust] = '0';
                ksort($leapSeconds);
                $keys = array_keys($leapSeconds);
                $key = (array_search($dateTimeToAdjust->format('Y-m-d'), $keys, true) - 1);
                $leapSecondsValue = $leapSeconds[$keys[$key]];
            } else {
                $leapSecondsValue = $leapSeconds[$dateToAdjust];
            }
            $seconds = number_format($leapSecondsValue, 0, '.', '');
            $modify = sprintf($timeStandard->getLeapSecondsAdjustmentString(), $seconds);
            return $dateTimeToAdjust->modify($modify);
        }
        return $dateTimeToAdjust;

    }

}