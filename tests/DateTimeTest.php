<?php
/**
 * @author Andrea Maccis <andrea.maccis@gmail.com>
 */

namespace Amaccis\Tai64DateTime;

use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testIsThereAnySyntaxError() : void
    {
        $dateTime = new DateTime();
        $this->assertIsObject($dateTime);
    }

    /**
     * @throws \Exception
     */
    public function testNativeDateTimeIsWorking() : void
    {
        $dateTime = new DateTime("20-05-1981");
        $this->assertEquals("Wednesday", $dateTime->format("l"));
    }

    /**
     * @throws \Exception
     */
    public function testConstructor() : void
    {
        /**
         * 400000002a2b2c2d
         * 1992-06-02 08:07:09 TAI
         * 1992-06-02 08:06:43 UTC
         */
        $hexString = "400000002a2b2c2d";
        $dateTime = new DateTime($hexString);
        $this->assertSame('1992-06-02 08:06:43', $dateTime->format(Tai64::DATE_FORMAT_YYYYMMDDHHIISS));

    }

    public function testFormat() : void
    {

        /**
         * 400000002a2b2c2d
         * 1992-06-02 08:07:09 TAI
         * 1992-06-02 08:06:43 UTC
         */
        $dateTime = new DateTime("1992-06-02 08:06:43");
        $this->assertSame('400000002a2b2c2d', $dateTime->format(Tai64::DATE_FORMAT_TAI64));
    }

}