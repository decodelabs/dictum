<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum\Plugin;

/**
 * @template TReturn
 */
interface Number
{
    /**
     * @phpstan-return TReturn|null
     */
    public function format(
        int|float|string|null $value,
        ?string $unit = null,
        ?string $locale = null
    ): mixed;

    /**
     * @phpstan-return TReturn|null
     */
    public function pattern(
        int|float|string|null $value,
        string $pattern,
        ?string $locale = null
    ): mixed;

    /**
     * @phpstan-return TReturn|null
     */
    public function decimal(
        int|float|string|null $value,
        ?int $precision = null,
        ?string $locale = null
    ): mixed;

    /**
     * @phpstan-return TReturn|null
     */
    public function currency(
        int|float|string|null $value,
        ?string $code,
        ?bool $rounded = null,
        ?string $locale = null
    ): mixed;

    /**
     * @phpstan-return TReturn|null
     */
    public function percent(
        int|float|string|null $value,
        float $total = 100.0,
        int $decimals = 0,
        ?string $locale = null
    ): mixed;

    /**
     * @phpstan-return TReturn|null
     */
    public function scientific(
        int|float|string|null $value,
        ?string $locale = null
    ): mixed;

    /**
     * @phpstan-return TReturn|null
     */
    public function spellout(
        int|float|string|null $value,
        ?string $locale = null
    ): mixed;

    /**
     * @phpstan-return TReturn|null
     */
    public function ordinal(
        int|float|string|null $value,
        ?string $locale = null
    ): mixed;

    /**
     * @phpstan-return TReturn|null
     */
    public function diff(
        int|float|string|null $diff,
        ?bool $invert = false,
        ?string $locale = null
    ): mixed;

    /**
     * @phpstan-return TReturn|null
     */
    public function fileSize(
        ?int $bytes,
        ?string $locale = null
    ): mixed;

    /**
     * @phpstan-return TReturn|null
     */
    public function fileSizeDec(
        ?int $bytes,
        ?string $locale = null
    ): mixed;

    /**
     * @phpstan-return TReturn|null
     */
    public function counter(
        int|float|string|null $counter,
        bool $allowZero = false,
        ?string $locale = null
    ): mixed;
}
