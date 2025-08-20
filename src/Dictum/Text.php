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
use DecodeLabs\Fluidity\Then;
use DecodeLabs\Fluidity\ThenTrait;
use DecodeLabs\Nuance\Dumpable;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;
use Stringable;

/**
 * @implements ArrayAccess<int, static>
 */
class Text implements
    Then,
    ArrayAccess,
    Countable,
    Stringable,
    Dumpable
{
    use ThenTrait;

    public protected(set) string $encoding;
    public protected(set) string $text;


    /**
     * Create a new instance
     */
    public static function create(
        ?string $text = '',
        ?string $encoding = null
    ): static {
        return new static($text, $encoding);
    }


    /**
     * Create with initial value and encoding, defaults to mb_internal_encoding
     */
    final public function __construct(
        ?string $text = '',
        ?string $encoding = null
    ) {
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
    public function convertEncoding(
        string $encoding
    ): static {
        if ($encoding === $this->encoding) {
            return $this;
        }

        return new static(
            (string)mb_convert_encoding($this->text, $encoding, $this->encoding),
            $encoding
        );
    }


    /**
     * Convert to UTF-8
     */
    public function toUtf8(): static
    {
        return $this->convertEncoding('UTF-8');
    }



    /**
     * Get a single char at index
     */
    public function getChar(
        int $index
    ): static {
        return $this->slice($index, 1);
    }

    /**
     * Replace a single char
     */
    public function replaceChar(
        int $index,
        string $char
    ): static {
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
    public function insert(
        int $index,
        string $string
    ): static {
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
    public function removeChar(
        int $index
    ): static {
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
    public function hasCharAt(
        int $index
    ): bool {
        return mb_substr($this->text, $index, 1, $this->encoding) !== '';
    }


    /**
     * Get char at index
     *
     * @param int $index
     */
    public function offsetGet(
        mixed $index
    ): static {
        return $this->slice($index, 1);
    }

    /**
     * Not supported for immutable
     *
     * @param int $index
     * @param Text $value
     */
    public function offsetSet(
        mixed $index,
        mixed $value
    ): void {
        throw Exceptional::Implementation(
            message: 'Immutable DecodeLabs\\Dictum\\Text does not support array-access setting'
        );
    }

    /**
     * Check char
     *
     * @param int $index
     */
    public function offsetExists(
        mixed $index
    ): bool {
        return $this->hasCharAt($index);
    }

    /**
     * Remove character
     *
     * @param int $index
     */
    public function offsetUnset(
        mixed $index
    ): void {
        throw Exceptional::Implementation(
            message: 'Immutable DecodeLabs\\Dictum\\Text does not support array-access unset'
        );
    }



    /**
     * Get first index of needle
     */
    public function getIndexOf(
        string $needle,
        int $offset = 0
    ): ?int {
        if (false === ($output = mb_strpos($this->text, $needle, $offset, $this->encoding))) {
            $output = null;
        }

        return $output;
    }

    /**
     * Get first index of needle
     */
    public function getIndexOfCi(
        string $needle,
        int $offset = 0
    ): ?int {
        if (false === ($output = mb_stripos($this->text, $needle, $offset, $this->encoding))) {
            $output = null;
        }

        return $output;
    }

    /**
     * Get last index of needle
     */
    public function getLastIndexOf(
        string $needle,
        int $offset = 0
    ): ?int {
        if (false === ($output = mb_strrpos($this->text, $needle, $offset, $this->encoding))) {
            $output = null;
        }

        return $output;
    }

    /**
     * Get last index of needle
     */
    public function getLastIndexOfCi(
        string $needle,
        int $offset = 0
    ): ?int {
        if (false === ($output = mb_strripos($this->text, $needle, $offset, $this->encoding))) {
            $output = null;
        }

        return $output;
    }




    /**
     * Is $needle in text?
     */
    public function contains(
        string ...$needles
    ): bool {
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
    public function containsCi(
        string ...$needles
    ): bool {
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
    public function containsAll(
        string ...$needles
    ): bool {
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
    public function containsAllCi(
        string ...$needles
    ): bool {
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
    public function beginsWith(
        string ...$starts
    ): bool {
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
    public function beginsWithCi(
        string ...$starts
    ): bool {
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
    public function endsWith(
        string ...$ends
    ): bool {
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
    public function endsWithCi(
        string ...$ends
    ): bool {
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
    public function padLeft(
        int $size,
        string $value = ' '
    ): static {
        return new static(
            str_pad($this->text, abs($size), $value, STR_PAD_LEFT),
            $this->encoding
        );
    }

    /**
     * Ensure sequence is at least $size long
     */
    public function padRight(
        int $size,
        string $value = ' '
    ): static {
        return new static(
            str_pad($this->text, abs($size), $value, STR_PAD_RIGHT),
            $this->encoding
        );
    }

    /**
     * Ensure sequence is at least $size long
     */
    public function padBoth(
        int $size,
        string $value = ' '
    ): static {
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
    public function countInstances(
        string $string
    ): int {
        return mb_substr_count($this->text, $string, $this->encoding);
    }

    /**
     * Count case insensitive instances of $string
     */
    public function countInstancesCi(
        string $string
    ): int {
        return mb_substr_count(
            mb_strtolower($this->text, $this->encoding),
            mb_strtolower($string, $this->encoding),
            $this->encoding
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
    public function slice(
        int $start,
        ?int $length = null
    ): static {
        $output = mb_substr($this->text, $start, $length, $this->encoding);
        return new static($output, $this->encoding);
    }

    /**
     * Get random slice
     */
    public function sliceRandom(
        int $length
    ): static {
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
    public function sliceDelimited(
        string $start,
        string $end,
        int $offset = 0
    ): static {
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
     */
    public function append(
        Text|string|Stringable|null $text,
        ?string $encoding = null
    ): static {
        $text = (string)$this->normalizeEncoding($text, $encoding);
        return new static($this->text . $text, $this->encoding);
    }

    /**
     * Add a string to the start
     */
    public function prepend(
        Text|string|Stringable|null $text,
        ?string $encoding = null
    ): static {
        $text = (string)$this->normalizeEncoding($text, $encoding);
        return new static($text . $this->text, $this->encoding);
    }

    /**
     * Add string to start and end
     */
    public function surroundWith(
        Text|string|Stringable|null $text,
        ?string $encoding = null
    ): static {
        $text = (string)$this->normalizeEncoding($text, $encoding);
        return new static($text . $this->text . $text, $this->encoding);
    }



    /**
     * Limit length to $length - length of $cap, adds cap to end
     */
    public function truncate(
        int $length,
        ?string $cap = null
    ): static {
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
    public function collapseWhitespace(): static
    {
        return $this->regexReplace('[[:space:]]+', ' ')->trim();
    }

    /**
     * Remove all whitespace
     */
    public function stripWhitespace(): static
    {
        return $this->regexReplace('[[:space:]]+', '');
    }


    /**
     * Regex match
     */
    public function matches(
        string $pattern,
        string $options = 'msr'
    ): bool {
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
     * @return ?list<string>
     */
    public function match(
        string $pattern,
        string $options = 'msr'
    ): ?array {
        $encoding = mb_regex_encoding();
        $oldOptions = mb_regex_set_options($options);
        mb_regex_encoding($this->encoding);

        if (false === mb_ereg($pattern, $this->text, $output)) {
            $output = null;
        }

        mb_regex_set_options($oldOptions);
        mb_regex_encoding($encoding);

        /** @var ?list<string> */
        return $output;
    }

    /**
     * Replace a chunk
     *
     * @param list<string>|string $search
     * @param list<string>|string $replace
     */
    public function replace(
        array|string $search,
        array|string $replace,
        int &$count = 0
    ): static {
        return new static(
            str_replace($search, $replace, $this->text, $count),
            $this->encoding
        );
    }

    /**
     * Replace all occurances of $pattern in text
     */
    public function regexReplace(
        string $pattern,
        string|callable $replacement,
        string $options = 'msr'
    ): static {
        $encoding = mb_regex_encoding();
        $oldOptions = mb_regex_set_options($options);
        mb_regex_encoding($this->encoding);

        if (is_string($replacement)) {
            $output = mb_ereg_replace($pattern, (string)$replacement, $this->text, $options);
        } elseif (is_callable($replacement)) {
            $output = mb_ereg_replace_callback($pattern, $replacement, $this->text, $options);
        } else {
            throw Exceptional::InvalidArgument(
                message: 'Replacement must be string or callable'
            );
        }

        if ($output === false) {
            throw Exceptional::Runtime(
                message: 'Unable to complete mb regex'
            );
        }

        mb_regex_encoding($encoding);
        mb_regex_set_options($oldOptions);
        return new static($output, $this->encoding);
    }


    /**
     * Split by $string
     *
     * @param non-empty-string $delimiter
     * @return array<static>
     */
    public function split(
        string $delimiter,
        int $limit = PHP_INT_MAX
    ): array {
        $output = (array)explode($delimiter, $this->text, $limit);

        return array_map(function ($part) {
            return new static((string)$part, $this->encoding);
        }, $output);
    }

    /**
     * Split by $pattern
     *
     * @return array<static>
     */
    public function regexSplit(
        string $pattern,
        int $limit = -1
    ): array {
        if (false === ($parts = mb_split($pattern, $this->text, $limit))) {
            throw Exceptional::Runtime(
                message: 'Unable to split text with: ' . $pattern
            );
        }

        return array_map(function ($part) {
            return new static($part, $this->encoding);
        }, $parts);
    }


    /**
     * Replace whitespace with $delimiter, all lowercase
     */
    public function delimit(
        string $delimiter
    ): static {
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
    public function trim(
        ?string $chars = null
    ): static {
        $chars = $chars ? preg_quote($chars) : '[:space:]';
        return $this->regexReplace('^[' . $chars . ']+|[' . $chars . ']+$', '');
    }

    /**
     * Trim left
     */
    public function trimLeft(
        ?string $chars = null
    ): static {
        $chars = $chars ? preg_quote($chars) : '[:space:]';
        return $this->regexReplace('^[' . $chars . ']+', '');
    }

    /**
     * Trim right
     */
    public function trimRight(
        ?string $chars = null
    ): static {
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
    public function toLowerCase(): static
    {
        return new static(
            mb_strtolower($this->text, $this->encoding),
            $this->encoding
        );
    }

    /**
     * Convert first character to lowercase
     */
    public function firstToLowerCase(): static
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
    public function toUpperCase(): static
    {
        return new static(
            mb_strtoupper($this->text, $this->encoding),
            $this->encoding
        );
    }

    /**
     * Convert first character to uppercase
     */
    public function firstToUpperCase(): static
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
    public function toTitleCase(): static
    {
        return new static(
            mb_convert_case($this->text, MB_CASE_TITLE, $this->encoding),
            $this->encoding
        );
    }



    /**
     * Swap case of all characters
     */
    public function swapCase(): static
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
    public function toAscii(
        string $language = 'en',
        bool $removeUnsupported = true
    ): static {
        $output = $this->text;

        if (isset(self::LanguageAsciiChars[$language])) {
            $output = str_replace(
                self::LanguageAsciiChars[$language][0],
                self::LanguageAsciiChars[$language][1],
                $output
            );
        }

        foreach (self::AsciiChars as $key => $value) {
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
    public function toBoolean(
        ?bool $default = null
    ): bool {
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
    public function tabsToSpaces(
        int $tabLength = 4
    ): static {
        return new static(
            str_replace("\t", str_repeat(' ', $tabLength), $this->text),
            $this->encoding
        );
    }

    /**
     * Convert spaces to tabs
     */
    public function spacesToTabs(
        int $tabLength
    ): static {
        return new static(
            str_replace(str_repeat(' ', $tabLength), "\t", $this->text),
            $this->encoding
        );
    }


    /**
     * Convert numerics to alpha characters
     */
    public static function numericToAlpha(
        int $number
    ): static {
        $output = '';

        while ($number >= 0) {
            $key = $number % 26;
            $output = self::Alphabet[$key] . $output;
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

        return $output;
    }




    /**
     * Encode for HTML
     */
    public function htmlEncode(
        int $flags = ENT_COMPAT
    ): static {
        return new static(
            htmlentities($this->text, $flags, $this->encoding),
            $this->encoding
        );
    }

    /**
     * Decode from HTML
     */
    public function htmlDecode(
        int $flags = ENT_COMPAT
    ): static {
        return new static(
            html_entity_decode($this->text, $flags, $this->encoding),
            $this->encoding
        );
    }


    /**
     * Remove html tags
     */
    public function stripTags(
        ?string $allowableTags = null
    ): static {
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
    public function tidyMsWord(): static
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
     * @return iterable<static>
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
     * @return iterable<int,static>
     */
    public function scanMatches(
        string $pattern,
        ?int $limit = null,
        bool $yieldMatch = false,
        string $options = 'msr'
    ): iterable {
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
        $result = mb_ereg_search_getregs();

        if ($result === false) {
            throw Exceptional::InvalidArgument(
                message: 'Unable to complete mb regex with: ' . $pattern
            );
        }

        do {
            /** @var list<string> $result */
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

            $pos = $rPos;
            $count++;

            if (
                $limit !== null &&
                $count == $limit
            ) {
                break;
            }

            $result = mb_ereg_search_regs();
        } while ($result);

        if ($pos < strlen($this->text)) {
            yield $count => new static(
                substr($this->text, $pos),
                $this->encoding
            );
        }

        mb_regex_encoding($encoding);
    }

    /**
     * Iterate over lines
     *
     * @return iterable<int, static>
     */
    public function scanLines(): iterable
    {
        return $this->scanMatches('[\r\n]{1,2}');
    }

    /**
     * Iterate over all words
     *
     * @return iterable<int, static>
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
     * @return iterable<int,list<static>>
     */
    public function searchAll(
        string $pattern,
        ?int $limit = null,
        string $options = 'msr'
    ): iterable {
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
            /** @var list<string> $result */
            foreach ($result as $key => $value) {
                $result[$key] = new static($value, $this->encoding);
            }

            /** @var list<static> $result */
            yield $result;
            $count++;

            if (
                $limit !== null &&
                $count == $limit
            ) {
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


    protected const Alphabet = 'abcdefghijklmnopqrstuvwxyz';


    /**
     * Note: Adapted from Stringy\Stringy.
     *
     * @see https://github.com/danielstjules/Stringy/blob/3.1.0/LICENSE.txt
     *
     * @var array<string|int,list<string>>
     */
    protected const AsciiChars = [
        '0' => ['°', '₀', '۰', '０'],  // @ignore-non-ascii
        '1' => ['¹', '₁', '۱', '１'],  // @ignore-non-ascii
        '2' => ['²', '₂', '۲', '２'],  // @ignore-non-ascii
        '3' => ['³', '₃', '۳', '３'],  // @ignore-non-ascii
        '4' => ['⁴', '₄', '۴', '٤', '４'],  // @ignore-non-ascii
        '5' => ['⁵', '₅', '۵', '٥', '５'],  // @ignore-non-ascii
        '6' => ['⁶', '₆', '۶', '٦', '６'],  // @ignore-non-ascii
        '7' => ['⁷', '₇', '۷', '７'],  // @ignore-non-ascii
        '8' => ['⁸', '₈', '۸', '８'],  // @ignore-non-ascii
        '9' => ['⁹', '₉', '۹', '９'],  // @ignore-non-ascii
        'a' => ['à', 'á', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ',  // @ignore-non-ascii
                'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'ā', 'ą', 'å',  // @ignore-non-ascii
                'α', 'ά', 'ἀ', 'ἁ', 'ἂ', 'ἃ', 'ἄ', 'ἅ', 'ἆ', 'ἇ',  // @ignore-non-ascii
                'ᾀ', 'ᾁ', 'ᾂ', 'ᾃ', 'ᾄ', 'ᾅ', 'ᾆ', 'ᾇ', 'ὰ', 'ά',  // @ignore-non-ascii
                'ᾰ', 'ᾱ', 'ᾲ', 'ᾳ', 'ᾴ', 'ᾶ', 'ᾷ', 'а', 'أ', 'အ',  // @ignore-non-ascii
                'ာ', 'ါ', 'ǻ', 'ǎ', 'ª', 'ა', 'अ', 'ا', 'ａ', 'ä'],  // @ignore-non-ascii
        'b' => ['б', 'β', 'ب', 'ဗ', 'ბ', 'ｂ'],  // @ignore-non-ascii
        'c' => ['ç', 'ć', 'č', 'ĉ', 'ċ', 'ｃ'],  // @ignore-non-ascii
        'd' => ['ď', 'ð', 'đ', 'ƌ', 'ȡ', 'ɖ', 'ɗ', 'ᵭ', 'ᶁ', 'ᶑ',  // @ignore-non-ascii
                'д', 'δ', 'د', 'ض', 'ဍ', 'ဒ', 'დ', 'ｄ'],  // @ignore-non-ascii
        'e' => ['é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ',  // @ignore-non-ascii
                'ệ', 'ë', 'ē', 'ę', 'ě', 'ĕ', 'ė', 'ε', 'έ', 'ἐ',  // @ignore-non-ascii
                'ἑ', 'ἒ', 'ἓ', 'ἔ', 'ἕ', 'ὲ', 'έ', 'е', 'ё', 'э',  // @ignore-non-ascii
                'є', 'ə', 'ဧ', 'ေ', 'ဲ', 'ე', 'ए', 'إ', 'ئ', 'ｅ'],  // @ignore-non-ascii
        'f' => ['ф', 'φ', 'ف', 'ƒ', 'ფ', 'ｆ'],  // @ignore-non-ascii
        'g' => ['ĝ', 'ğ', 'ġ', 'ģ', 'г', 'ґ', 'γ', 'ဂ', 'გ', 'گ', 'ｇ'],  // @ignore-non-ascii
        'h' => ['ĥ', 'ħ', 'η', 'ή', 'ح', 'ه', 'ဟ', 'ှ', 'ჰ', 'ｈ'],  // @ignore-non-ascii
        'i' => ['í', 'ì', 'ỉ', 'ĩ', 'ị', 'î', 'ï', 'ī', 'ĭ', 'į',  // @ignore-non-ascii
                'ı', 'ι', 'ί', 'ϊ', 'ΐ', 'ἰ', 'ἱ', 'ἲ', 'ἳ', 'ἴ',  // @ignore-non-ascii
                'ἵ', 'ἶ', 'ἷ', 'ὶ', 'ί', 'ῐ', 'ῑ', 'ῒ', 'ΐ', 'ῖ',  // @ignore-non-ascii
                'ῗ', 'і', 'ї', 'и', 'ဣ', 'ိ', 'ီ', 'ည်', 'ǐ', 'ი',  // @ignore-non-ascii
                'इ', 'ی', 'ｉ'],  // @ignore-non-ascii
        'j' => ['ĵ', 'ј', 'Ј', 'ჯ', 'ج', 'ｊ'],  // @ignore-non-ascii
        'k' => ['ķ', 'ĸ', 'к', 'κ', 'Ķ', 'ق', 'ك', 'က', 'კ', 'ქ',  // @ignore-non-ascii
                'ک', 'ｋ'],  // @ignore-non-ascii
        'l' => ['ł', 'ľ', 'ĺ', 'ļ', 'ŀ', 'л', 'λ', 'ل', 'လ', 'ლ',  // @ignore-non-ascii
                'ｌ'],  // @ignore-non-ascii
        'm' => ['м', 'μ', 'م', 'မ', 'მ', 'ｍ'],  // @ignore-non-ascii
        'n' => ['ñ', 'ń', 'ň', 'ņ', 'ŉ', 'ŋ', 'ν', 'н', 'ن', 'န',  // @ignore-non-ascii
                'ნ', 'ｎ'],  // @ignore-non-ascii
        'o' => ['ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ',  // @ignore-non-ascii
                'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ø', 'ō', 'ő',  // @ignore-non-ascii
                'ŏ', 'ο', 'ὀ', 'ὁ', 'ὂ', 'ὃ', 'ὄ', 'ὅ', 'ὸ', 'ό',  // @ignore-non-ascii
                'о', 'و', 'θ', 'ို', 'ǒ', 'ǿ', 'º', 'ო', 'ओ', 'ｏ',  // @ignore-non-ascii
                'ö'],  // @ignore-non-ascii
        'p' => ['п', 'π', 'ပ', 'პ', 'پ', 'ｐ'],  // @ignore-non-ascii
        'q' => ['ყ', 'ｑ'],  // @ignore-non-ascii
        'r' => ['ŕ', 'ř', 'ŗ', 'р', 'ρ', 'ر', 'რ', 'ｒ'],  // @ignore-non-ascii
        's' => ['ś', 'š', 'ş', 'с', 'σ', 'ș', 'ς', 'س', 'ص', 'စ',  // @ignore-non-ascii
                'ſ', 'ს', 'ｓ'],  // @ignore-non-ascii
        't' => ['ť', 'ţ', 'т', 'τ', 'ț', 'ت', 'ط', 'ဋ', 'တ', 'ŧ',  // @ignore-non-ascii
                'თ', 'ტ', 'ｔ'],  // @ignore-non-ascii
        'u' => ['ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ',  // @ignore-non-ascii
                'ự', 'û', 'ū', 'ů', 'ű', 'ŭ', 'ų', 'µ', 'у', 'ဉ',  // @ignore-non-ascii
                'ု', 'ူ', 'ǔ', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'უ', 'उ', 'ｕ',  // @ignore-non-ascii
                'ў', 'ü'],  // @ignore-non-ascii
        'v' => ['в', 'ვ', 'ϐ', 'ｖ'],  // @ignore-non-ascii
        'w' => ['ŵ', 'ω', 'ώ', 'ဝ', 'ွ', 'ｗ'],  // @ignore-non-ascii
        'x' => ['χ', 'ξ', 'ｘ'],  // @ignore-non-ascii
        'y' => ['ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'ÿ', 'ŷ', 'й', 'ы', 'υ',  // @ignore-non-ascii
                'ϋ', 'ύ', 'ΰ', 'ي', 'ယ', 'ｙ'],  // @ignore-non-ascii
        'z' => ['ź', 'ž', 'ż', 'з', 'ζ', 'ز', 'ဇ', 'ზ', 'ｚ'],  // @ignore-non-ascii
        'aa' => ['ع', 'आ', 'آ'],  // @ignore-non-ascii
        'ae' => ['æ', 'ǽ'],  // @ignore-non-ascii
        'ai' => ['ऐ'],  // @ignore-non-ascii
        'ch' => ['ч', 'ჩ', 'ჭ', 'چ'],  // @ignore-non-ascii
        'dj' => ['ђ', 'đ'],  // @ignore-non-ascii
        'dz' => ['џ', 'ძ'],  // @ignore-non-ascii
        'ei' => ['ऍ'],  // @ignore-non-ascii
        'gh' => ['غ', 'ღ'],  // @ignore-non-ascii
        'ii' => ['ई'],  // @ignore-non-ascii
        'ij' => ['ĳ'],  // @ignore-non-ascii
        'kh' => ['х', 'خ', 'ხ'],  // @ignore-non-ascii
        'lj' => ['љ'],  // @ignore-non-ascii
        'nj' => ['њ'],  // @ignore-non-ascii
        'oe' => ['œ', 'ؤ'],  // @ignore-non-ascii
        'oi' => ['ऑ'],  // @ignore-non-ascii
        'oii' => ['ऒ'],  // @ignore-non-ascii
        'ps' => ['ψ'],  // @ignore-non-ascii
        'sh' => ['ш', 'შ', 'ش'],  // @ignore-non-ascii
        'shch' => ['щ'],  // @ignore-non-ascii
        'ss' => ['ß'],  // @ignore-non-ascii
        'sx' => ['ŝ'],  // @ignore-non-ascii
        'th' => ['þ', 'ϑ', 'ث', 'ذ', 'ظ'],  // @ignore-non-ascii
        'ts' => ['ц', 'ც', 'წ'],  // @ignore-non-ascii
        'uu' => ['ऊ'],  // @ignore-non-ascii
        'ya' => ['я'],  // @ignore-non-ascii
        'yu' => ['ю'],  // @ignore-non-ascii
        'zh' => ['ж', 'ჟ', 'ژ'],  // @ignore-non-ascii
        '(c)' => ['©'],  // @ignore-non-ascii
        'A' => ['Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ',  // @ignore-non-ascii
                'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'Å', 'Ā', 'Ą',  // @ignore-non-ascii
                'Α', 'Ά', 'Ἀ', 'Ἁ', 'Ἂ', 'Ἃ', 'Ἄ', 'Ἅ', 'Ἆ', 'Ἇ',  // @ignore-non-ascii
                'ᾈ', 'ᾉ', 'ᾊ', 'ᾋ', 'ᾌ', 'ᾍ', 'ᾎ', 'ᾏ', 'Ᾰ', 'Ᾱ',  // @ignore-non-ascii
                'Ὰ', 'Ά', 'ᾼ', 'А', 'Ǻ', 'Ǎ', 'Ａ', 'Ä'],  // @ignore-non-ascii
        'B' => ['Б', 'Β', 'ब', 'Ｂ'],  // @ignore-non-ascii
        'C' => ['Ç', 'Ć', 'Č', 'Ĉ', 'Ċ', 'Ｃ'],  // @ignore-non-ascii
        'D' => ['Ď', 'Ð', 'Đ', 'Ɖ', 'Ɗ', 'Ƌ', 'ᴅ', 'ᴆ', 'Д', 'Δ',  // @ignore-non-ascii
                'Ｄ'],  // @ignore-non-ascii
        'E' => ['É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ',  // @ignore-non-ascii
                'Ệ', 'Ë', 'Ē', 'Ę', 'Ě', 'Ĕ', 'Ė', 'Ε', 'Έ', 'Ἐ',  // @ignore-non-ascii
                'Ἑ', 'Ἒ', 'Ἓ', 'Ἔ', 'Ἕ', 'Έ', 'Ὲ', 'Е', 'Ё', 'Э',  // @ignore-non-ascii
                'Є', 'Ə', 'Ｅ'],  // @ignore-non-ascii
        'F' => ['Ф', 'Φ', 'Ｆ'],  // @ignore-non-ascii
        'G' => ['Ğ', 'Ġ', 'Ģ', 'Г', 'Ґ', 'Γ', 'Ｇ'],  // @ignore-non-ascii
        'H' => ['Η', 'Ή', 'Ħ', 'Ｈ'],  // @ignore-non-ascii
        'I' => ['Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị', 'Î', 'Ï', 'Ī', 'Ĭ', 'Į',  // @ignore-non-ascii
                'İ', 'Ι', 'Ί', 'Ϊ', 'Ἰ', 'Ἱ', 'Ἳ', 'Ἴ', 'Ἵ', 'Ἶ',  // @ignore-non-ascii
                'Ἷ', 'Ῐ', 'Ῑ', 'Ὶ', 'Ί', 'И', 'І', 'Ї', 'Ǐ', 'ϒ',  // @ignore-non-ascii
                'Ｉ'],  // @ignore-non-ascii
        'J' => ['Ｊ'],  // @ignore-non-ascii
        'K' => ['К', 'Κ', 'Ｋ'],  // @ignore-non-ascii
        'L' => ['Ĺ', 'Ł', 'Л', 'Λ', 'Ļ', 'Ľ', 'Ŀ', 'ल', 'Ｌ'],  // @ignore-non-ascii
        'M' => ['М', 'Μ', 'Ｍ'],  // @ignore-non-ascii
        'N' => ['Ń', 'Ñ', 'Ň', 'Ņ', 'Ŋ', 'Н', 'Ν', 'Ｎ'],  // @ignore-non-ascii
        'O' => ['Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ',  // @ignore-non-ascii
                'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'Ø', 'Ō', 'Ő',  // @ignore-non-ascii
                'Ŏ', 'Ο', 'Ό', 'Ὀ', 'Ὁ', 'Ὂ', 'Ὃ', 'Ὄ', 'Ὅ', 'Ὸ',  // @ignore-non-ascii
                'Ό', 'О', 'Θ', 'Ө', 'Ǒ', 'Ǿ', 'Ｏ', 'Ö'],  // @ignore-non-ascii
        'P' => ['П', 'Π', 'Ｐ'],  // @ignore-non-ascii
        'Q' => ['Ｑ'],  // @ignore-non-ascii
        'R' => ['Ř', 'Ŕ', 'Р', 'Ρ', 'Ŗ', 'Ｒ'],  // @ignore-non-ascii
        'S' => ['Ş', 'Ŝ', 'Ș', 'Š', 'Ś', 'С', 'Σ', 'Ｓ'],  // @ignore-non-ascii
        'T' => ['Ť', 'Ţ', 'Ŧ', 'Ț', 'Т', 'Τ', 'Ｔ'],  // @ignore-non-ascii
        'U' => ['Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ',  // @ignore-non-ascii
                'Ự', 'Û', 'Ū', 'Ů', 'Ű', 'Ŭ', 'Ų', 'У', 'Ǔ', 'Ǖ',  // @ignore-non-ascii
                'Ǘ', 'Ǚ', 'Ǜ', 'Ｕ', 'Ў', 'Ü'],  // @ignore-non-ascii
        'V' => ['В', 'Ｖ'],  // @ignore-non-ascii
        'W' => ['Ω', 'Ώ', 'Ŵ', 'Ｗ'],  // @ignore-non-ascii
        'X' => ['Χ', 'Ξ', 'Ｘ'],  // @ignore-non-ascii
        'Y' => ['Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ', 'Ÿ', 'Ῠ', 'Ῡ', 'Ὺ', 'Ύ',  // @ignore-non-ascii
                'Ы', 'Й', 'Υ', 'Ϋ', 'Ŷ', 'Ｙ'],  // @ignore-non-ascii
        'Z' => ['Ź', 'Ž', 'Ż', 'З', 'Ζ', 'Ｚ'],  // @ignore-non-ascii
        'AE' => ['Æ', 'Ǽ'],  // @ignore-non-ascii
        'Ch' => ['Ч'],  // @ignore-non-ascii
        'Dj' => ['Ђ'],  // @ignore-non-ascii
        'Dz' => ['Џ'],  // @ignore-non-ascii
        'Gx' => ['Ĝ'],  // @ignore-non-ascii
        'Hx' => ['Ĥ'],  // @ignore-non-ascii
        'Ij' => ['Ĳ'],  // @ignore-non-ascii
        'Jx' => ['Ĵ'],  // @ignore-non-ascii
        'Kh' => ['Х'],  // @ignore-non-ascii
        'Lj' => ['Љ'],  // @ignore-non-ascii
        'Nj' => ['Њ'],  // @ignore-non-ascii
        'Oe' => ['Œ'],  // @ignore-non-ascii
        'Ps' => ['Ψ'],  // @ignore-non-ascii
        'Sh' => ['Ш'],  // @ignore-non-ascii
        'Shch' => ['Щ'],  // @ignore-non-ascii
        'Ss' => ['ẞ'],  // @ignore-non-ascii
        'Th' => ['Þ'],  // @ignore-non-ascii
        'Ts' => ['Ц'],  // @ignore-non-ascii
        'Ya' => ['Я'],  // @ignore-non-ascii
        'Yu' => ['Ю'],  // @ignore-non-ascii
        'Zh' => ['Ж'],  // @ignore-non-ascii
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
     *
     * @var array<string,array<list<string>>>
     */
    protected const LanguageAsciiChars = [
        'de' => [
            ['ä',  'ö',  'ü',  'Ä',  'Ö',  'Ü' ], // @ignore-non-ascii
            ['ae', 'oe', 'ue', 'AE', 'OE', 'UE'], // @ignore-non-ascii
        ],
        'bg' => [
            ['х', 'Х', 'щ', 'Щ', 'ъ', 'Ъ', 'ь', 'Ь'], // @ignore-non-ascii
            ['h', 'H', 'sht', 'SHT', 'a', 'А', 'y', 'Y'] // @ignore-non-ascii
        ]
    ];


    /**
     * Normalize encoding
     */
    protected function normalizeEncoding(
        Text|string|Stringable|null $text,
        ?string $encoding
    ): ?string {
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


    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);

        $text = mb_convert_encoding(
            $this->text,
            'UTF-8',
            $this->encoding
        );

        if ($text === false) {
            $text = $this->text;
        }

        $entity->text = $text;

        $entity->meta['encoding'] = $this->encoding;
        return $entity;
    }
}
