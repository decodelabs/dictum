<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum;

use DecodeLabs\Cosmos\Extension\Number as NumberExtension;
use DecodeLabs\Cosmos\Extension\NumberTrait as NumberExtensionTrait;
use DecodeLabs\Cosmos\Locale;

/**
 * @implements NumberExtension<string>
 */
class Number implements NumberExtension
{
    /**
     * @use NumberExtensionTrait<string>
     */
    use NumberExtensionTrait;

    /**
     * Format a generic number
     */
    public static function format(
        int|float|string|null $value,
        ?string $unit = null,
        string|Locale|null $locale = null
    ): ?string {
        static::expandStringUnitValue($value, $unit);

        if (null === ($value = static::normalizeNumeric($value, true))) {
            return null;
        }

        $output = static::formatRawDecimal($value, null, $locale);

        if ($unit !== null) {
            $output .= ' ' . $unit;
        }

        return $output;
    }

    /**
     * Format according to pattern and wrap
     */
    public static function pattern(
        int|float|string|null $value,
        string $pattern,
        string|Locale|null $locale = null
    ): ?string {
        if (null === ($value = static::normalizeNumeric($value))) {
            return null;
        }

        return static::formatRawPatternDecimal($value, $pattern, $locale);
    }

    /**
     * Format and render a decimal
     */
    public static function decimal(
        int|float|string|null $value,
        ?int $precision = null,
        string|Locale|null $locale = null
    ): ?string {
        if (null === ($value = static::normalizeNumeric($value))) {
            return null;
        }

        return static::formatRawDecimal($value, $precision, $locale);
    }

    /**
     * Format and wrap currency
     */
    public static function currency(
        int|float|string|null $value,
        ?string $code,
        ?bool $rounded = null,
        string|Locale|null $locale = null
    ): ?string {
        if (
            null === ($value = static::normalizeNumeric($value)) ||
            $code === null
        ) {
            return null;
        }

        return static::formatRawCurrency($value, $code, $rounded, $locale);
    }

    /**
     * Format and render a percentage
     */
    public static function percent(
        int|float|string|null $value,
        float $total = 100.0,
        int $decimals = 0,
        string|Locale|null $locale = null
    ): ?string {
        if (
            null === ($value = static::normalizeNumeric($value, true)) ||
            $total <= 0
        ) {
            return null;
        }

        return static::formatRawPercent($value, $total, $decimals, $locale);
    }

    /**
     * Format and render a scientific number
     */
    public static function scientific(
        int|float|string|null $value,
        string|Locale|null $locale = null
    ): ?string {
        if (null === ($value = static::normalizeNumeric($value))) {
            return null;
        }

        return static::formatRawScientific($value, $locale);
    }

    /**
     * Format and render a number as words
     */
    public static function spellout(
        int|float|string|null $value,
        string|Locale|null $locale = null
    ): ?string {
        if (null === ($value = static::normalizeNumeric($value))) {
            return null;
        }

        return static::formatRawSpellout($value, $locale);
    }

    /**
     * Format and render a number as ordinal
     */
    public static function ordinal(
        int|float|string|null $value,
        string|Locale|null $locale = null
    ): ?string {
        if (null === ($value = static::normalizeNumeric($value))) {
            return null;
        }

        return static::formatRawOrdinal($value, $locale);
    }


    /**
     * Render difference of number from 0
     */
    public static function diff(
        int|float|string|null $diff,
        ?bool $invert = false,
        string|Locale|null $locale = null
    ): ?string {
        if (null === ($diff = static::normalizeNumeric($diff))) {
            return null;
        }

        $diff = (float)$diff;

        if ($invert) {
            $diff *= -1;
        }

        $output = static::getDiffArrow($diff) . ' ';
        $output .= static::format(abs($diff), null, $locale);

        return $output;
    }




    /**
     * Format filesize
     */
    public static function fileSize(
        ?int $bytes,
        string|Locale|null $locale = null
    ): ?string {
        if ($bytes === null) {
            return null;
        }

        return static::formatRawFileSize($bytes, $locale);
    }

    /**
     * Format filesize as decimal
     */
    public static function fileSizeDec(
        ?int $bytes,
        string|Locale|null $locale = null
    ): ?string {
        if ($bytes === null) {
            return null;
        }

        return static::formatRawFileSizeDec($bytes, $locale);
    }
}
