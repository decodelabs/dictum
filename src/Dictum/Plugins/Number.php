<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum\Plugins;

use DecodeLabs\Dictum\Context;
use DecodeLabs\Dictum\Plugin\Number as NumberPlugin;
use DecodeLabs\Dictum\Plugin\NumberTrait as NumberPluginTrait;

/**
 * @implements NumberPlugin<string>
 */
class Number implements NumberPlugin
{
    /**
     * @use NumberPluginTrait<string>
     */
    use NumberPluginTrait;

    protected Context $context;

    /**
     * Init with parent Context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Format a generic number
     */
    public function format(
        int|float|string|null $value,
        ?string $unit = null,
        ?string $locale = null
    ): ?string {
        $this->expandStringUnitValue($value, $unit);

        if (null === ($value = $this->normalizeNumeric($value, true))) {
            return null;
        }

        $output = $this->formatRawDecimal($value, null, $this->getLocale($locale));

        if ($unit !== null) {
            $output .= ' ' . $unit;
        }

        return $output;
    }

    /**
     * Format according to pattern and wrap
     */
    public function pattern(
        int|float|string|null $value,
        string $pattern,
        ?string $locale = null
    ): ?string {
        if (null === ($value = $this->normalizeNumeric($value))) {
            return null;
        }

        return $this->formatRawPatternDecimal($value, $pattern, $this->getLocale($locale));
    }

    /**
     * Format and render a decimal
     */
    public function decimal(
        int|float|string|null $value,
        ?int $precision = null,
        ?string $locale = null
    ): ?string {
        if (null === ($value = $this->normalizeNumeric($value))) {
            return null;
        }

        return $this->formatRawDecimal($value, $precision, $this->getLocale($locale));
    }

    /**
     * Format and wrap currency
     */
    public function currency(
        int|float|string|null $value,
        ?string $code,
        ?bool $rounded = null,
        ?string $locale = null
    ): ?string {
        if (
            null === ($value = $this->normalizeNumeric($value)) ||
            $code === null
        ) {
            return null;
        }

        return $this->formatRawCurrency($value, $code, $rounded, $this->getLocale($locale));
    }

    /**
     * Format and render a percentage
     */
    public function percent(
        int|float|string|null $value,
        float $total = 100.0,
        int $decimals = 0,
        ?string $locale = null
    ): ?string {
        if (
            null === ($value = $this->normalizeNumeric($value, true)) ||
            $total <= 0
        ) {
            return null;
        }

        return $this->formatRawPercent($value, $total, $decimals, $this->getLocale($locale));
    }

    /**
     * Format and render a scientific number
     */
    public function scientific(
        int|float|string|null $value,
        ?string $locale = null
    ): ?string {
        if (null === ($value = $this->normalizeNumeric($value))) {
            return null;
        }

        return $this->formatRawScientific($value, $this->getLocale($locale));
    }

    /**
     * Format and render a number as words
     */
    public function spellout(
        int|float|string|null $value,
        ?string $locale = null
    ): ?string {
        if (null === ($value = $this->normalizeNumeric($value))) {
            return null;
        }

        return $this->formatRawSpellout($value, $this->getLocale($locale));
    }

    /**
     * Format and render a number as ordinal
     */
    public function ordinal(
        int|float|string|null $value,
        ?string $locale = null
    ): ?string {
        if (null === ($value = $this->normalizeNumeric($value))) {
            return null;
        }

        return $this->formatRawOrdinal($value, $this->getLocale($locale));
    }


    /**
     * Render difference of number from 0
     */
    public function diff(
        int|float|string|null $diff,
        ?bool $invert = false,
        ?string $locale = null
    ): ?string {
        if (null === ($diff = $this->normalizeNumeric($diff))) {
            return null;
        }

        $diff = (float)$diff;

        if ($invert) {
            $diff *= -1;
        }

        $output = $this->getDiffArrow($diff) . ' ';
        $output .= $this->format(abs($diff), null, $locale);

        return $output;
    }




    /**
     * Format filesize
     */
    public function fileSize(
        ?int $bytes,
        ?string $locale = null
    ): ?string {
        if ($bytes === null) {
            return null;
        }

        return $this->formatRawFileSize($bytes, $this->getLocale($locale));
    }

    /**
     * Format filesize as decimal
     */
    public function fileSizeDec(
        ?int $bytes,
        ?string $locale = null
    ): ?string {
        if ($bytes === null) {
            return null;
        }

        return $this->formatRawFileSizeDec($bytes, $this->getLocale($locale));
    }
}
