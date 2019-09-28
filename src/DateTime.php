<?php
/**
 * @author Andrea Maccis <andrea.maccis@gmail.com>
 */

namespace Amaccis\Tai64DateTime;

use DateTimeZone;

class DateTime extends \DateTime
{

    /**
     * DateTime constructor.
     * @param string $time
     * @param DateTimeZone|null $timezone
     * @throws \Exception
     */
    public function __construct(string $time = 'now', DateTimeZone $timezone = null)
    {

        if (ctype_xdigit($time)) {
            $time = Tai64::externalTAI64FormatToStringDateTime($time);
        }
        parent::__construct($time, $timezone);

    }

    /**
     * @param string $format
     * @return string
     */
    public function format($format)
    {

        if ($format == Tai64::DATE_FORMAT_TAI64) {
            return Tai64::dateTimeToExternalTAI64Format($this);
        }
        return parent::format($format);

    }

}