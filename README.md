# TAI64-DateTime

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue.svg)](https://img.shields.io/badge/php-%5E8.1-blue.svg)

A PHP library that extends DateTime class, in order to allow to handle Tai64.

For a basic knowledge of TAI, TAI64, TAI64N and TAI64NA, [read this](https://cr.yp.to/libtai/tai64.html).
Instead, for a basic knowledge of leap seconds, [read this](https://maia.usno.navy.mil/information/what-is-a-leap-second).
TAI64N and TAI64NA formats are currently not supported by this library.

## Installation

The library is available as a package on [Packagist](http://packagist.org/packages/amaccis/tai64-datetime), 
you can install it using [Composer](http://getcomposer.org).

```shell
composer require amaccis/tai64-datetime
```

## Basic usage

### DateTime extension

#### Convert TAI64 into UTC

```php
    use Amaccis\Tai64\DateTime

    $dateTime = new DateTime("400000002a2b2c2d");
    $utc = $dateTime->format('Y-m-d H:i:s');
    var_dump($utc); // 1992-06-02 08:06:43       
```

#### Convert UTC into TAI64

```php
    use Amaccis\Tai64\DateTime

    $dateTime = new DateTime("1992-06-02 08:06:43");
    $tai64Label = $dateTime->format("TAI64");
    var_dump($tai64Label); // 400000002a2b2c2d'
```

### TAI64 tools

```php
    use Amaccis\Tai64\Tai64Tools
    
    $tai64Tools = new Tai64Tools();
    $tai64Label = "4000000000000000";
    $taiDateTime = $tai64Tools->tai64LabelToTaiDateTime($tai64Label);
    var_dump($taiDateTime->format('Y-m-d H:i:s')); // 1970-01-01 00:00:00
```

```php
    use Amaccis\Tai64\Tai64Tools

    $tai64Tools = new Tai64Tools();
    $taiDateTime = new \DateTime("1970-01-01 00:00:00")
    $tai64Label = $tai64Tools->taiDateTimeToTai64Label($taiDateTime);
    var_dump($tai64Label); // 4000000000000000
```

```php
    use Amaccis\Tai64\Tai64Tools

    $tai64Tools = new Tai64Tools();
    $utcDateTime = new \DateTime("1992-06-02 08:06:43");
    $taiDateTime = $tai64Tools->utcDateTimeToTaiDateTime($utcDateTime);
    var_dump($taiDateTime->format('Y-m-d H:i:s')); // 1992-06-02 08:07:09
```

```php
    use Amaccis\Tai64\Tai64Tools

    $tai64Tools = new Tai64Tools();
    $taiDateTime = new \DateTime("1992-06-02 08:07:09");
    $utcDateTime = $tai64Tools->taiDateTimeToUtcDateTime($taiDateTime);
    var_dump($utcDateTime->format('Y-m-d H:i:s')); // 1992-06-02 08:06:43
```



