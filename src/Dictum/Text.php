<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum;

use ArrayAccess;
use Countable;

use DecodeLabs\Exceptional;
use DecodeLabs\Fluidity\ThenTrait;
use DecodeLabs\Glitch\Dumpable;

use Iterator;
use IteratorAggregate;
use Stringable;

/**
 * @implements IteratorAggregate<Text>
 * @implements ArrayAccess<int, Text>
 */
class Text implements
    ArrayAccess,
    Countable,
    IteratorAggregate,
    Stringable,
    Dumpable
{
    use ThenTrait;

    /**
     * @var string
     */
    protected $encoding;

    /**
     * @var string
     */
    protected $text;


    /**
     * Create a new instance
     */
    public static function create(?string $text = '', ?string $encoding = null): Text
    {
        return new static($text, $encoding);
    }


    /**
     * Create with initial value and encoding, defaults to mb_internal_encoding
     */
    final public function __construct(?string $text = '', string $encoding = null)
    {
        $this->text = (string)$text;
        $this->encoding = $encoding ?: mb_internal_encoding();
    }


    /**
     * Get internal value
     */
    public function __toString(): string
    {
        return $this->text;
    }


    /**
     * Is the string empty
     */
    public function isEmpty(): bool
    {
        return $this->text === '';
    }


    /**
     * Get length
     */
    public function count(): int
    {
        return (int)mb_strlen($this->text, $this->encoding);
    }

    /**
     * Get length alias
     */
    public function getLength(): int
    {
        return (int)mb_strlen($this->text, $this->encoding);
    }

    /**
     * Get active encoding
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * Convert encoding
     */
    public function convertEncoding(string $encoding): Text
    {
        return new static(
            mb_convert_encoding($this->text, $encoding, $this->encoding),
            $encoding
        );
    }



    /**
     * Get a single char at index
     */
    public function getChar(int $index): Text
    {
        return $this->slice($index, 1);
    }

    /**
     * Replace a single char
     */
    public function replaceChar(int $index, string $char): Text
    {
        $output = '';

        if ($index !== 0) {
            $output .= mb_substr($this->text, 0, $index, $this->encoding);
        }

        $output .= $char;

        if ($index !== -1) {
            $output .= mb_substr($this->text, $index + 1, null, $this->encoding);
        }

        return new static($output, $this->encoding);
    }

    /**
     * Insert a string into the text
     */
    public function insert(int $index, string $string): Text
    {
        $output = '';

        if ($index !== 0) {
            $output .= mb_substr($this->text, 0, $index, $this->encoding);
        }

        $output .= $string;
        $output .= mb_substr($this->text, $index, null, $this->encoding);

        return new static($output, $this->encoding);
    }

    /**
     * Remove a single char
     */
    public function removeChar(int $index): Text
    {
        $output = '';

        if ($index !== 0) {
            $output .= mb_substr($this->text, 0, $index, $this->encoding);
        }

        if ($index !== -1) {
            $output .= mb_substr($this->text, $index + 1, null, $this->encoding);
        }

        return new static($output, $this->encoding);
    }

    /**
     * Check char index
     */
    public function hasCharAt(int $index): bool
    {
        return mb_substr($this->text, $index, 1, $this->encoding) !== '';
    }


    /**
     * Get char at index
     *
     * @param int $index
     */
    public function offsetGet($index): Text
    {
        return $this->slice($index, 1);
    }

    /**
     * Not supported for immutable
     *
     * @param int $index
     * @param Text $value
     */
    public function offsetSet($index, $value): void
    {
        throw Exceptional::Implementation(
            'Immutable flex\\Text does not support array-access setting'
        );
    }

    /**
     * Check char
     *
     * @param int $index
     */
    public function offsetExists($index): bool
    {
        return $this->hasCharAt($index);
    }

    /**
     * Remove character
     *
     * @param int $index
     */
    public function offsetUnset($index): void
    {
        throw Exceptional::Implementation(
            'Immutable flex\\Text does not support array-access unset'
        );
    }



    /**
     * Get first index of needle
     */
    public function getIndexOf(string $needle, int $offset = 0): ?int
    {
        if (false === ($output = mb_strpos($this->text, $needle, $offset, $this->encoding))) {
            $output = null;
        }

        return $output;
    }

    /**
     * Get first index of needle
     */
    public function getIndexOfCi(string $needle, int $offset = 0): ?int
    {
        if (false === ($output = mb_stripos($this->text, $needle, $offset, $this->encoding))) {
            $output = null;
        }

        return $output;
    }

    /**
     * Get last index of needle
     */
    public function getLastIndexOf(string $needle, int $offset = 0): ?int
    {
        if (false === ($output = mb_strrpos($this->text, $needle, $offset, $this->encoding))) {
            $output = null;
        }

        return $output;
    }

    /**
     * Get last index of needle
     */
    public function getLastIndexOfCi(string $needle, int $offset = 0): ?int
    {
        if (false === ($output = mb_strripos($this->text, $needle, $offset, $this->encoding))) {
            $output = null;
        }

        return $output;
    }




    /**
     * Is $needle in text?
     */
    public function contains(string ...$needles): bool
    {
        foreach ($needles as $needle) {
            if (false !== mb_strpos($this->text, $needle, 0, $this->encoding)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is $needle in text? Case insensitive
     */
    public function containsCi(string ...$needles): bool
    {
        foreach ($needles as $needle) {
            if (false !== mb_stripos($this->text, $needle, 0, $this->encoding)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Are all $needles in text?
     */
    public function containsAll(string ...$needles): bool
    {
        foreach ($needles as $needle) {
            if (false === mb_strpos($this->text, $needle, 0, $this->encoding)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Are all $needles in text? Case insensitive
     */
    public function containsAllCi(string ...$needles): bool
    {
        foreach ($needles as $needle) {
            if (false === mb_stripos($this->text, $needle, 0, $this->encoding)) {
                return false;
            }
        }

        return true;
    }



    /**
     * Does the end of the text match $start?
     */
    public function beginsWith(string ...$starts): bool
    {
        foreach ($starts as $start) {
            if (mb_substr(
                $this->text,
                0,
                (int)mb_strlen($start, $this->encoding),
                $this->encoding
            ) === $start) {
                return true;
            }
        }

        return false;
    }

    /**
     * Does the end of the text match $start?
     */
    public function beginsWithCi(string ...$starts): bool
    {
        foreach ($starts as $start) {
            if (mb_strtolower(
                mb_substr(
                    $this->text,
                    0,
                    (int)mb_strlen($start, $this->encoding),
                    $this->encoding
                ),
                $this->encoding
            ) === mb_strtolower($start, $this->encoding)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Does the end of the text match $end?
     */
    public function endsWith(string ...$ends): bool
    {
        foreach ($ends as $end) {
            if (mb_substr(
                $this->text,
                0 - mb_strlen($end, $this->encoding),
                null,
                $this->encoding
            ) === $end) {
                return true;
            }
        }

        return false;
    }

    /**
     * Does the end of the text match $end?
     */
    public function endsWithCi(string ...$ends): bool
    {
        foreach ($ends as $end) {
            if (mb_strtolower(
                mb_substr(
                    $this->text,
                    0 - mb_strlen($end, $this->encoding),
                    null,
                    $this->encoding
                ),
                $this->encoding
            ) === mb_strtolower($end, $this->encoding)) {
                return true;
            }
        }

        return false;
    }



    /**
     * Ensure sequence is at least $size long
     */
    public function padLeft(int $size, string $value = ' '): Text
    {
        return new static(
            str_pad($this->text, abs($size), $value, STR_PAD_LEFT),
            $this->encoding
        );
    }

    /**
     * Ensure sequence is at least $size long
     */
    public function padRight(int $size, string $value = ' '): Text
    {
        return new static(
            str_pad($this->text, abs($size), $value, STR_PAD_RIGHT),
            $this->encoding
        );
    }

    /**
     * Ensure sequence is at least $size long
     */
    public function padBoth(int $size, string $value = ' '): Text
    {
        $length = $this->getLength();
        $output = $this->text;

        if (($size = abs($size)) < $length) {
            return new static($output, $this->encoding);
        }

        $padSize = ($size - $length) / 2;
        $leftSize = $length + floor($padSize);
        $rightSize = $size;

        $output = str_pad($output, (int)$leftSize, $value, STR_PAD_LEFT);
        $output = str_pad($output, (int)$rightSize, $value, STR_PAD_RIGHT);

        return new static($output, $this->encoding);
    }




    /**
     * Count instances of $string
     */
    public function countInstances(string $string): int
    {
        return mb_substr_count($this->text, $string, $this->encoding);
    }

    /**
     * Count case insensitive instances of $string
     */
    public function countInstancesCi(string $string): int
    {
        return mb_substr_count(
            mb_strtolower($this->text, $this->encoding),
            mb_strtolower($this->text, $this->encoding)
        );
    }


    /**
     * Count number of whole words in text
     */
    public function countWords(): int
    {
        return $this
            ->trim()
            ->regexReplace('[^\w\s]+', '')
            ->regexReplace('^([^\s])', ' $1')
            ->regexReplace('[\s]+', ' ')
            ->countInstances(' ');
    }



    /**
     * Get a range of characters
     */
    public function slice(int $start, int $length = null): Text
    {
        $output = mb_substr($this->text, $start, $length, $this->encoding);
        return new static($output, $this->encoding);
    }

    /**
     * Get random slice
     */
    public function sliceRandom(int $length): Text
    {
        $total = $this->getLength();

        if ($length >= $total) {
            return new static($this->text, $this->encoding);
        }

        $start = rand(0, $total - $length);
        return $this->slice($start, $length);
    }

    /**
     * Get first instance of substring between $start and $end
     */
    public function sliceDelimited(string $start, string $end, int $offset = 0): Text
    {
        if (null === ($startIndex = $this->getIndexOf($start, $offset))) {
            return new static('', $this->encoding);
        }

        $sliceIndex = $startIndex + mb_strlen($start, $this->encoding);

        if (null === ($endIndex = $this->getIndexOf($end, $sliceIndex))) {
            return new static('', $this->encoding);
        }

        return $this->slice($sliceIndex, $endIndex - $sliceIndex);
    }




    /**
     * Add a string to the end
     *
     * @param Text|string|Stringable|null $text
     */
    public function append($text, ?string $encoding = null): Text
    {
        $text = (string)$this->normalizeEncoding($text, $encoding);
        return new static($this->text . $text, $this->encoding);
    }

    /**
     * Add a string to the start
     *
     * @param Text|string|Stringable|null $text
     */
    public function prepend($text, ?string $encoding = null): Text
    {
        $text = (string)$this->normalizeEncoding($text, $encoding);
        return new static($text . $this->text, $this->encoding);
    }

    /**
     * Add string to start and end
     *
     * @param Text|string|Stringable|null $text
     */
    public function surroundWith($text, ?string $encoding = null): Text
    {
        $text = (string)$this->normalizeEncoding($text, $encoding);
        return new static($text . $this->text . $text, $this->encoding);
    }



    /**
     * Limit length to $length - length of $cap, adds cap to end
     */
    public function truncate(int $length, string $cap = null): Text
    {
        if ($length > $this->getLength()) {
            return $this;
        }

        $capLength = $cap === null ? 0 : mb_strlen($cap);
        $length -= $capLength;

        $output = mb_substr($this->text, 0, $length, $this->encoding);
        $output .= $cap;

        return new static($output, $this->encoding);
    }



    /**
     * Collapse all whitespace
     */
    public function collapseWhitespace(): Text
    {
        return $this->regexReplace('[[:space:]]+', ' ')->trim();
    }

    /**
     * Remove all whitespace
     */
    public function stripWhitespace(): Text
    {
        return $this->regexReplace('[[:space:]]+', '');
    }


    /**
     * Regex match
     */
    public function matches(string $pattern, string $options = 'msr'): bool
    {
        $encoding = mb_regex_encoding();
        $oldOptions = mb_regex_set_options($options);
        mb_regex_encoding($this->encoding);

        $output = (bool)mb_ereg($pattern, $this->text);

        mb_regex_set_options($oldOptions);
        mb_regex_encoding($encoding);
        return $output;
    }

    /**
     * Regex match
     *
     * @return array<string>|null
     */
    public function match(string $pattern, string $options = 'msr'): ?array
    {
        $encoding = mb_regex_encoding();
        $oldOptions = mb_regex_set_options($options);
        mb_regex_encoding($this->encoding);

        if (false === mb_ereg($pattern, $this->text, $output)) {
            $output = null;
        }

        mb_regex_set_options($oldOptions);
        mb_regex_encoding($encoding);
        return $output;
    }

    /**
     * Replace a chunk
     *
     * @param array<string>|string $search
     * @param array<string>|string $replace
     */
    public function replace($search, $replace, int &$count = 0): Text
    {
        return new static(
            str_replace($search, $replace, $this->text, $count),
            $this->encoding
        );
    }

    /**
     * Replace all occurances of $pattern in text
     */
    public function regexReplace(string $pattern, string $replacement, string $options = 'msr'): Text
    {
        $encoding = mb_regex_encoding();
        $oldOptions = mb_regex_set_options($options);
        mb_regex_encoding($this->encoding);

        if (false === ($output = mb_ereg_replace($pattern, $replacement, $this->text, $options))) {
            throw Exceptional::Runtime('Unable to complete mb regex');
        }

        mb_regex_encoding($encoding);
        mb_regex_set_options($oldOptions);
        return new static($output, $this->encoding);
    }


    /**
     * Split by $string
     *
     * @return array<Text>
     */
    public function split(string $delimiter, int $limit = PHP_INT_MAX): array
    {
        $output = (array)explode($delimiter, $this->text, $limit);

        return array_map(function ($part) {
            return new static($part, $this->encoding);
        }, $output);
    }

    /**
     * Split by $pattern
     *
     * @return array<Text>
     */
    public function regexSplit(string $pattern, int $limit = -1): array
    {
        if (false === ($parts = mb_split($pattern, $this->text, $limit))) {
            throw Exceptional::Runtime('Unable to split text with: ' . $pattern);
        }

        return array_map(function ($part) {
            return new static($part, $this->encoding);
        }, $parts);
    }


    /**
     * Replace whitespace with $delimiter, all lowercase
     */
    public function delimit(string $delimiter): Text
    {
        $encoding = mb_regex_encoding();
        mb_regex_encoding($this->encoding);

        $output = (string)mb_ereg_replace('^[[:space:]]+|[[:space:]]+\$', '', $this->text);
        $output = (string)mb_ereg_replace('\B([A-Z])', '-\1', $output);
        $output = (string)mb_strtolower($output, $this->encoding);
        $output = (string)mb_ereg_replace('[-_]+', ' ', $output);
        $output = (string)mb_ereg_replace('\p{P}', '', $output);
        $output = (string)mb_ereg_replace('[\s]+', $delimiter, $output);

        mb_regex_encoding($encoding);
        return new static($output, $this->encoding);
    }


    /**
     * Trim left and right
     */
    public function trim(string $chars = null): Text
    {
        $chars = $chars ? preg_quote($chars) : '[:space:]';
        return $this->regexReplace('^[' . $chars . ']+|[' . $chars . ']+$', '');
    }

    /**
     * Trim left
     */
    public function trimLeft(string $chars = null): Text
    {
        $chars = $chars ? preg_quote($chars) : '[:space:]';
        return $this->regexReplace('^[' . $chars . ']+', '');
    }

    /**
     * Trim right
     */
    public function trimRight(string $chars = null): Text
    {
        $chars = $chars ? preg_quote($chars) : '[:space:]';
        return $this->regexReplace('[' . $chars . ']+$', '');
    }



    /**
     * Only contains alpha characters
     */
    public function isAlpha(): bool
    {
        return $this->matches('^[[:alpha:]]*$');
    }

    /**
     * Only contains alpha numeric characters
     */
    public function isAlphaNumeric(): bool
    {
        return $this->matches('^[[:alnum:]]*$');
    }

    /**
     * Only contains whitespace
     */
    public function isBlank(): bool
    {
        return $this->matches('^[[:space:]]*$');
    }

    /**
     * Only contains hex
     */
    public function isHex(): bool
    {
        return $this->matches('^[[:xdigit:]]*$');
    }

    /**
     * Is valid json data
     */
    public function isJson(): bool
    {
        if (!strlen($this->text)) {
            return false;
        }

        json_decode($this->text);
        return json_last_error() === JSON_ERROR_NONE;
    }


    /**
     * Is all lowercase
     */
    public function isLowerCase(): bool
    {
        return $this->matches('^[[:lower:]]*$');
    }

    /**
     * Has any lowercase characters
     */
    public function hasLowerCase(): bool
    {
        return $this->matches('[[:lower:]]');
    }

    /**
     * Convert to lowercase
     */
    public function toLowerCase(): Text
    {
        return new static(
            mb_strtolower($this->text, $this->encoding),
            $this->encoding
        );
    }

    /**
     * Convert first character to lowercase
     */
    public function firstToLowerCase(): Text
    {
        return new static(
            mb_strtolower(mb_substr($this->text, 0, 1, $this->encoding)) .
                mb_substr($this->text, 1, (int)mb_strlen($this->text, $this->encoding), $this->encoding),
            $this->encoding
        );
    }


    /**
     * Is all uppercase
     */
    public function isUpperCase(): bool
    {
        return $this->matches('^[[:upper:]]*$');
    }

    /**
     * Has any uppercase characters
     */
    public function hasUpperCase(): bool
    {
        return $this->matches('[[:upper:]]');
    }

    /**
     * Convert to uppercase
     */
    public function toUpperCase(): Text
    {
        return new static(
            mb_strtoupper($this->text, $this->encoding),
            $this->encoding
        );
    }

    /**
     * Convert first character to uppercase
     */
    public function firstToUpperCase(): Text
    {
        return new static(
            mb_strtoupper(mb_substr($this->text, 0, 1, $this->encoding)) .
                mb_substr($this->text, 1, (int)mb_strlen($this->text, $this->encoding), $this->encoding),
            $this->encoding
        );
    }

    /**
     * Convert words first character to uppercase
     */
    public function toTitleCase(): Text
    {
        return new static(
            mb_convert_case($this->text, MB_CASE_TITLE, $this->encoding),
            $this->encoding
        );
    }



    /**
     * Swap case of all characters
     */
    public function swapCase(): Text
    {
        $output = '';

        for ($i = 0, $length = $this->getLength(); $i < $length; $i++) {
            $char = mb_substr($this->text, $i, 1, $this->encoding);

            if ($char === mb_strtoupper($char, $this->encoding)) {
                $char = mb_strtolower($char, $this->encoding);
            } else {
                $char = mb_strtoupper($char, $this->encoding);
            }

            $output .= $char;
        }

        return new static($output, $this->encoding);
    }




    /**
     * Convert all non-ascii chars to ascii equivalent
     *
     * Note: Adapted from Stringy\Stringy.
     *
     * @see https://github.com/danielstjules/Stringy/blob/3.1.0/LICENSE.txt
     */
    public function toAscii(string $language = 'en', bool $removeUnsupported = true): Text
    {
        $output = $this->text;

        if (isset(static::LANGUAGE_ASCII_CHARS[$language])) {
            $output = str_replace(
                static::LANGUAGE_ASCII_CHARS[$language][0],
                static::LANGUAGE_ASCII_CHARS[$language][1],
                $output
            );
        }

        foreach (static::ASCII_CHARS as $key => $value) {
            $output = str_replace($value, (string)$key, $output);
        }

        if ($removeUnsupported) {
            $output = preg_replace('/[^\x20-\x7E]/u', '', $output);
        }

        return new static($output, $this->encoding);
    }

    /**
     * Convert text value to boolean
     */
    public function toBoolean(?bool $default = null): bool
    {
        switch ($text = strtolower(trim($this->text))) {
            case 'false':
            case '0':
            case 'no':
            case 'n':
            case 'off':
            case 'disabled':
                return false;

            case 'true':
            case '1':
            case 'yes':
            case 'y':
            case 'on':
            case 'enabled':
                return true;

            default:
                if ($default !== null) {
                    return $default;
                }
        }

        if (is_numeric($text)) {
            return (int)$text > 0;
        }

        return (bool)$this->text;
    }

    /**
     * Convert tabs to spaces
     */
    public function tabsToSpaces(int $tabLength = 4): Text
    {
        return new static(
            str_replace("\t", str_repeat(' ', $tabLength), $this->text),
            $this->encoding
        );
    }

    /**
     * Convert spaces to tabs
     */
    public function spacesToTabs(int $tabLength): Text
    {
        return new static(
            str_replace(str_repeat(' ', $tabLength), "\t", $this->text),
            $this->encoding
        );
    }


    /**
     * Convert numerics to alpha characters
     */
    public static function numericToAlpha(int $number): Text
    {
        static $alphabet = 'abcdefghijklmnopqrstuvwxyz';
        $output = '';

        while ($number >= 0) {
            $key = $number % 26;
            $output = $alphabet[$key] . $output;
            $number = (($number - $key) / 26) - 1;
        }

        return new static($output);
    }

    /**
     * Convert alpha characters to number
     */
    public function alphaToNumeric(): ?int
    {
        $output = -1;

        for ($i = 0; $i < mb_strlen($this->text); $i++) {
            $char = mb_substr($this->text, $i, 1, $this->encoding);

            if (empty($num = base_convert($char, 36, 10))) {
                continue;
            }

            $output = (($output + 1) * 26) + (int)$num - 10;
        }

        if ($output === -1) {
            return null;
        }

        if (!is_int($output)) {
            throw Exceptional::Range(
                'Alpha to numeric string overflowed int max'
            );
        }

        return $output;
    }




    /**
     * Encode for HTML
     */
    public function htmlEncode(int $flags = ENT_COMPAT): Text
    {
        return new static(
            htmlentities($this->text, $flags, $this->encoding),
            $this->encoding
        );
    }

    /**
     * Decode from HTML
     */
    public function htmlDecode(int $flags = ENT_COMPAT): Text
    {
        return new static(
            html_entity_decode($this->text, $flags, $this->encoding),
            $this->encoding
        );
    }


    /**
     * Remove html tags
     */
    public function stripTags(string $allowableTags = null): Text
    {
        if ($allowableTags !== null) {
            $output = strip_tags($this->text, $allowableTags);
        } else {
            $output = strip_tags($this->text);
        }

        return new static($output, $this->encoding);
    }


    /**
     * Remove auto-replace MS word characters back to what they should be
     *
     * Note: Adapted from Stringy\Stringy.
     *
     * @see https://github.com/danielstjules/Stringy/blob/3.1.0/LICENSE.txt
     */
    public function tidyMsWord(): Text
    {
        return new static(
            preg_replace(
                [
                    '/\x{2026}/u',
                    '/[\x{201C}\x{201D}]/u',
                    '/[\x{2018}\x{2019}]/u',
                    '/[\x{2013}\x{2014}]/u',
                ],
                [
                    '...',
                    '"',
                    "'",
                    '-',
                ],
                $this->text
            ),
            $this->encoding
        );
    }



    /**
     * Build a text iterator
     *
     * @return Iterator<Text>
     */
    public function getIterator(): Iterator
    {
        for ($i = 0, $length = $this->getLength(); $i < $length; $i++) {
            yield new static(mb_substr($this->text, $i, 1, $this->encoding), $this->encoding);
        }
    }

    /**
     * Iterate over all split instances
     *
     * @return iterable<int, array<Text>>
     */
    public function searchAll(string $pattern, int $limit = null, string $options = 'msr'): iterable
    {
        if ($pattern === '') {
            return;
        }

        $encoding = mb_regex_encoding();
        mb_regex_encoding($this->encoding);

        mb_ereg_search_init($this->text);

        if (!mb_ereg_search($pattern, $options)) {
            mb_regex_encoding($encoding);
            return;
        }

        if (false === ($result = mb_ereg_search_getregs())) {
            $result = [];
        }

        $count = 0;

        do {
            foreach ($result as $key => $value) {
                $result[$key] = new static($value, $this->encoding);
            }

            yield $result;
            $count++;

            if ($limit !== null && $count == $limit) {
                break;
            }

            $result = mb_ereg_search_regs();
        } while ($result);

        mb_regex_encoding($encoding);
    }

    /**
     * Iterate over all split instances
     *
     * @return iterable<int, Text>
     */
    public function scan(string $pattern, int $limit = null, bool $yieldMatch = false, string $options = 'msr'): iterable
    {
        if ($pattern === '') {
            return;
        }

        $encoding = mb_regex_encoding();
        mb_regex_encoding($this->encoding);
        mb_ereg_search_init($this->text);

        if (!mb_ereg_search($pattern, $options)) {
            mb_regex_encoding($encoding);
            return;
        }

        $count = 0;
        $pos = 0;

        if (false === ($result = mb_ereg_search_getregs())) {
            throw Exceptional::InvalidArgument('Unable to complete mb regex with: ' . $pattern);
        }

        do {
            $rPos = mb_ereg_search_getpos();
            $key = $count;

            yield $key => new static(
                substr($this->text, $pos, $rPos - ($pos + strlen($result[0]))),
                $this->encoding
            );

            if ($yieldMatch) {
                $count++;
                yield $count => new static($result[0], $this->encoding);
            }

            $pos += $rPos - $pos;
            $count++;

            if ($limit !== null && $count == $limit) {
                break;
            }

            if (false === ($result = mb_ereg_search_getregs())) {
                throw Exceptional::InvalidArgument('Unable to complete mb regex with: ' . $pattern);
            }
        } while ($result);

        if ($pos < strlen($this->text)) {
            yield $key => new static(
                substr($this->text, $pos),
                $this->encoding
            );
        }

        mb_regex_encoding($encoding);
    }

    /**
     * Iterate over lines
     *
     * @return iterable<int, Text>
     */
    public function scanLines(): iterable
    {
        return $this->scan('[\r\n]{1,2}');
    }

    /**
     * Iterate over all words
     *
     * @return iterable<int, Text>
     */
    public function scanWords(): iterable
    {
        return $this
            ->regexReplace('\p{P}', ' ')
            ->scan('[[:space:]]+');
    }

    /**
     * Convert to array
     *
     * @return array<string>
     */
    public function toArray(): array
    {
        $output = [];

        for ($i = 0, $length = $this->getLength(); $i < $length; $i++) {
            $output[] = mb_substr($this->text, $i, 1, $this->encoding);
        }

        return $output;
    }

    /**
     * Convert to json
     */
    public function jsonSerialize(): string
    {
        return $this->text;
    }



    /**
     * Is this collection mutable?
     */
    public function isMutable(): bool
    {
        return false;
    }


    /**
     * Note: Adapted from Stringy\Stringy.
     *
     * @see https://github.com/danielstjules/Stringy/blob/3.1.0/LICENSE.txt
     */
    public const ASCII_CHARS = [
        '0' => ['°', '₀', '۰', '０'],
        '1' => ['¹', '₁', '۱', '１'],
        '2' => ['²', '₂', '۲', '２'],
        '3' => ['³', '₃', '۳', '３'],
        '4' => ['⁴', '₄', '۴', '٤', '４'],
        '5' => ['⁵', '₅', '۵', '٥', '５'],
        '6' => ['⁶', '₆', '۶', '٦', '６'],
        '7' => ['⁷', '₇', '۷', '７'],
        '8' => ['⁸', '₈', '۸', '８'],
        '9' => ['⁹', '₉', '۹', '９'],
        'a' => ['à', 'á', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ',
                'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'ā', 'ą', 'å',
                'α', 'ά', 'ἀ', 'ἁ', 'ἂ', 'ἃ', 'ἄ', 'ἅ', 'ἆ', 'ἇ',
                'ᾀ', 'ᾁ', 'ᾂ', 'ᾃ', 'ᾄ', 'ᾅ', 'ᾆ', 'ᾇ', 'ὰ', 'ά',
                'ᾰ', 'ᾱ', 'ᾲ', 'ᾳ', 'ᾴ', 'ᾶ', 'ᾷ', 'а', 'أ', 'အ',
                'ာ', 'ါ', 'ǻ', 'ǎ', 'ª', 'ა', 'अ', 'ا', 'ａ', 'ä'],
        'b' => ['б', 'β', 'ب', 'ဗ', 'ბ', 'ｂ'],
        'c' => ['ç', 'ć', 'č', 'ĉ', 'ċ', 'ｃ'],
        'd' => ['ď', 'ð', 'đ', 'ƌ', 'ȡ', 'ɖ', 'ɗ', 'ᵭ', 'ᶁ', 'ᶑ',
                'д', 'δ', 'د', 'ض', 'ဍ', 'ဒ', 'დ', 'ｄ'],
        'e' => ['é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ',
                'ệ', 'ë', 'ē', 'ę', 'ě', 'ĕ', 'ė', 'ε', 'έ', 'ἐ',
                'ἑ', 'ἒ', 'ἓ', 'ἔ', 'ἕ', 'ὲ', 'έ', 'е', 'ё', 'э',
                'є', 'ə', 'ဧ', 'ေ', 'ဲ', 'ე', 'ए', 'إ', 'ئ', 'ｅ'],
        'f' => ['ф', 'φ', 'ف', 'ƒ', 'ფ', 'ｆ'],
        'g' => ['ĝ', 'ğ', 'ġ', 'ģ', 'г', 'ґ', 'γ', 'ဂ', 'გ', 'گ',
                'ｇ'],
        'h' => ['ĥ', 'ħ', 'η', 'ή', 'ح', 'ه', 'ဟ', 'ှ', 'ჰ', 'ｈ'],
        'i' => ['í', 'ì', 'ỉ', 'ĩ', 'ị', 'î', 'ï', 'ī', 'ĭ', 'į',
                'ı', 'ι', 'ί', 'ϊ', 'ΐ', 'ἰ', 'ἱ', 'ἲ', 'ἳ', 'ἴ',
                'ἵ', 'ἶ', 'ἷ', 'ὶ', 'ί', 'ῐ', 'ῑ', 'ῒ', 'ΐ', 'ῖ',
                'ῗ', 'і', 'ї', 'и', 'ဣ', 'ိ', 'ီ', 'ည်', 'ǐ', 'ი',
                'इ', 'ی', 'ｉ'],
        'j' => ['ĵ', 'ј', 'Ј', 'ჯ', 'ج', 'ｊ'],
        'k' => ['ķ', 'ĸ', 'к', 'κ', 'Ķ', 'ق', 'ك', 'က', 'კ', 'ქ',
                'ک', 'ｋ'],
        'l' => ['ł', 'ľ', 'ĺ', 'ļ', 'ŀ', 'л', 'λ', 'ل', 'လ', 'ლ',
                'ｌ'],
        'm' => ['м', 'μ', 'م', 'မ', 'მ', 'ｍ'],
        'n' => ['ñ', 'ń', 'ň', 'ņ', 'ŉ', 'ŋ', 'ν', 'н', 'ن', 'န',
                'ნ', 'ｎ'],
        'o' => ['ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ',
                'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ø', 'ō', 'ő',
                'ŏ', 'ο', 'ὀ', 'ὁ', 'ὂ', 'ὃ', 'ὄ', 'ὅ', 'ὸ', 'ό',
                'о', 'و', 'θ', 'ို', 'ǒ', 'ǿ', 'º', 'ო', 'ओ', 'ｏ',
                'ö'],
        'p' => ['п', 'π', 'ပ', 'პ', 'پ', 'ｐ'],
        'q' => ['ყ', 'ｑ'],
        'r' => ['ŕ', 'ř', 'ŗ', 'р', 'ρ', 'ر', 'რ', 'ｒ'],
        's' => ['ś', 'š', 'ş', 'с', 'σ', 'ș', 'ς', 'س', 'ص', 'စ',
                'ſ', 'ს', 'ｓ'],
        't' => ['ť', 'ţ', 'т', 'τ', 'ț', 'ت', 'ط', 'ဋ', 'တ', 'ŧ',
                'თ', 'ტ', 'ｔ'],
        'u' => ['ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ',
                'ự', 'û', 'ū', 'ů', 'ű', 'ŭ', 'ų', 'µ', 'у', 'ဉ',
                'ု', 'ူ', 'ǔ', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'უ', 'उ', 'ｕ',
                'ў', 'ü'],
        'v' => ['в', 'ვ', 'ϐ', 'ｖ'],
        'w' => ['ŵ', 'ω', 'ώ', 'ဝ', 'ွ', 'ｗ'],
        'x' => ['χ', 'ξ', 'ｘ'],
        'y' => ['ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'ÿ', 'ŷ', 'й', 'ы', 'υ',
                'ϋ', 'ύ', 'ΰ', 'ي', 'ယ', 'ｙ'],
        'z' => ['ź', 'ž', 'ż', 'з', 'ζ', 'ز', 'ဇ', 'ზ', 'ｚ'],
        'aa' => ['ع', 'आ', 'آ'],
        'ae' => ['æ', 'ǽ'],
        'ai' => ['ऐ'],
        'ch' => ['ч', 'ჩ', 'ჭ', 'چ'],
        'dj' => ['ђ', 'đ'],
        'dz' => ['џ', 'ძ'],
        'ei' => ['ऍ'],
        'gh' => ['غ', 'ღ'],
        'ii' => ['ई'],
        'ij' => ['ĳ'],
        'kh' => ['х', 'خ', 'ხ'],
        'lj' => ['љ'],
        'nj' => ['њ'],
        'oe' => ['œ', 'ؤ'],
        'oi' => ['ऑ'],
        'oii' => ['ऒ'],
        'ps' => ['ψ'],
        'sh' => ['ш', 'შ', 'ش'],
        'shch' => ['щ'],
        'ss' => ['ß'],
        'sx' => ['ŝ'],
        'th' => ['þ', 'ϑ', 'ث', 'ذ', 'ظ'],
        'ts' => ['ц', 'ც', 'წ'],
        'uu' => ['ऊ'],
        'ya' => ['я'],
        'yu' => ['ю'],
        'zh' => ['ж', 'ჟ', 'ژ'],
        '(c)' => ['©'],
        'A' => ['Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ',
                'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'Å', 'Ā', 'Ą',
                'Α', 'Ά', 'Ἀ', 'Ἁ', 'Ἂ', 'Ἃ', 'Ἄ', 'Ἅ', 'Ἆ', 'Ἇ',
                'ᾈ', 'ᾉ', 'ᾊ', 'ᾋ', 'ᾌ', 'ᾍ', 'ᾎ', 'ᾏ', 'Ᾰ', 'Ᾱ',
                'Ὰ', 'Ά', 'ᾼ', 'А', 'Ǻ', 'Ǎ', 'Ａ', 'Ä'],
        'B' => ['Б', 'Β', 'ब', 'Ｂ'],
        'C' => ['Ç', 'Ć', 'Č', 'Ĉ', 'Ċ', 'Ｃ'],
        'D' => ['Ď', 'Ð', 'Đ', 'Ɖ', 'Ɗ', 'Ƌ', 'ᴅ', 'ᴆ', 'Д', 'Δ',
                'Ｄ'],
        'E' => ['É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ',
                'Ệ', 'Ë', 'Ē', 'Ę', 'Ě', 'Ĕ', 'Ė', 'Ε', 'Έ', 'Ἐ',
                'Ἑ', 'Ἒ', 'Ἓ', 'Ἔ', 'Ἕ', 'Έ', 'Ὲ', 'Е', 'Ё', 'Э',
                'Є', 'Ə', 'Ｅ'],
        'F' => ['Ф', 'Φ', 'Ｆ'],
        'G' => ['Ğ', 'Ġ', 'Ģ', 'Г', 'Ґ', 'Γ', 'Ｇ'],
        'H' => ['Η', 'Ή', 'Ħ', 'Ｈ'],
        'I' => ['Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị', 'Î', 'Ï', 'Ī', 'Ĭ', 'Į',
                'İ', 'Ι', 'Ί', 'Ϊ', 'Ἰ', 'Ἱ', 'Ἳ', 'Ἴ', 'Ἵ', 'Ἶ',
                'Ἷ', 'Ῐ', 'Ῑ', 'Ὶ', 'Ί', 'И', 'І', 'Ї', 'Ǐ', 'ϒ',
                'Ｉ'],
        'J' => ['Ｊ'],
        'K' => ['К', 'Κ', 'Ｋ'],
        'L' => ['Ĺ', 'Ł', 'Л', 'Λ', 'Ļ', 'Ľ', 'Ŀ', 'ल', 'Ｌ'],
        'M' => ['М', 'Μ', 'Ｍ'],
        'N' => ['Ń', 'Ñ', 'Ň', 'Ņ', 'Ŋ', 'Н', 'Ν', 'Ｎ'],
        'O' => ['Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ',
                'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'Ø', 'Ō', 'Ő',
                'Ŏ', 'Ο', 'Ό', 'Ὀ', 'Ὁ', 'Ὂ', 'Ὃ', 'Ὄ', 'Ὅ', 'Ὸ',
                'Ό', 'О', 'Θ', 'Ө', 'Ǒ', 'Ǿ', 'Ｏ', 'Ö'],
        'P' => ['П', 'Π', 'Ｐ'],
        'Q' => ['Ｑ'],
        'R' => ['Ř', 'Ŕ', 'Р', 'Ρ', 'Ŗ', 'Ｒ'],
        'S' => ['Ş', 'Ŝ', 'Ș', 'Š', 'Ś', 'С', 'Σ', 'Ｓ'],
        'T' => ['Ť', 'Ţ', 'Ŧ', 'Ț', 'Т', 'Τ', 'Ｔ'],
        'U' => ['Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ',
                'Ự', 'Û', 'Ū', 'Ů', 'Ű', 'Ŭ', 'Ų', 'У', 'Ǔ', 'Ǖ',
                'Ǘ', 'Ǚ', 'Ǜ', 'Ｕ', 'Ў', 'Ü'],
        'V' => ['В', 'Ｖ'],
        'W' => ['Ω', 'Ώ', 'Ŵ', 'Ｗ'],
        'X' => ['Χ', 'Ξ', 'Ｘ'],
        'Y' => ['Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ', 'Ÿ', 'Ῠ', 'Ῡ', 'Ὺ', 'Ύ',
                'Ы', 'Й', 'Υ', 'Ϋ', 'Ŷ', 'Ｙ'],
        'Z' => ['Ź', 'Ž', 'Ż', 'З', 'Ζ', 'Ｚ'],
        'AE' => ['Æ', 'Ǽ'],
        'Ch' => ['Ч'],
        'Dj' => ['Ђ'],
        'Dz' => ['Џ'],
        'Gx' => ['Ĝ'],
        'Hx' => ['Ĥ'],
        'Ij' => ['Ĳ'],
        'Jx' => ['Ĵ'],
        'Kh' => ['Х'],
        'Lj' => ['Љ'],
        'Nj' => ['Њ'],
        'Oe' => ['Œ'],
        'Ps' => ['Ψ'],
        'Sh' => ['Ш'],
        'Shch' => ['Щ'],
        'Ss' => ['ẞ'],
        'Th' => ['Þ'],
        'Ts' => ['Ц'],
        'Ya' => ['Я'],
        'Yu' => ['Ю'],
        'Zh' => ['Ж'],
        ' ' => ["\xC2\xA0", "\xE2\x80\x80", "\xE2\x80\x81",
                "\xE2\x80\x82", "\xE2\x80\x83", "\xE2\x80\x84",
                "\xE2\x80\x85", "\xE2\x80\x86", "\xE2\x80\x87",
                "\xE2\x80\x88", "\xE2\x80\x89", "\xE2\x80\x8A",
                "\xE2\x80\xAF", "\xE2\x81\x9F", "\xE3\x80\x80",
                "\xEF\xBE\xA0"],
    ];


    /**
     * Note: Adapted from Stringy\Stringy.
     *
     * @see https://github.com/danielstjules/Stringy/blob/3.1.0/LICENSE.txt
     */
    public const LANGUAGE_ASCII_CHARS = [
        'de' => [
            ['ä',  'ö',  'ü',  'Ä',  'Ö',  'Ü' ],
            ['ae', 'oe', 'ue', 'AE', 'OE', 'UE'],
        ],
        'bg' => [
            ['х', 'Х', 'щ', 'Щ', 'ъ', 'Ъ', 'ь', 'Ь'],
            ['h', 'H', 'sht', 'SHT', 'a', 'А', 'y', 'Y']
        ]
    ];


    /**
     * Normalize encoding
     *
     * @param Text|string|Stringable|null $text
     */
    protected function normalizeEncoding($text, ?string $encoding): ?string
    {
        if ($text === null) {
            return null;
        }

        if (
            $encoding !== null &&
            $encoding !== $this->encoding
        ) {
            $text = mb_convert_encoding((string)$text, $this->encoding, $encoding);
        }

        return (string)$text;
    }



    /**
     * Export for dump inspection
     */
    public function glitchDump(): iterable
    {
        yield 'metaList' => [
            'encoding' => $this->encoding
        ];

        yield 'text' => mb_convert_encoding($this->text, 'UTF-8', $this->encoding);
    }
}
