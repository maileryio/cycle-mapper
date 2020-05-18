# Mapper for Cycle ORM

**Mapper for Cycle ORM**

[![Latest Stable Version](https://poser.pugx.org/maileryio/cycle-mapper/v/stable)](https://packagist.org/packages/maileryio/cycle-mapper)
[![Total Downloads](https://poser.pugx.org/maileryio/cycle-mapper/downloads)](https://packagist.org/packages/maileryio/cycle-mapper)
[![Build Status](https://travis-ci.com/maileryio/cycle-mapper.svg?branch=master)](https://travis-ci.com/maileryio/cycle-mapper)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/maileryio/cycle-mapper.svg)](https://scrutinizer-ci.com/g/maileryio/cycle-mapper/)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/maileryio/cycle-mapper.svg)](https://scrutinizer-ci.com/g/maileryio/cycle-mapper/)

Mapper for Cycle ORM

## Installation

The preferred way to install this library is through [composer](http://getcomposer.org/download/).

Either run

```sh
php composer.phar require "maileryio/cycle-mapper"
```

or add

```json
"maileryio/cycle-mapper": "*"
```

to the require section of your composer.json.

## Usage

```php
/**
 * @Cycle\Annotated\Annotation\Table(
 *      columns = {
 *          "created_at": @Cycle\Annotated\Annotation\Column(type = "datetime"),
 *          "updated_at": @Cycle\Annotated\Annotation\Column(type = "datetime")
 *      }
 * )
 */
class SubscriberMapper extends ChainedMapper
{
    /**
     * {@inheritdoc}
     */
    protected function getChainItemList(): ChainItemList
    {
        return new ChainItemList([
            new Timestamped('created_at', 'updated_at'),
        ]);
    }
}
```

## License

This project is released under the terms of the BSD-3-Clause [license](LICENSE).
Read more [here](http://choosealicense.com/licenses/bsd-3-clause).

Copyright © 2020, Mailery (https://mailery.io)
