<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum;

class Context
{
    /**
     * Create Text buffer
     */
    public function text(?string $text, ?string $encoding = null): ?Text
    {
        if ($text === null) {
            return null;
        }

        return new Text($text, $encoding);
    }



    /**
     * Normalize words, convert words to upper
     */
    public function name(?string $name, ?string $encoding = null): ?Text
    {
        if (null === ($name = $this->text($name, $encoding))) {
            return null;
        }

        return $name
            ->replace(['-', '_'], ' ')
            ->regexReplace('([^ ])([A-Z/])', '\\1 \\2')
            ->regexReplace('([/])([^ ])', '\\1 \\2')
            ->toTitleCase();
    }

    /**
     * Get first name from full name
     */
    public function firstName(?string $fullName, ?string $encoding = null): ?Text
    {
        if (!strlen((string)$fullName)) {
            return null;
        }

        $parts = explode(' ', (string)$fullName);
        $output = (string)array_shift($parts);

        if (in_array(strtolower($output), ['mr', 'ms', 'mrs', 'miss', 'dr'])) {
            if (isset($parts[1])) {
                $output = (string)array_shift($parts);
            } else {
                $output = (string)$fullName;
            }
        }

        if (strlen($output) < 3) {
            $output .= ' ' . array_pop($parts);
        }

        return (new Text($output, $encoding))
            ->firstToUpperCase();
    }

    /**
     * Initialise name
     */
    public function initials(?string $name, bool $extendShort = true, ?string $encoding = null): ?Text
    {
        if (null === ($name = $this->text($name, $encoding))) {
            return null;
        }

        $output = $name
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

        return $output;
    }

    /**
     * Get initials and surname
     */
    public function initialsAndSurname(?string $name, ?string $encoding = null): ?Text
    {
        if (!strlen((string)$name)) {
            return null;
        }

        $parts = explode(' ', (string)$name);
        $surname = array_pop($parts);

        if (in_array(strtolower($parts[0] ?? ''), ['mr', 'ms', 'mrs', 'miss', 'dr'])) {
            array_shift($parts);
        }

        if (null === ($output = $this->initials(implode(' ', $parts), false))) {
            return null;
        }

        return $output
            ->append(' ')
            ->append(
                (new Text($surname, $encoding))
                    ->firstToUpperCase()
            );
    }

    /**
     * Shorten middle names
     */
    public function initialMiddleNames(?string $name, ?string $encoding = null): ?Text
    {
        if (!strlen((string)$name)) {
            return null;
        }

        $parts = explode(' ', (string)$name);
        $surname = array_pop($parts);

        if (in_array(strtolower($parts[0] ?? ''), ['mr', 'ms', 'mrs', 'miss', 'dr'])) {
            array_shift($parts);
        }

        $output = (new Text((string)array_shift($parts), $encoding))
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

        return $output->append(
            (new Text($surname, $encoding))
                ->firstToUpperCase()
        );
    }

    /**
     * Strip vowels from text
     */
    public function consonants(?string $text, ?string $encoding = null): ?Text
    {
        if (null === ($text = $this->text($text, $encoding))) {
            return null;
        }

        return $text
            ->toAscii()
            ->regexReplace('[aeiou]+', '');
    }

    /**
     * Uppercase first, to ASCII, strip some chars
     */
    public function label(?string $label, ?string $encoding = null): ?Text
    {
        if (null === ($label = $this->text($label, $encoding))) {
            return null;
        }

        return $label
            ->regexReplace('[-_./:]', ' ')
            ->regexReplace('([a-z])([A-Z])', '\\1 \\2')
            ->toLowerCase()
            ->firstToUpperCase();
    }

    /**
     * Convert to Id
     */
    public function id(?string $id, ?string $encoding = null): ?Text
    {
        if (null === ($id = $this->text($id, $encoding))) {
            return null;
        }

        return $id
            ->toAscii()
            ->regexReplace('([^ ])([A-Z])', '\\1 \\2')
            ->replace(['-', '.', '+'], ' ')
            ->regexReplace('[^a-zA-Z0-9_ ]', '')
            ->toTitleCase()
            ->replace(' ', '');
    }

    /**
     * Convert to camelCase
     */
    public function camel(?string $id, ?string $encoding = null): ?Text
    {
        if (null === ($id = $this->id($id, $encoding))) {
            return null;
        }

        return $id->firstToLowerCase();
    }

    /**
     * Format as PHP_CONSTANT
     */
    public function constant(?string $constant, ?string $encoding = null): ?Text
    {
        if (null === ($constant = $this->text($constant, $encoding))) {
            return null;
        }

        return $constant
            ->toAscii()
            ->regexReplace('[^a-zA-Z0-9]', ' ')
            ->regexReplace('([^ ])([A-Z])', '\\1 \\2')
            ->regexReplace('[^a-zA-Z0-9_ ]', '')
            ->trim()
            ->replace(' ', '_')
            ->replace('__', '_')
            ->toUpperCase();
    }

    /**
     * Convert to slug
     */
    public function slug(?string $slug, string $allowedChars = '', ?string $encoding = null): ?Text
    {
        if (null === ($slug = $this->text($slug, $encoding))) {
            return null;
        }

        return $slug
            ->toAscii()
            ->regexReplace('([a-z][a-z])([A-Z][a-z])', '\\1 \\2')
            ->toLowerCase()
            ->regexReplace('[\s_/]', '-')
            ->regexReplace('[^a-z0-9_\-' . preg_quote($allowedChars) . ']', '')
            ->regexReplace('-+', '-')
            ->trim(' -');
    }

    /**
     * Convert to path format slug
     */
    public function pathSlug(?string $slug, string $allowedChars = '', ?string $encoding = null): ?Text
    {
        if (
            $slug === null ||
            !strlen($slug)
        ) {
            return null;
        }

        $parts = explode('/', $slug);

        foreach ($parts as $i => $part) {
            $part = $this->slug($part, $allowedChars, $encoding);

            if (
                $part === null ||
                $part->isEmpty()
            ) {
                unset($parts[$i]);
                continue;
            }

            $parts[$i] = (string)$part;
        }

        return $this->text(implode('/', $parts), $encoding);
    }

    /**
     * Convert to URL action slug
     */
    public function actionSlug(?string $slug, ?string $encoding = null): ?Text
    {
        if (null === ($slug = $this->text($slug, $encoding))) {
            return null;
        }

        return $slug
            ->toAscii()
            ->regexReplace('([^ ])([A-Z])', '\\1-\\2')
            ->replace(' ', '-')
            ->toLowerCase()
            ->regexReplace('-+', '-')
            ->trim(' -');
    }

    /**
     * Remove non-filesystem compatible chars
     */
    public function filename(?string $filename, bool $allowSpaces = false, ?string $encoding = null): ?Text
    {
        if (null === ($filename = $this->text($filename, $encoding))) {
            return null;
        }

        $filename = $filename
            ->toAscii()
            ->replace('/', '_')
            ->regexReplace('[\/\\?%*:|"<>]', '');

        if (!$allowSpaces) {
            $filename = $filename->replace(' ', '-');
        }

        return $filename;
    }

    /**
     * Cap length of string, add ellipsis if needed
     */
    public function shorten(?string $text, int $length, bool $rtl = false, ?string $encoding = null): ?Text
    {
        if (null === ($text = $this->text($text, $encoding))) {
            return null;
        }

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

        return $text;
    }

    /**
     * Wrapper around Text::numericToAlpha
     */
    public function numericToAlpha(?int $number, ?string $encoding = null): ?Text
    {
        if ($number === null) {
            return null;
        }

        return Text::numericToAlpha($number);
    }

    /**
     * Wrapper around alphaToNumeric
     */
    public function alphaToNumeric(?string $text, ?string $encoding = null): ?int
    {
        if (null === ($text = $this->text($text, $encoding))) {
            return null;
        }

        return $text->alphaToNumeric();
    }

    /**
     * String to boolean
     */
    public function toBoolean(string $text, ?string $encoding = null): bool
    {
        if (null === ($text = $this->text($text, $encoding))) {
            return false;
        }

        return $text->toBoolean();
    }
}
