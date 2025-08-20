<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs;

use DecodeLabs\Dictum\Text;
use DecodeLabs\Kingdom\Service;
use DecodeLabs\Kingdom\ServiceTrait;
use Stringable;

class Dictum implements Service
{
    use ServiceTrait;


    /**
     * @return ($text is null ? null : Text)
     */
    public static function text(
        string|Stringable|int|float|null $text,
        ?string $encoding = null
    ): ?Text {
        if ($text === null) {
            return null;
        } elseif ($text instanceof Text) {
            return $text;
        }

        return new Text((string)$text, $encoding);
    }



    /**
     * @return ($name is null ? null : string)
     */
    public static function name(
        string|Stringable|int|float|null $name,
        ?string $encoding = null
    ): ?string {
        if (null === ($name = static::text($name, $encoding))) {
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
     * @return ($fullName is null ? null : string)
     */
    public static function firstName(
        string|Stringable|int|float|null $fullName,
        ?string $encoding = null
    ): ?string {
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
     * @return ($name is null ? null : string)
     */
    public static function initials(
        string|Stringable|int|float|null $name,
        bool $extendShort = true,
        ?string $encoding = null
    ): ?string {
        if (null === ($name = static::text($name, $encoding))) {
            return null;
        }

        $output = $name
            ->toUtf8()
            ->replace(['-', '_'], ' ')
            ->regexReplace('[^A-Za-z0-9\s]', '');

        if ($output->contains(' ')) {
            $output = $output->toTitleCase();
        }

        $output = $output
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
     * @return ($name is null ? null : string)
     */
    public static function initialsAndSurname(
        string|Stringable|int|float|null $name,
        ?string $encoding = null
    ): ?string {
        if (!strlen($name = (string)$name)) {
            return null;
        }

        $parts = explode(' ', $name);
        $surname = array_pop($parts);

        if (in_array(strtolower($parts[0] ?? ''), ['mr', 'ms', 'mrs', 'miss', 'dr'])) {
            array_shift($parts);
        }

        $output = static::initials(implode(' ', $parts), false);

        return (new Text($surname, $encoding))
            ->toUtf8()
            ->firstToUpperCase()
            ->prepend($output . ' ')
            ->__toString();
    }

    /**
     * @return ($name is null ? null : string)
     */
    public static function initialMiddleNames(
        string|Stringable|int|float|null $name,
        ?string $encoding = null
    ): ?string {
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
                    static::initials(implode(' ', $parts), false, $encoding)
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
     * @return ($text is null ? null : string)
     */
    public static function consonants(
        string|Stringable|int|float|null $text,
        ?string $encoding = null
    ): ?string {
        if (null === ($text = static::text($text, $encoding))) {
            return null;
        }

        return $text
            ->toUtf8()
            ->toAscii()
            ->regexReplace('[aeiou]+', '')
            ->__toString();
    }

    /**
     * @return ($label is null ? null : string)
     */
    public static function label(
        string|Stringable|int|float|null $label,
        ?string $encoding = null
    ): ?string {
        if (null === ($label = static::text($label, $encoding))) {
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
     * @return ($id is null ? null : string)
     */
    public static function id(
        string|Stringable|int|float|null $id,
        ?string $encoding = null
    ): ?string {
        if (null === ($id = static::text($id, $encoding))) {
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
     * @return ($id is null ? null : string)
     */
    public static function camel(
        string|Stringable|int|float|null $id,
        ?string $encoding = null
    ): ?string {
        if (null === ($id = static::text($id, $encoding))) {
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
     * @return ($constant is null ? null : string)
     */
    public static function constant(
        string|Stringable|int|float|null $constant,
        ?string $encoding = null
    ): ?string {
        if (null === ($constant = static::text($constant, $encoding))) {
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
     * @return ($slug is null ? null : string)
     */
    public static function slug(
        string|Stringable|int|float|null $slug,
        string $allowedChars = '',
        ?string $encoding = null
    ): ?string {
        if (null === ($slug = static::text($slug, $encoding))) {
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
     * @return ($slug is null ? null : string)
     */
    public static function pathSlug(
        string|Stringable|int|float|null $slug,
        string $allowedChars = '',
        ?string $encoding = null
    ): ?string {
        if (
            $slug === null ||
            !strlen($slug = (string)$slug)
        ) {
            return null;
        }

        $parts = explode('/', $slug);

        foreach ($parts as $i => $part) {
            $part = static::slug($part, $allowedChars, $encoding);

            if (!strlen($part)) {
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
     * @return ($slug is null ? null : string)
     */
    public static function actionSlug(
        string|Stringable|int|float|null $slug,
        ?string $encoding = null
    ): ?string {
        if (null === ($slug = static::text($slug, $encoding))) {
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
     * @return ($fileName is null ? null : string)
     */
    public static function fileName(
        string|Stringable|int|float|null $fileName,
        bool $allowSpaces = false,
        ?string $encoding = null
    ): ?string {
        if (null === ($fileName = static::text($fileName, $encoding))) {
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
     * @return ($text is null ? null : string)
     */
    public static function shorten(
        string|Stringable|int|float|null $text,
        int $length,
        bool $rtl = false,
        ?string $encoding = null
    ): ?string {
        if (null === ($text = static::text($text, $encoding))) {
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
                    ->prepend('…'); // @ignore-non-ascii
            } else {
                $text = $text->slice(0, $length - 1)
                    ->trimRight('., ')
                    ->append('…'); // @ignore-non-ascii
            }
        }

        return $text->__toString();
    }

    /**
     * @return ($number is null ? null : string)
     */
    public static function numericToAlpha(
        ?int $number,
        ?string $encoding = null
    ): ?string {
        if ($number === null) {
            return null;
        }

        return Text::numericToAlpha($number)
            ->__toString();
    }

    /**
     * @return ($text is null ? null : int)
     */
    public static function alphaToNumeric(
        string|Stringable|int|float|null $text,
        ?string $encoding = null
    ): ?int {
        if (null === ($text = static::text($text, $encoding))) {
            return null;
        }

        return $text->alphaToNumeric();
    }



    public static function toBoolean(
        string|Stringable|int|float|null $text,
        ?string $encoding = null
    ): bool {
        if (is_int($text) || is_float($text)) {
            return (bool)$text;
        }

        if (null === ($text = static::text($text, $encoding))) {
            return false;
        }

        return $text->toBoolean();
    }

    public static function compare(
        string|Stringable|int|float|null $string1,
        string|Stringable|int|float|null $string2
    ): bool {
        $string1 = static::text($string1);
        $string2 = static::text($string2);

        if (
            $string1 === null ||
            $string2 === null
        ) {
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

    public static function isAlpha(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = static::text($text))) {
            return false;
        }

        return $text->isAlpha();
    }

    public static function isAlphaNumeric(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = static::text($text))) {
            return false;
        }

        return $text->isAlphaNumeric();
    }

    public static function isDigit(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = static::text($text))) {
            return false;
        }

        return $text->isDigit();
    }

    public static function isWhitespace(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = static::text($text))) {
            return false;
        }

        return $text->isWhitespace();
    }

    public static function isBlank(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = static::text($text))) {
            return true;
        }

        return $text->isBlank();
    }

    public static function isHex(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = static::text($text))) {
            return false;
        }

        return $text->isHex();
    }

    public static function countWords(
        string|Stringable|int|float|null $text
    ): int {
        if (null === ($text = static::text($text))) {
            return 0;
        }

        return $text->countWords();
    }
}
