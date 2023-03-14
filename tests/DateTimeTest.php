<?php

namespace Amaccis\Tai64\Tests;

use Amaccis\Tai64\DateTime;
use Amaccis\Tai64\Tai64ToolsInterface;
use Exception;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testIsThereAnySyntaxError() : void
    {

        $dateTime = new DateTime();
        $this->assertIsObject($dateTime);

    }

    /**
     * @throws Exception
     */
    public function testDateTimeIsWorking() : void
    {

        $dateTime = new DateTime("20-05-1981");
        $this->assertEquals("Wednesday", $dateTime->format("l"));

    }

    /**
     * @dataProvider commonProvider
     * @param string $tai64Label
     * @param string $taiDate
     * @param string $utcDate
     * @throws Exception
     */
    public function testConstructor(string $tai64Label, string $taiDate, string $utcDate) : void
    {

        $dateTime = new DateTime($tai64Label);
        $this->assertSame($utcDate, $dateTime->format('Y-m-d H:i:s'));

    }

    /**
     * @dataProvider commonProvider
     * @param string $tai64Label
     * @param string $taiDate
     * @param string $utcDate
     * @return void
     * @throws Exception
     */
    public function testFormat(string $tai64Label, string $taiDate, string $utcDate) : void
    {

        $dateTime = new DateTime($utcDate);
        $this->assertSame($tai64Label, $dateTime->format(Tai64ToolsInterface::FORMAT_TAI64));

    }

    public static function commonProvider(): array
    {

        return [
            [
                '3fffffffffffffff',     // TAI64 label
                '1969-12-31 23:59:59',  // TAI
                '1969-12-31 23:59:59'   // UTC
            ],
            [
                '4000000000000000',     // TAI64 label
                '1970-01-01 00:00:00',  // TAI
                '1970-01-01 00:00:00'   // UTC
            ],
            [
                '400000002a2b2c2d',     // TAI64 label
                '1992-06-02 08:07:09',  // TAI
                '1992-06-02 08:06:43'   // UTC
            ]
        ];

    }

}