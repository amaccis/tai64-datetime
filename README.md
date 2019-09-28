# TAI64-DateTime

A PHP library that extends DateTime class, in order to allow to handle Tai64.

## Basic usage

If you feel to need a basic knowledge of TAI, TAI64, TAI64N and TAI64NA, [read this](https://cr.yp.to/libtai/tai64.html).
Instead, to know more about leap seconds, [read this](http://maia.usno.navy.mil/leapsec.html).
TAI64N and TAI64NA are not currently managed.

### Convert TAI64 into UTC

```php
    $dateTime = DateTime("400000002a2b2c2d");
    $utc = $dateTime->format('Y-m-d H:i:s');
    var_dump($utc); // 1992-06-02 08:06:43       
```

### Convert UTC into TAI64

```php
    $dateTime = new DateTime("1992-06-02 08:06:43");
    $hexString = $dateTime->format("TAI64");
    var_dump($hexString); // 400000002a2b2c2d'
```


