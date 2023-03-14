<?php

namespace Amaccis\Tai64;

use Exception;

class DateTime extends \DateTime implements \DateTimeInterface
{

    private Tai64ToolsInterface $tai64Tools;

    /**
     * DateTime constructor.
     * @param string $datetime
     * @param \DateTimeZone|null $timezone
     * @throws Exception
     */
    public function __construct(string $datetime = 'now', ?\DateTimeZone $timezone = null)
    {

        $this->tai64Tools = new Tai64Tools();
        if (ctype_xdigit($datetime)) {
            $taiDateTime = $this->tai64Tools->tai64LabelToTaiDateTime($datetime);
            $utcDateTime = $this->tai64Tools->taiDateTimeToUtcDateTime($taiDateTime);
            $datetime = $utcDateTime->format(\DateTimeInterface::ATOM);
        }
        parent::__construct($datetime, $timezone);

    }

    /**
     * @param string $format
     * @return string
     */
    public function format(string $format): string
    {

        if ($format === Tai64ToolsInterface::FORMAT_TAI64) {
            $taiDateTime = $this->tai64Tools->utcDateTimeToTaiDateTime($this);
            return $this->tai64Tools->taiDateTimeToTai64Label($taiDateTime);
        }
        return parent::format($format);

    }

}