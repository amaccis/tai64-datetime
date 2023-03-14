<?php

namespace Amaccis\Tai64\Enum;

enum TimeStandard
{
    case UTC;

    case TAI;

    public function getLeapSecondsAdjustmentString(): string
    {

        return match($this) {
            self::UTC => '-%s seconds',
            self::TAI => '+%s seconds'
        };

    }

}