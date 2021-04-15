<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum\Plugin;

use DecodeLabs\Exceptional;
use NumberFormatter;

/**
 * @template TReturn
 */
trait NumberTrait
{
    use SystemicProxyTrait;

    /**
     * Expand string unit value
     *
     * @param int|float|string|null $value
     */
    protected function expandStringUnitValue(&$value, ?string &$unit = null): void
    {
        if ($unit === null && is_string($value) && false !== strpos($value, ' ')) {
            list($value, $unit) = explode(' ', $value, 2);
        }
    }

    /**
     * Format raw decimal
     *
     * @param int|float $value
     */
    protected function formatRawDecimal($value, ?int $precision, string $locale): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);

        if ($precision !== null) {
            $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $precision);
        }

        return $formatter->format($value);
    }

    /**
     * Format raw decimal
     *
     * @param int|float $value
     */
    protected function formatRawPatternDecimal($value, string $pattern, string $locale): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::PATTERN_DECIMAL, $pattern);
        return $formatter->format($value);
    }

    /**
     * Format raw currency
     *
     * @param int|float $value
     */
    protected function formatRawCurrency($value, ?string $code, ?bool $rounded, string $locale): string
    {
        $value = (float)$value;

        if ($code === null) {
            $code = 'GBP';
        }

        $code = strtoupper($code);

        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $formatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, $code);

        if (
            $rounded === true ||
            (
                $rounded === null &&
                (round($value, 0) == round($value, 2))
            )
        ) {
            $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
        }

        return $formatter->formatCurrency($value, $code);
    }

    /**
     * Format raw percent
     *
     * @param int|float $value
     */
    protected function formatRawPercent($value, float $total, int $decimals, string $locale): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::PERCENT);
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $decimals);
        return $formatter->format($value / $total);
    }

    /**
     * Format raw scientific
     *
     * @param int|float $value
     */
    protected function formatRawScientific($value, string $locale): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::SCIENTIFIC);
        return $formatter->format($value);
    }

    /**
     * Format raw spellout
     *
     * @param int|float $value
     */
    protected function formatRawSpellout($value, string $locale): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::SPELLOUT);
        return $formatter->format($value);
    }

    /**
     * Format raw ordinal
     *
     * @param int|float $value
     */
    protected function formatRawOrdinal($value, string $locale): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::ORDINAL);
        return $formatter->format($value);
    }


    /**
     * Get unicode arrow for diff
     */
    protected function getDiffArrow(float $diff): string
    {
        if ($diff > 0) {
            return '⬆';
        } elseif ($diff < 0) {
            return '⬇';
        } else {
            return '⬌';
        }
    }


    /**
     * Format raw filesize
     */
    protected function formatRawFileSize(int $bytes, string $locale): string
    {
        static $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1024 && $i < 6; $i++) {
            $bytes /= 1024;
        }

        return $this->formatRawDecimal($bytes, 2, $locale) . ' ' . $units[$i];
    }

    /**
     * Format raw decimal filesize
     */
    protected function formatRawFileSizeDec(int $bytes, string $locale): string
    {
        static $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1000 && $i < 6; $i++) {
            $bytes /= 1000;
        }

        return $this->formatRawDecimal($bytes, 2, $locale) . ' ' . $units[$i];
    }



    /**
     * Format counter
     */
    public function counter($counter, bool $allowZero=false, ?string $locale = null)
    {
        if (null === ($counter = $this->normalizeNumeric($counter))) {
            return null;
        }

        if ($counter == 0 && !$allowZero) {
            return null;
        }

        if ($counter > 999999999) {
            return $this->format(round($counter / 1000000000, 1), 'b', $locale);
        } elseif ($counter > 999999) {
            return $this->format(round($counter / 1000000, 1), 'm', $locale);
        } elseif ($counter > 9999) {
            return $this->format(round($counter / 1000), 'k', $locale);
        } elseif ($counter > 999) {
            return $this->format(round($counter / 1000, 1), 'k', $locale);
        } else {
            return $this->format($counter, null, $locale);
        }
    }



    /**
     * Normalize numeric value
     *
     * @param int|float|string|null $value
     * @return int|float|null
     */
    protected function normalizeNumeric($value, bool $checkInt = false)
    {
        if ($value === null) {
            return null;
        }

        if (
            is_string($value) &&
            is_numeric($value)
        ) {
            if ($checkInt) {
                $value = $value == (int)$value ?
                    (int)$value : (float)$value;
            } else {
                $value = (float)$value;
            }
        }

        if (
            !is_int($value) &&
            !is_float($value)
        ) {
            throw Exceptional::InvalidArgument('Value is not a number', null, $value);
        }

        return $value;
    }
}
