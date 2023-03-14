<?php

namespace Amaccis\Tai64\Tests;

use Amaccis\Tai64\Tai64Tools;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class Tai64ToolsTest extends TestCase
{

    private Tai64Tools $tai64Tools;

    public function setUp(): void
    {
        $this->tai64Tools = new Tai64Tools();
    }

    /**
     * @dataProvider commonProvider
     * @param string $tai64Label
     * @param string $taiDate
     * @param string $utcDate
     * @return void
     */
    public function testTai64LabelToTaiDateTimeForProperValues(string $tai64Label, string $taiDate, string $utcDate): void
    {

        $taiDateTime = $this->tai64Tools->tai64LabelToTaiDateTime($tai64Label);
        $this->assertIsObject($taiDateTime);
        $this->assertInstanceOf(\DateTime::class, $taiDateTime);
        $this->assertSame($taiDate, $taiDateTime->format('Y-m-d H:i:s'));

    }

    public function testTai64LabelToTaiDateTimeForWrongTai64LabelValue() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->tai64Tools->tai64LabelToTaiDateTime("wrongTai64Label");
    }


    /**
     * @dataProvider commonProvider
     * @param string $tai64Label
     * @param string $taiDate
     * @param string $utcDate
     * @throws Exception
     */
    public function testTaiDateTimeToTai64Label(string $tai64Label, string $taiDate, string $utcDate): void
    {

        $taiDateTime = new \DateTime($taiDate);
        $actualTai64Label = $this->tai64Tools->taiDateTimeToTai64Label($taiDateTime);
        $this->assertSame($tai64Label, $actualTai64Label);

    }

    /**
     * @dataProvider commonProvider
     * @param string $tai64Label
     * @param string $taiDate
     * @param string $utcDate
     * @return void
     * @throws Exception
     */
    public function testUtcDateTimeToTaiDateTime(string $tai64Label, string $taiDate, string $utcDate): void
    {

        $utcDateTime = new \DateTime($utcDate);
        $taiDateTime = $this->tai64Tools->utcDateTimeToTaiDateTime($utcDateTime);
        $this->assertSame($taiDate, $taiDateTime->format('Y-m-d H:i:s'));

    }

    /**
     * @dataProvider commonProvider
     * @param string $tai64Label
     * @param string $taiDate
     * @param string $utcDate
     * @return void
     * @throws Exception
     */
    public function testTaiDateTimeToUtcDateTime(string $tai64Label, string $taiDate, string $utcDate): void
    {

        $taiDateTime = new \DateTime($taiDate);
        $utcDateTime = $this->tai64Tools->taiDateTimeToUtcDateTime($taiDateTime);
        $this->assertSame($utcDate, $utcDateTime->format('Y-m-d H:i:s'));

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