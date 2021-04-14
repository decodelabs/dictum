<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum\Plugin;

use DecodeLabs\Veneer\Plugin;

use Locale;

/**
 * @template TReturn
 */
interface Number extends Plugin
{
    /**
     * @param int|float|string|null $value
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function format($value, ?string $unit = null, $locale = null);

    /**
     * @param int|float|string|null $value
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function pattern($value, string $pattern, $locale = null);

    /**
     * @param int|float|string|null $value
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function decimal($value, ?int $precision = null, $locale = null);

    /**
     * @param int|float|string|null $value
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function currency($value, ?string $code, ?bool $rounded = null, $locale = null);

    /**
     * @param int|float|string|null $value
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function percent($value, float $total = 100.0, int $decimals = 0, $locale = null);

    /**
     * @param int|float|string|null $value
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function scientific($value, $locale = null);

    /**
     * @param int|float|string|null $value
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function spellout($value, $locale = null);

    /**
     * @param int|float|string|null $value
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function ordinal($value, $locale = null);

    /**
     * @param int|float|string|null $diff
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function diff($diff, ?bool $invert = false, $locale = null);

    /**
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function fileSize(?int $bytes, $locale = null);

    /**
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function fileSizeDec(?int $bytes, $locale = null);
}
