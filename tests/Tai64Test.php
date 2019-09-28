<?php
/**
 * @author Andrea Maccis <andrea.maccis@gmail.com>
 */

namespace Amaccis\Tai64DateTime;

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

    public function testAdjust() : void
    {

        $externalTAI64Format = "707472429";
        $adjustedDate = Tai64::adjust(DateTime::createFromFormat(Tai64::DATE_FORMAT_SECONDS, $externalTAI64Format));
        $this->assertSame("1992-06-02 08:06:43", $adjustedDate->format(Tai64::DATE_FORMAT_YYYYMMDDHHIISS));

    }

}
