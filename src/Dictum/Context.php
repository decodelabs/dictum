<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum;

use DecodeLabs\Exceptional;

use Stringable;

class Context
{
    /**
     * Create Text buffer
     *
     * @param string|Stringable|int|float|null $text
     */
    public function text($text, ?string $encoding = null): ?Text
    {
        if ($text === null) {
            return null;
        } elseif ($text instanceof Text) {
            return $text;
        }

        return new Text((string)$text, $encoding);
    }



    /**
     * Normalize words, convert words to upper
     *
     * @param string|Stringable|int|float|null $name
     */
    public function name($name, ?string $encoding = null): ?string
    {
        if (null === ($name = $this->text($name, $encoding))) {
            return null;
        }

        return $name
            ->toUtf8()
            ->replace(['-', '_'], ' ')
            ->regexReplace('([^ ])([A-Z/])', '\\1 \\2')
            ->regexReplace('([/])([^ ])', '\\1 \\2')
            ->toTitleCase()
            ->__toString();
    }

    /**
     * Get first name from full name
     *
     * @param string|Stringable|int|float|null $fullName
     */
    public function firstName($fullName, ?string $encoding = null): ?string
    {
        if (!strlen($fullName = (string)$fullName)) {
            return null;
        }

        $parts = explode(' ', $fullName);
        $output = (string)array_shift($parts);

        if (in_array(strtolower($output), ['mr', 'ms', 'mrs', 'miss', 'dr'])) {
            if (isset($parts[1])) {
                $output = (string)array_shift($parts);
            } else {
                $output = $fullName;
            }
        }

        if (strlen($output) < 3) {
            $output .= ' ' . array_pop($parts);
        }

        return (new Text($output, $encoding))
            ->toUtf8()
            ->firstToUpperCase()
            ->__toString();
    }

    /**
     * Initialise name
     *
     * @param string|Stringable|int|float|null $name
     */
    public function initials($name, bool $extendShort = true, ?string $encoding = null): ?string
    {
        if (null === ($name = $this->text($name, $encoding))) {
            return null;
        }

        $output = $name
            ->toUtf8()
            ->replace(['-', '_'], ' ')
            ->regexReplace('[^A-Za-z0-9\s]', '')
            ->regexReplace('([^ ])([A-Z])', '\\1 \\2')
            ->toTitleCase()
            ->regexReplace('[^A-Z0-9]', '');

        if (
            $extendShort &&
            $output->getLength() == 1
        ) {
            $output = $output->append(
                $name
                    ->toAscii()
                    ->replace(['a', 'e', 'i', 'o', 'u'], '')
                    ->getChar(1)
            );
        }

        return $output->__toString();
    }

    /**
     * Get initials and surname
     *
     * @param string|Stringable|int|float|null $name
     */
    public function initialsAndSurname($name, ?string $encoding = null): ?string
    {
        if (!strlen($name = (string)$name)) {
            return null;
        }

        $parts = explode(' ', $name);
        $surname = array_pop($parts);

        if (in_array(strtolower($parts[0] ?? ''), ['mr', 'ms', 'mrs', 'miss', 'dr'])) {
            array_shift($parts);
        }

        if (null === ($output = $this->initials(implode(' ', $parts), false))) {
            return null;
        }

        return (new Text($surname, $encoding))
            ->toUtf8()
            ->firstToUpperCase()
            ->prepend($output . ' ')
            ->__toString();
    }

    /**
     * Shorten middle names
     *
     * @param string|Stringable|int|float|null $name
     */
    public function initialMiddleNames($name, ?string $encoding = null): ?string
    {
        if (!strlen($name = (string)$name)) {
            return null;
        }

        $parts = explode(' ', $name);
        $surname = array_pop($parts);

        if (in_array(strtolower($parts[0] ?? ''), ['mr', 'ms', 'mrs', 'miss', 'dr'])) {
            array_shift($parts);
        }

        $output = (new Text((string)array_shift($parts), $encoding))
            ->toUtf8()
            ->firstToUpperCase();

        if (!$output->isEmpty()) {
            $output = $output->append(' ');
        }

        if (!empty($parts)) {
            $output = $output
                ->append(
                    $this->initials(implode(' ', $parts), false, $encoding)
                )
                ->append(' ');
        }

        return $output
            ->append(
                (new Text($surname, $encoding))
                    ->toUtf8()
                    ->firstToUpperCase()
            )
            ->__toString();
    }

    /**
     * Strip vowels from text
     *
     * @param string|Stringable|int|float|null $text
     */
    public function consonants($text, ?string $encoding = null): ?string
    {
        if (null === ($text = $this->text($text, $encoding))) {
            return null;
        }

        return $text
            ->toUtf8()
            ->toAscii()
            ->regexReplace('[aeiou]+', '')
            ->__toString();
    }

    /**
     * Uppercase first, to ASCII, strip some chars
     *
     * @param string|Stringable|int|float|null $label
     */
    public function label($label, ?string $encoding = null): ?string
    {
        if (null === ($label = $this->text($label, $encoding))) {
            return null;
        }

        return $label
            ->toUtf8()
            ->regexReplace('[-_]', ' ')
            ->regexReplace('([a-z])([A-Z])', '\\1 \\2')
            ->regexReplace('[\s]+', ' ')
            ->toLowerCase()
            ->firstToUpperCase()
            ->__toString();
    }

    /**
     * Convert to Id
     *
     * @param string|Stringable|int|float|null $id
     */
    public function id($id, ?string $encoding = null): ?string
    {
        if (null === ($id = $this->text($id, $encoding))) {
            return null;
        }

        return $id
            ->toUtf8()
            ->toAscii()
            ->regexReplace('([^ ])([A-Z])', '\\1 \\2')
            ->replace(['-', '.', '+'], ' ')
            ->regexReplace('[^a-zA-Z0-9_ ]', '')
            ->toTitleCase()
            ->replace(' ', '')
            ->__toString();
    }

    /**
     * Convert to camelCase
     *
     * @param string|Stringable|int|float|null $id
     */
    public function camel($id, ?string $encoding = null): ?string
    {
        if (null === ($id = $this->text($id, $encoding))) {
            return null;
        }

        return $id
            ->toUtf8()
            ->toAscii()
            ->regexReplace('([^ ])([A-Z])', '\\1 \\2')
            ->replace(['-', '.', '+'], ' ')
            ->regexReplace('[^a-zA-Z0-9_ ]', '')
            ->toTitleCase()
            ->replace(' ', '')
            ->firstToLowerCase()
            ->__toString();
    }

    /**
     * Format as PHP_CONSTANT
     *
     * @param string|Stringable|int|float|null $constant
     */
    public function constant($constant, ?string $encoding = null): ?string
    {
        if (null === ($constant = $this->text($constant, $encoding))) {
            return null;
        }

        return $constant
            ->toUtf8()
            ->toAscii()
            ->regexReplace('[^a-zA-Z0-9]', ' ')
            ->regexReplace('([^ ])([A-Z])', '\\1 \\2')
            ->regexReplace('[^a-zA-Z0-9_ ]', '')
            ->trim()
            ->replace(' ', '_')
            ->replace('__', '_')
            ->toUpperCase()
            ->__toString();
    }

    /**
     * Convert to slug
     *
     * @param string|Stringable|int|float|null $slug
     */
    public function slug($slug, string $allowedChars = '', ?string $encoding = null): ?string
    {
        if (null === ($slug = $this->text($slug, $encoding))) {
            return null;
        }

        return $slug
            ->toUtf8()
            ->toAscii()
            ->regexReplace('([a-z][a-z])([A-Z][a-z])', '\\1 \\2')
            ->toLowerCase()
            ->regexReplace('[\s_/]', '-')
            ->regexReplace('[^a-z0-9_\-' . preg_quote($allowedChars) . ']', '')
            ->regexReplace('-+', '-')
            ->trim(' -')
            ->__toString();
    }

    /**
     * Convert to path format slug
     *
     * @param string|Stringable|int|float|null $slug
     */
    public function pathSlug($slug, string $allowedChars = '', ?string $encoding = null): ?string
    {
        if (
            $slug === null ||
            !strlen($slug = (string)$slug)
        ) {
            return null;
        }

        $parts = explode('/', $slug);

        foreach ($parts as $i => $part) {
            $part = $this->slug($part, $allowedChars, $encoding);

            if (
                $part === null ||
                !strlen($part)
            ) {
                unset($parts[$i]);
                continue;
            }

            $parts[$i] = (string)$part;
        }

        return (new Text(implode('/', $parts), $encoding))
            ->toUtf8()
            ->__toString();
    }

    /**
     * Convert to URL action slug
     *
     * @param string|Stringable|int|float|null $slug
     */
    public function actionSlug($slug, ?string $encoding = null): ?string
    {
        if (null === ($slug = $this->text($slug, $encoding))) {
            return null;
        }

        return $slug
            ->toUtf8()
            ->toAscii()
            ->regexReplace('([^ ])([A-Z])', '\\1-\\2')
            ->replace(' ', '-')
            ->toLowerCase()
            ->regexReplace('-+', '-')
            ->trim(' -')
            ->__toString();
    }

    /**
     * Remove non-filesystem compatible chars
     *
     * @param string|Stringable|int|float|null $fileName
     */
    public function fileName($fileName, bool $allowSpaces = false, ?string $encoding = null): ?string
    {
        if (null === ($fileName = $this->text($fileName, $encoding))) {
            return null;
        }

        $fileName = $fileName
            ->toUtf8()
            ->toAscii()
            ->replace('/', '_')
            ->regexReplace('[\/\\?%*:|"<>]', '');

        if (!$allowSpaces) {
            $fileName = $fileName->replace(' ', '-');
        }

        return $fileName
            ->__toString();
    }

    /**
     * Cap length of string, add ellipsis if needed
     *
     * @param string|Stringable|int|float|null $text
     */
    public function shorten($text, int $length, bool $rtl = false, ?string $encoding = null): ?string
    {
        if (null === ($text = $this->text($text, $encoding))) {
            return null;
        }

        $text->toUtf8();

        if ($length < 5) {
            $length = 5;
        }

        if ($text->getLength() > $length - 1) {
            if ($rtl) {
                $text = $text->slice(-($length - 1))
                    ->trimLeft('., ')
                    ->prepend('…');
            } else {
                $text = $text->slice(0, $length - 1)
                    ->trimRight('., ')
                    ->append('…');
            }
        }

        return $text->__toString();
    }

    /**
     * Wrapper around Text::numericToAlpha
     */
    public function numericToAlpha(?int $number, ?string $encoding = null): ?string
    {
        if ($number === null) {
            return null;
        }

        return Text::numericToAlpha($number)
            ->__toString();
    }

    /**
     * Wrapper around alphaToNumeric
     *
     * @param string|Stringable|int|float|null $text
     */
    public function alphaToNumeric($text, ?string $encoding = null): ?int
    {
        if (null === ($text = $this->text($text, $encoding))) {
            return null;
        }

        return $text->alphaToNumeric();
    }

    /**
     * Convert between any base from 2 to 62
     *
     * @param string|Stringable|int|float|null $input
     */
    public function baseConvert($input, int $fromBase, int $toBase, int $pad = 1): ?string
    {
        if ($input === null) {
            return null;
        }

        if (
            $fromBase < 2 ||
            $fromBase > 62 ||
            $toBase < 2 ||
            $toBase > 62
        ) {
            throw Exceptional::Overflow('Base must be between 2 and 62');
        }

        if (!is_string($input)) {
            $input = sprintf('%0.0F', $input);
        }


        if (extension_loaded('gmp')) {
            $output = gmp_strval(gmp_init($input, $fromBase), $toBase);

            if ($pad > 1) {
                $output = str_pad($output, $pad, '0', STR_PAD_LEFT);
            }

            return $output;
        }


        $digitChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $inDigits = [];
        $outChars = '';

        $input = strtolower($input);
        $length = strlen($input);


        for ($i = 0; $i < $length; $i++) {
            $digit = ord($input[$i]) - 48;

            if ($digit > 9) {
                $digit -= 39;
            }

            if ($digit > $fromBase) {
                throw Exceptional::Overflow('Digit as exceeded base: ' . $fromBase);
            }

            $inDigits[] = $digit;
        }


        while (!empty($inDigits)) {
            $work = 0;
            $workDigits = [];

            foreach ($inDigits as $digit) {
                $work *= $fromBase;
                $work += $digit;


                if ($work < $toBase) {
                    if (!empty($workDigits)) {
                        $workDigits[] = 0;
                    }
                } else {
                    $workDigits[] = (int)($work / $toBase);
                    $work %= $toBase;
                }
            }

            $outChars = $digitChars[$work] . $outChars;
            $inDigits = $workDigits;
        }

        return str_pad($outChars, $pad, '0', STR_PAD_LEFT);
    }




    /**
     * String to boolean
     *
     * @param string|Stringable|int|float|null $text
     */
    public function toBoolean($text, ?string $encoding = null): bool
    {
        if (is_int($text) || is_float($text)) {
            return (bool)$text;
        }

        if (null === ($text = $this->text($text, $encoding))) {
            return false;
        }

        return $text->toBoolean();
    }

    /**
     * Compare two strings
     *
     * @param string|Stringable|int|float|null $string1
     * @param string|Stringable|int|float|null $string2
     */
    public function compare($string1, $string2): bool
    {
        $string1 = $this->text($string1);
        $string2 = $this->text($string2);

        if ($string1 === null || $string2 === null) {
            return $string1 === $string2;
        }

        $string1 = $string1
            ->toUtf8()
            ->replace("\r\n", "\n")
            ->__toString();

        $string2 = $string2
            ->toUtf8()
            ->replace("\r\n", "\n")
            ->__toString();

        return $string1 === $string2;
    }


    /**
     * Only contains alpha characters
     *
     * @param string|Stringable|int|float|null $text
     */
    public function isAlpha($text): bool
    {
        if (null === ($text = $this->text($text))) {
            return false;
        }

        return $text->isAlpha();
    }

    /**
     * Only contains alpha numeric characters
     *
     * @param string|Stringable|int|float|null $text
     */
    public function isAlphaNumeric($text): bool
    {
        if (null === ($text = $this->text($text))) {
            return false;
        }

        return $text->isAlphaNumeric();
    }

    /**
     * Only contains digits
     *
     * @param string|Stringable|int|float|null $text
     */
    public function isDigit($text): bool
    {
        if (null === ($text = $this->text($text))) {
            return false;
        }

        return $text->isDigit();
    }


    /**
     * Only contains whitespace
     *
     * @param string|Stringable|int|float|null $text
     */
    public function isWhitespace($text): bool
    {
        if (null === ($text = $this->text($text))) {
            return false;
        }

        return $text->isWhitespace();
    }

    /**
     * Only contains whitespace or empty
     *
     * @param string|Stringable|int|float|null $text
     */
    public function isBlank($text): bool
    {
        if (null === ($text = $this->text($text))) {
            return false;
        }

        return $text->isBlank();
    }

    /**
     * Only contains hex
     *
     * @param string|Stringable|int|float|null $text
     */
    public function isHex($text): bool
    {
        if (null === ($text = $this->text($text))) {
            return false;
        }

        return $text->isHex();
    }


    /**
     * Count number of whole words
     *
     * @param string|Stringable|int|float|null $text
     */
    public function countWords($text): int
    {
        if (null === ($text = $this->text($text))) {
            return 0;
        }

        return $text->trim()
            ->append(' ')
            ->regexReplace('[^\w\s]+', '')
            ->regexReplace('[\s]+', ' ')
            ->countInstances(' ');
    }
}
