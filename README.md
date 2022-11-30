# Dictum

[![PHP from Packagist](https://img.shields.io/packagist/php-v/decodelabs/dictum?style=flat)](https://packagist.org/packages/decodelabs/dictum)
[![Latest Version](https://img.shields.io/packagist/v/decodelabs/dictum.svg?style=flat)](https://packagist.org/packages/decodelabs/dictum)
[![Total Downloads](https://img.shields.io/packagist/dt/decodelabs/dictum.svg?style=flat)](https://packagist.org/packages/decodelabs/dictum)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/decodelabs/dictum/Integrate)](https://github.com/decodelabs/dictum/actions/workflows/integrate.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44CC11.svg?longCache=true&style=flat)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/packagist/l/decodelabs/dictum?style=flat)](https://packagist.org/packages/decodelabs/dictum)

### Text formatting tools for PHP

Dictum provides a collection of commonly required text parsing and processing features.

_Get news and updates on the [DecodeLabs blog](https://blog.decodelabs.com)._

---

## Installation

Install via Composer:

```bash
composer require decodelabs/dictum
```

## Usage

### Importing

Dictum uses [Veneer](https://github.com/decodelabs/veneer) to provide a unified frontage under <code>DecodeLabs\Dictum</code>.
You can access all the primary functionality via this static frontage without compromising testing and dependency injection.

### Formatters

The main Veneer frontage of Dictum exposes a set of predictable text / key formatters which can be used to quickly prepare strings for specific actions.

```php
use DecodeLabs\Dictum;

echo Dictum::name('geoff-randomName');
// Geoff Random Name

echo Dictum::firstName('geoff-randomName');
// Geoff

echo Dictum::initials('geoff-randomName');
// GRN

echo Dictum::initialsAndSurname('geoff-randomName');
// GR Name

echo Dictum::initialMiddleNames('geoff-randomName');
// Geoff R Name


echo Dictum::consonants('here\'s a Random-string of text');
// hr's  Rndm-strng f txt

echo Dictum::label('here\'s a Random-string of text');
// Here's a random string of text

echo Dictum::id('here\'s a Random-string of text');
// HeresARandomStringOfText

echo Dictum::camel('here\'s a Random-string of text');
// heresARandomStringOfText

echo Dictum::constant('here\'s a Random-string of text');
// HERE_S_A_RANDOM_STRING_OF_TEXT


echo Dictum::slug('here\'s a Random-string of text / other stuff');
// heres-a-random-string-of-text-other-stuff

echo Dictum::pathSlug('here\'s a Random-string of text / other stuff');
// heres-a-random-string-of-text/other-stuff

echo Dictum::actionSlug('here\'s a Random-string of text / other stuff');
// here's-a-random-string-of-text-/-other-stuff

echo Dictum::fileName('here\'s a Random-string of text / other stuff');
// here's-a-Random-string-of-text-_-other-stuff

echo Dictum::shorten('here\'s a Random-string of text / other stuff', 10);
// here's a…


echo Dictum::numericToAlpha(23345452);
// aybfra

echo Dictum::alphaToNumeric('aybfra')
// 23345452

echo Dictum::baseConvert(23345452, 10, 36);
// 4J4RS


echo Dictum::toBoolean('yes') ? 'true' : 'false';
// true
```


### Text

The formatters above predominantly use the <code>Text</code> class to process the strings provided. This class exposes a full suite of multibyte aware string manipulation functionality in an immutable collection format.

For example, the above <code>id()</code> method is defined as:

```php
echo (new Text($id))
    ->toUtf8()
    ->toAscii()
    ->regexReplace('([^ ])([A-Z])', '\\1 \\2')
    ->replace(['-', '.', '+'], ' ')
    ->regexReplace('[^a-zA-Z0-9_ ]', '')
    ->toTitleCase()
    ->replace(' ', '')
    ->__toString();
```

Note, regexes are based off the mb_ereg functions and as such do not use delimiters in their patterns.


## Plugins

Dictum provides generic interfaces for defining locale-aware formatter plugins that can be implemented by different output generators.

Currently, Time and Number are available, defining predictable methods for formatting dates and various forms of number.

Dictum offers a plain text version of these interfaces:

```php
use DecodeLabs\Dictum;

// Custom format
Dictum::$time->format('now', 'd/m/Y', 'Europe/London');

// Locale format
// When timezone is true it is fetched from Cosmos::$timezone
Dictum::$time->locale('now', 'long', 'long', true);

// Locale shortcuts
Dictum::$time->dateTime('tomorrow'); // medium
Dictum::$time->longTime('yesterday');
Dictum::$time->shortDate('yesterday');
// ...etc


// Intervals
Dictum::$time->since('yesterday'); // 1 day ago
Dictum::$time->until('tomorrow'); // 1 day from now
Dictum::$time->sinceAbs('yesterday'); // 1 day
Dictum::$time->untilAbs('yesterday'); // -1 day
Dictum::$time->between('yesterday', 'tomorrow'); // 1 day



// Numbers
Dictum::$number->format(16.5, 'px'); // 16.5 px
Dictum::$number->format(16.5, 'px', 'de'); // 16,5 px
Dictum::$number->decimal(16.534643, 2); // 16.53
Dictum::$number->currency(16.534643, 'GBP'); // £16.53
Dictum::$number->percent(16.534643, 50, 2); // 33.07%
Dictum::$number->scientific(16.534643); // 1.6534643E1
Dictum::$number->spellout(16.534643); // sixteen point five three four six four three
Dictum::$number->ordinal(16.534643); // 17th
Dictum::$number->diff(16.534643); // ⬆ 16.535
Dictum::$number->fileSize(16534643); // 15.77 MiB
Dictum::$number->fileSizeDec(16534643); // 16.53 MB
```

See [Tagged](https://github.com/decodelabs/tagged) for equivalent HTML implementations of these interfaces.

## Licensing
Dictum is licensed under the MIT License. See [LICENSE](./LICENSE) for the full license text.
