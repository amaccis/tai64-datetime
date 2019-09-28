<?php
/**
 * @author Andrea Maccis <andrea.maccis@gmail.com>
 */

namespace Amaccis\Tai64DateTime;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class Tai64Test
 * @package Amaccis\Tai64DateTime
 */
class Tai64Test extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testExternalTAI64FormatToStringDateTime() : void
    {

        /**
         * 400000002a2b2c2d
         * 1992-06-02 08:07:09 TAI
         * 1992-06-02 08:06:43 UTC
         */
        $hexString = "400000002a2b2c2d";
        $stringDateTime = Tai64::externalTAI64FormatToStringDateTime($hexString);
        $this->assertSame('1992-06-02 08:06:43', $stringDateTime);

    }


    public function testExternalTAI64FormatToStringDateTimeFailsForNotAllowedSecondsValue() : void
    {

        $this->expectException(InvalidArgumentException::class);
        $hexString = "400000002a2b2c2c";
        Tai64::externalTAI64FormatToStringDateTime($hexString);

    }


    /**
     * @throws \Exception
     */
    public function testDateTimeToExternalTAI64Format() : void
    {

        /**
         * 400000002a2b2c2d
         * 1992-06-02 08:07:09 TAI
         * 1992-06-02 08:06:43 UTC
         */
        $dateTime = new DateTime("1992-06-02 08:06:43");
        $externalTAI64Format = Tai64::dateTimeToExternalTAI64Format($dateTime);
        $this->assertSame('400000002a2b2c2d', $externalTAI64Format);

    }

    /**
     * @throws \Exception
     */
    public function testDateTimeToExternalTAI64FormatFailsForNotAllowedDateValue() : void
    {

        $this->expectException(InvalidArgumentException::class);
        $dateTime = new DateTime("1971-12-31 08:06:43");
        Tai64::dateTimeToExternalTAI64Format($dateTime);

    }

    /**
     * @throws \Exception
     */
    public function testAdjust() : void
    {

        $adjustedDate = Tai64::adjust(new DateTime("1992-06-02 08:07:09"));
        $this->assertSame("1992-06-02 08:06:43", $adjustedDate->format(Tai64::DATE_FORMAT_YYYYMMDDHHIISS));

        $adjustedDate = Tai64::adjust(new DateTime("1981-07-01 10:20:31"));
        $this->assertSame("1981-07-01 10:20:11", $adjustedDate->format(Tai64::DATE_FORMAT_YYYYMMDDHHIISS));

    }

}
