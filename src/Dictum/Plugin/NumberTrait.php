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
     */
    protected function expandStringUnitValue(
        int|float|string|null &$value,
        ?string &$unit = null
    ): void {
        if (
            $unit === null &&
            is_string($value) &&
            false !== strpos($value, ' ')
        ) {
            list($value, $unit) = explode(' ', $value, 2);
        }
    }

    /**
     * Format raw decimal
     */
    protected function formatRawDecimal(
        int|float $value,
        ?int $precision,
        string $locale
    ): string {
        $formatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);

        if ($precision !== null) {
            $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $precision);
        }

        return $this->normalizeFormatterOutput(
            $formatter,
            $formatter->format($value)
        );
    }

    /**
     * Format raw decimal
     */
    protected function formatRawPatternDecimal(
        int|float $value,
        string $pattern,
        string $locale
    ): string {
        $formatter = new NumberFormatter($locale, NumberFormatter::PATTERN_DECIMAL, $pattern);

        return $this->normalizeFormatterOutput(
            $formatter,
            $formatter->format($value)
        );
    }

    /**
     * Format raw currency
     */
    protected function formatRawCurrency(
        int|float $value,
        ?string $code,
        ?bool $rounded,
        string $locale
    ): string {
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

        return $this->normalizeFormatterOutput(
            $formatter,
            $formatter->formatCurrency($value, $code)
        );
    }

    /**
     * Format raw percent
     */
    protected function formatRawPercent(
        int|float $value,
        float $total,
        int $decimals,
        string $locale
    ): string {
        $formatter = new NumberFormatter($locale, NumberFormatter::PERCENT);
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $decimals);

        return $this->normalizeFormatterOutput(
            $formatter,
            $formatter->format($value / $total)
        );
    }

    /**
     * Format raw scientific
     */
    protected function formatRawScientific(
        int|float $value,
        string $locale
    ): string {
        $formatter = new NumberFormatter($locale, NumberFormatter::SCIENTIFIC);

        return $this->normalizeFormatterOutput(
            $formatter,
            $formatter->format($value)
        );
    }

    /**
     * Format raw spellout
     */
    protected function formatRawSpellout(
        int|float $value,
        string $locale
    ): string {
        $formatter = new NumberFormatter($locale, NumberFormatter::SPELLOUT);

        return $this->normalizeFormatterOutput(
            $formatter,
            $formatter->format($value)
        );
    }

    /**
     * Format raw ordinal
     */
    protected function formatRawOrdinal(
        int|float $value,
        string $locale
    ): string {
        $formatter = new NumberFormatter($locale, NumberFormatter::ORDINAL);

        return $this->normalizeFormatterOutput(
            $formatter,
            $formatter->format($value)
        );
    }


    /**
     * Get unicode arrow for diff
     */
    protected function getDiffArrow(float $diff): string
    {
        if ($diff > 0) {
            return '⬆'; // @ignore-non-ascii
        } elseif ($diff < 0) {
            return '⬇'; // @ignore-non-ascii
        } else {
            return '⬌'; // @ignore-non-ascii
        }
    }


    /**
     * Format raw filesize
     */
    protected function formatRawFileSize(
        int $bytes,
        string $locale
    ): string {
        static $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1024 && $i < 6; $i++) {
            $bytes /= 1024;
        }

        return $this->formatRawDecimal($bytes, 2, $locale) . ' ' . $units[$i];
    }

    /**
     * Format raw decimal filesize
     */
    protected function formatRawFileSizeDec(
        int $bytes,
        string $locale
    ): string {
        static $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1000 && $i < 6; $i++) {
            $bytes /= 1000;
        }

        return $this->formatRawDecimal($bytes, 2, $locale) . ' ' . $units[$i];
    }



    /**
     * Format counter
     */
    public function counter(
        int|float|string|null $counter,
        bool $allowZero = false,
        ?string $locale = null
    ): mixed {
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
     */
    protected function normalizeNumeric(
        int|float|string|null $value,
        bool $checkInt = false
    ): int|float|null {
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

    /**
     * Normalize formatter output
     */
    protected function normalizeFormatterOutput(
        NumberFormatter $formatter,
        string|bool $output
    ): string {
        if (intl_is_failure($formatter->getErrorCode())) {
            throw Exceptional::Runtime('INTL failure: ' . $formatter->getErrorMessage(), null, $output);
        }

        if ($output === false) {
            throw Exceptional::Runtime('INTL failure: unknown error', null, $output);
        }

        return (string)$output;
    }
}
