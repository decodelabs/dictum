<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum;

use DecodeLabs\Dictum;
use DecodeLabs\Dictum\Plugins\Number as NumberPlugin;
use DecodeLabs\Dictum\Plugins\Time as TimePlugin;
use DecodeLabs\Veneer;
use DecodeLabs\Veneer\Plugin;

use Stringable;

class Context
{
    #[Plugin(lazy: true)]
    public NumberPlugin $number;

    #[Plugin(lazy: true)]
    public TimePlugin $time;



    /**
     * Create Text buffer
     *
     * @phpstan-return ($text is null ? null : Text)
     */
    public function text(
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
     * Normalize words, convert words to upper
     *
     * @phpstan-return ($name is null ? null : string)
     */
    public function name(
        string|Stringable|int|float|null $name,
        ?string $encoding = null
    ): ?string {
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
     * @phpstan-return ($fullName is null ? null : string)
     */
    public function firstName(
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
     * Initialise name
     *
     * @phpstan-return ($name is null ? null : string)
     */
    public function initials(
        string|Stringable|int|float|null $name,
        bool $extendShort = true,
        ?string $encoding = null
    ): ?string {
        if (null === ($name = $this->text($name, $encoding))) {
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
     * Get initials and surname
     *
     * @phpstan-return ($name is null ? null : string)
     */
    public function initialsAndSurname(
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

        $output = $this->initials(implode(' ', $parts), false);

        return (new Text($surname, $encoding))
            ->toUtf8()
            ->firstToUpperCase()
            ->prepend($output . ' ')
            ->__toString();
    }

    /**
     * Shorten middle names
     *
     * @phpstan-return ($name is null ? null : string)
     */
    public function initialMiddleNames(
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
     * @phpstan-return ($text is null ? null : string)
     */
    public function consonants(
        string|Stringable|int|float|null $text,
        ?string $encoding = null
    ): ?string {
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
     * @phpstan-return ($label is null ? null : string)
     */
    public function label(
        string|Stringable|int|float|null $label,
        ?string $encoding = null
    ): ?string {
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
     * @phpstan-return ($id is null ? null : string)
     */
    public function id(
        string|Stringable|int|float|null $id,
        ?string $encoding = null
    ): ?string {
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
     * @phpstan-return ($id is null ? null : string)
     */
    public function camel(
        string|Stringable|int|float|null $id,
        ?string $encoding = null
    ): ?string {
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
     * @phpstan-return ($constant is null ? null : string)
     */
    public function constant(
        string|Stringable|int|float|null $constant,
        ?string $encoding = null
    ): ?string {
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
     * @phpstan-return ($slug is null ? null : string)
     */
    public function slug(
        string|Stringable|int|float|null $slug,
        string $allowedChars = '',
        ?string $encoding = null
    ): ?string {
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
     * @phpstan-return ($slug is null ? null : string)
     */
    public function pathSlug(
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
            $part = $this->slug($part, $allowedChars, $encoding);

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
     * Convert to URL action slug
     *
     * @phpstan-return ($slug is null ? null : string)
     */
    public function actionSlug(
        string|Stringable|int|float|null $slug,
        ?string $encoding = null
    ): ?string {
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
     * @phpstan-return ($fileName is null ? null : string)
     */
    public function fileName(
        string|Stringable|int|float|null $fileName,
        bool $allowSpaces = false,
        ?string $encoding = null
    ): ?string {
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
     * @phpstan-return ($text is null ? null : string)
     */
    public function shorten(
        string|Stringable|int|float|null $text,
        int $length,
        bool $rtl = false,
        ?string $encoding = null
    ): ?string {
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
     * Wrapper around Text::numericToAlpha
     *
     * @phpstan-return ($number is null ? null : string)
     */
    public function numericToAlpha(
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
     * Wrapper around alphaToNumeric
     *
     * @phpstan-return ($text is null ? null : int)
     */
    public function alphaToNumeric(
        string|Stringable|int|float|null $text,
        ?string $encoding = null
    ): ?int {
        if (null === ($text = $this->text($text, $encoding))) {
            return null;
        }

        return $text->alphaToNumeric();
    }



    /**
     * String to boolean
     */
    public function toBoolean(
        string|Stringable|int|float|null $text,
        ?string $encoding = null
    ): bool {
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
     */
    public function compare(
        string|Stringable|int|float|null $string1,
        string|Stringable|int|float|null $string2
    ): bool {
        $string1 = $this->text($string1);
        $string2 = $this->text($string2);

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


    /**
     * Only contains alpha characters
     */
    public function isAlpha(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = $this->text($text))) {
            return false;
        }

        return $text->isAlpha();
    }

    /**
     * Only contains alpha numeric characters
     */
    public function isAlphaNumeric(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = $this->text($text))) {
            return false;
        }

        return $text->isAlphaNumeric();
    }

    /**
     * Only contains digits
     */
    public function isDigit(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = $this->text($text))) {
            return false;
        }

        return $text->isDigit();
    }


    /**
     * Only contains whitespace
     */
    public function isWhitespace(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = $this->text($text))) {
            return false;
        }

        return $text->isWhitespace();
    }

    /**
     * Only contains whitespace or empty
     */
    public function isBlank(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = $this->text($text))) {
            return true;
        }

        return $text->isBlank();
    }

    /**
     * Only contains hex
     */
    public function isHex(
        string|Stringable|int|float|null $text
    ): bool {
        if (null === ($text = $this->text($text))) {
            return false;
        }

        return $text->isHex();
    }


    /**
     * Count number of whole words
     */
    public function countWords(
        string|Stringable|int|float|null $text
    ): int {
        if (null === ($text = $this->text($text))) {
            return 0;
        }

        return $text->countWords();
    }
}


// Register the Veneer facade
Veneer\Manager::getGlobalManager()->register(
    Context::class,
    Dictum::class
);
