<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum\Plugin;

use DecodeLabs\Veneer\Plugin;

/**
 * @template TReturn
 */
interface Number extends Plugin
{
    /**
     * @param int|float|string|null $value
     * @return TReturn|null
     */
    public function format($value, ?string $unit = null, ?string $locale = null);

    /**
     * @param int|float|string|null $value
     * @return TReturn|null
     */
    public function pattern($value, string $pattern, ?string $locale = null);

    /**
     * @param int|float|string|null $value
     * @return TReturn|null
     */
    public function decimal($value, ?int $precision = null, ?string $locale = null);

    /**
     * @param int|float|string|null $value
     * @return TReturn|null
     */
    public function currency($value, ?string $code, ?bool $rounded = null, ?string $locale = null);

    /**
     * @param int|float|string|null $value
     * @return TReturn|null
     */
    public function percent($value, float $total = 100.0, int $decimals = 0, ?string $locale = null);

    /**
     * @param int|float|string|null $value
     * @return TReturn|null
     */
    public function scientific($value, ?string $locale = null);

    /**
     * @param int|float|string|null $value
     * @return TReturn|null
     */
    public function spellout($value, ?string $locale = null);

    /**
     * @param int|float|string|null $value
     * @return TReturn|null
     */
    public function ordinal($value, ?string $locale = null);

    /**
     * @param int|float|string|null $diff
     * @return TReturn|null
     */
    public function diff($diff, ?bool $invert = false, ?string $locale = null);

    /**
     * @return TReturn|null
     */
    public function fileSize(?int $bytes, ?string $locale = null);

    /**
     * @return TReturn|null
     */
    public function fileSizeDec(?int $bytes, ?string $locale = null);

    /**
     * @param int|float|string|null $counter
     * @return TReturn|null
     */
    public function counter($counter, bool $allowZero = false, ?string $locale = null);
}
