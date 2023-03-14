<?php

namespace Amaccis\Tai64;
interface Tai64ToolsInterface
{

    public const FORMAT_TAI64 = 'TAI64';

    public function tai64LabelToTaiDateTime(string $tai64Label): \DateTime;

    public function taiDateTimeToTai64Label(\DateTime $taiDateTime): string;

    public function utcDateTimeToTaiDateTime(\DateTime $utcDateTime): \DateTime;

    public function taiDateTimeToUtcDateTime(\DateTime $taiDateTime): \DateTime;

}