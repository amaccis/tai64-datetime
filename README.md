# TAI64-DateTime

[![PHP Version](https://img.shields.io/badge/php-%5E7.1-blue.svg)](https://img.shields.io/badge/php-%5E7.1-blue.svg)

[![Build Status](https://travis-ci.org/amaccis/tai64-datetime.svg?branch=master)](https://travis-ci.org/amaccis/tai64-datetime)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/amaccis/tai64-datetime/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/amaccis/tai64-datetime/?branch=master)

A PHP library that extends DateTime class, in order to allow to handle Tai64.

## Installation

Package is available on [Packagist](http://packagist.org/packages/amaccis/tai64-datetime), 
you can install it using [Composer](http://getcomposer.org).

```shell
composer require amaccis/tai64-datetime
```

## Basic usage

If you feel to need a basic knowledge of TAI, TAI64, TAI64N and TAI64NA, [read this](https://cr.yp.to/libtai/tai64.html).
Instead, to know more about leap seconds, [read this](http://maia.usno.navy.mil/leapsec.html).
TAI64N and TAI64NA are not currently managed.

### Convert TAI64 into UTC

```php
    use Amaccis/Tai64DateTime/DateTime

    $dateTime = new DateTime("400000002a2b2c2d");
    $utc = $dateTime->format('Y-m-d H:i:s');
    var_dump($utc); // 1992-06-02 08:06:43       
```

### Convert UTC into TAI64

```php
    use Amaccis/Tai64DateTime/DateTime

    $dateTime = new DateTime("1992-06-02 08:06:43");
    $hexString = $dateTime->format("TAI64");
    var_dump($hexString); // 400000002a2b2c2d'
```


