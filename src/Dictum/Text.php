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
use Stringable;

/**
 * @implements ArrayAccess<int, Text>
 */
class Text implements
    ArrayAccess,
    Countable,
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
        if ($encoding === $this->encoding) {
            return $this;
        }

        return new static(
            mb_convert_encoding($this->text, $encoding, $this->encoding),
            $encoding
        );
    }


    /**
     * Convert to UTF-8
     */
    public function toUtf8(): Text
    {
        return $this->convertEncoding('UTF-8');
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
     *
     * @param string|callable $replacement
     */
    public function regexReplace(string $pattern, $replacement, string $options = 'msr'): Text
    {
        $encoding = mb_regex_encoding();
        $oldOptions = mb_regex_set_options($options);
        mb_regex_encoding($this->encoding);

        if (is_string($replacement)) {
            $output = mb_ereg_replace($pattern, (string)$replacement, $this->text, $options);
        } elseif (is_callable($replacement)) {
            $output = mb_ereg_replace_callback($pattern, $replacement, $this->text, $options);
        } else {
            throw Exceptional::InvalidArgument('Replacement must be string or callable');
        }

        if ($output === false) {
            throw Exceptional::Runtime('Unable to complete mb regex');
        }

        mb_regex_encoding($encoding);
        mb_regex_set_options($oldOptions);
        return new static($output, $this->encoding);
    }


    /**
     * Split by $string
     *
     * @param non-empty-string $delimiter
     * @return array<Text>
     */
    public function split(string $delimiter, int $limit = PHP_INT_MAX): array
    {
        $output = (array)explode($delimiter, $this->text, $limit);

        return array_map(function ($part) {
            return new static((string)$part, $this->encoding);
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
        return $this->matches('^[[:alpha:]]+$');
    }

    /**
     * Only contains alpha numeric characters
     */
    public function isAlphaNumeric(): bool
    {
        return $this->matches('^[[:alnum:]]+$');
    }

    /**
     * Only contains digits
     */
    public function isDigit(): bool
    {
        return $this->matches('^[0-9]+$');
    }

    /**
     * Only contains whitespace
     */
    public function isWhitespace(): bool
    {
        return $this->matches('^[[:space:]]+$');
    }

    /**
     * Only contains whitespace or empty
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
        return $this->matches('^[[:xdigit:]]+$');
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
     * @return iterable<Text>
     */
    public function scan(): iterable
    {
        for ($i = 0, $length = $this->getLength(); $i < $length; $i++) {
            yield new static(mb_substr($this->text, $i, 1, $this->encoding), $this->encoding);
        }
    }

    /**
     * Iterate over all split instances
     *
     * @return iterable<int, Text>
     */
    public function scanMatches(string $pattern, int $limit = null, bool $yieldMatch = false, string $options = 'msr'): iterable
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
        return $this->scanMatches('[\r\n]{1,2}');
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
            ->scanMatches('[[:space:]]+');
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
        '0' => ['??', '???', '??', '???'],
        '1' => ['??', '???', '??', '???'],
        '2' => ['??', '???', '??', '???'],
        '3' => ['??', '???', '??', '???'],
        '4' => ['???', '???', '??', '??', '???'],
        '5' => ['???', '???', '??', '??', '???'],
        '6' => ['???', '???', '??', '??', '???'],
        '7' => ['???', '???', '??', '???'],
        '8' => ['???', '???', '??', '???'],
        '9' => ['???', '???', '??', '???'],
        'a' => ['??', '??', '???', '??', '???', '??', '???', '???', '???', '???',
                '???', '??', '???', '???', '???', '???', '???', '??', '??', '??',
                '??', '??', '???', '???', '???', '???', '???', '???', '???', '???',
                '???', '???', '???', '???', '???', '???', '???', '???', '???', '??',
                '???', '???', '???', '???', '???', '???', '???', '??', '??', '???',
                '???', '???', '??', '??', '??', '???', '???', '??', '???', '??'],
        'b' => ['??', '??', '??', '???', '???', '???'],
        'c' => ['??', '??', '??', '??', '??', '???'],
        'd' => ['??', '??', '??', '??', '??', '??', '??', '???', '???', '???',
                '??', '??', '??', '??', '???', '???', '???', '???'],
        'e' => ['??', '??', '???', '???', '???', '??', '???', '???', '???', '???',
                '???', '??', '??', '??', '??', '??', '??', '??', '??', '???',
                '???', '???', '???', '???', '???', '???', '??', '??', '??', '??',
                '??', '??', '???', '???', '???', '???', '???', '??', '??', '???'],
        'f' => ['??', '??', '??', '??', '???', '???'],
        'g' => ['??', '??', '??', '??', '??', '??', '??', '???', '???', '??',
                '???'],
        'h' => ['??', '??', '??', '??', '??', '??', '???', '???', '???', '???'],
        'i' => ['??', '??', '???', '??', '???', '??', '??', '??', '??', '??',
                '??', '??', '??', '??', '??', '???', '???', '???', '???', '???',
                '???', '???', '???', '???', '??', '???', '???', '???', '??', '???',
                '???', '??', '??', '??', '???', '???', '???', '??????', '??', '???',
                '???', '??', '???'],
        'j' => ['??', '??', '??', '???', '??', '???'],
        'k' => ['??', '??', '??', '??', '??', '??', '??', '???', '???', '???',
                '??', '???'],
        'l' => ['??', '??', '??', '??', '??', '??', '??', '??', '???', '???',
                '???'],
        'm' => ['??', '??', '??', '???', '???', '???'],
        'n' => ['??', '??', '??', '??', '??', '??', '??', '??', '??', '???',
                '???', '???'],
        'o' => ['??', '??', '???', '??', '???', '??', '???', '???', '???', '???',
                '???', '??', '???', '???', '???', '???', '???', '??', '??', '??',
                '??', '??', '???', '???', '???', '???', '???', '???', '???', '??',
                '??', '??', '??', '??????', '??', '??', '??', '???', '???', '???',
                '??'],
        'p' => ['??', '??', '???', '???', '??', '???'],
        'q' => ['???', '???'],
        'r' => ['??', '??', '??', '??', '??', '??', '???', '???'],
        's' => ['??', '??', '??', '??', '??', '??', '??', '??', '??', '???',
                '??', '???', '???'],
        't' => ['??', '??', '??', '??', '??', '??', '??', '???', '???', '??',
                '???', '???', '???'],
        'u' => ['??', '??', '???', '??', '???', '??', '???', '???', '???', '???',
                '???', '??', '??', '??', '??', '??', '??', '??', '??', '???',
                '???', '???', '??', '??', '??', '??', '??', '???', '???', '???',
                '??', '??'],
        'v' => ['??', '???', '??', '???'],
        'w' => ['??', '??', '??', '???', '???', '???'],
        'x' => ['??', '??', '???'],
        'y' => ['??', '???', '???', '???', '???', '??', '??', '??', '??', '??',
                '??', '??', '??', '??', '???', '???'],
        'z' => ['??', '??', '??', '??', '??', '??', '???', '???', '???'],
        'aa' => ['??', '???', '??'],
        'ae' => ['??', '??'],
        'ai' => ['???'],
        'ch' => ['??', '???', '???', '??'],
        'dj' => ['??', '??'],
        'dz' => ['??', '???'],
        'ei' => ['???'],
        'gh' => ['??', '???'],
        'ii' => ['???'],
        'ij' => ['??'],
        'kh' => ['??', '??', '???'],
        'lj' => ['??'],
        'nj' => ['??'],
        'oe' => ['??', '??'],
        'oi' => ['???'],
        'oii' => ['???'],
        'ps' => ['??'],
        'sh' => ['??', '???', '??'],
        'shch' => ['??'],
        'ss' => ['??'],
        'sx' => ['??'],
        'th' => ['??', '??', '??', '??', '??'],
        'ts' => ['??', '???', '???'],
        'uu' => ['???'],
        'ya' => ['??'],
        'yu' => ['??'],
        'zh' => ['??', '???', '??'],
        '(c)' => ['??'],
        'A' => ['??', '??', '???', '??', '???', '??', '???', '???', '???', '???',
                '???', '??', '???', '???', '???', '???', '???', '??', '??', '??',
                '??', '??', '???', '???', '???', '???', '???', '???', '???', '???',
                '???', '???', '???', '???', '???', '???', '???', '???', '???', '???',
                '???', '??', '???', '??', '??', '??', '???', '??'],
        'B' => ['??', '??', '???', '???'],
        'C' => ['??', '??', '??', '??', '??', '???'],
        'D' => ['??', '??', '??', '??', '??', '??', '???', '???', '??', '??',
                '???'],
        'E' => ['??', '??', '???', '???', '???', '??', '???', '???', '???', '???',
                '???', '??', '??', '??', '??', '??', '??', '??', '??', '???',
                '???', '???', '???', '???', '???', '??', '???', '??', '??', '??',
                '??', '??', '???'],
        'F' => ['??', '??', '???'],
        'G' => ['??', '??', '??', '??', '??', '??', '???'],
        'H' => ['??', '??', '??', '???'],
        'I' => ['??', '??', '???', '??', '???', '??', '??', '??', '??', '??',
                '??', '??', '??', '??', '???', '???', '???', '???', '???', '???',
                '???', '???', '???', '???', '??', '??', '??', '??', '??', '??',
                '???'],
        'J' => ['???'],
        'K' => ['??', '??', '???'],
        'L' => ['??', '??', '??', '??', '??', '??', '??', '???', '???'],
        'M' => ['??', '??', '???'],
        'N' => ['??', '??', '??', '??', '??', '??', '??', '???'],
        'O' => ['??', '??', '???', '??', '???', '??', '???', '???', '???', '???',
                '???', '??', '???', '???', '???', '???', '???', '??', '??', '??',
                '??', '??', '??', '???', '???', '???', '???', '???', '???', '???',
                '??', '??', '??', '??', '??', '??', '???', '??'],
        'P' => ['??', '??', '???'],
        'Q' => ['???'],
        'R' => ['??', '??', '??', '??', '??', '???'],
        'S' => ['??', '??', '??', '??', '??', '??', '??', '???'],
        'T' => ['??', '??', '??', '??', '??', '??', '???'],
        'U' => ['??', '??', '???', '??', '???', '??', '???', '???', '???', '???',
                '???', '??', '??', '??', '??', '??', '??', '??', '??', '??',
                '??', '??', '??', '???', '??', '??'],
        'V' => ['??', '???'],
        'W' => ['??', '??', '??', '???'],
        'X' => ['??', '??', '???'],
        'Y' => ['??', '???', '???', '???', '???', '??', '???', '???', '???', '??',
                '??', '??', '??', '??', '??', '???'],
        'Z' => ['??', '??', '??', '??', '??', '???'],
        'AE' => ['??', '??'],
        'Ch' => ['??'],
        'Dj' => ['??'],
        'Dz' => ['??'],
        'Gx' => ['??'],
        'Hx' => ['??'],
        'Ij' => ['??'],
        'Jx' => ['??'],
        'Kh' => ['??'],
        'Lj' => ['??'],
        'Nj' => ['??'],
        'Oe' => ['??'],
        'Ps' => ['??'],
        'Sh' => ['??'],
        'Shch' => ['??'],
        'Ss' => ['???'],
        'Th' => ['??'],
        'Ts' => ['??'],
        'Ya' => ['??'],
        'Yu' => ['??'],
        'Zh' => ['??'],
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
            ['??',  '??',  '??',  '??',  '??',  '??' ],
            ['ae', 'oe', 'ue', 'AE', 'OE', 'UE'],
        ],
        'bg' => [
            ['??', '??', '??', '??', '??', '??', '??', '??'],
            ['h', 'H', 'sht', 'SHT', 'a', '??', 'y', 'Y']
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
