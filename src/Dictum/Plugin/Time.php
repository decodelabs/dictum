<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum\Plugin;

use DateInterval;
use DateTime;
use DateTimeZone;
use Stringable;

/**
 * @template TReturn
 */
interface Time
{
    /**
     * Custom format a date and wrap it
     *
     * @phpstan-return TReturn|null
     */
    public function format(
        DateTime|DateInterval|string|Stringable|int|null $date,
        string $format,
        DateTimeZone|string|Stringable|bool|null $timezone = true
    ): mixed;

    /**
     * Custom format a date and wrap it
     *
     * @phpstan-return TReturn|null
     */
    public function formatDate(
        DateTime|DateInterval|string|Stringable|int|null $date,
        string $format
    ): mixed;

    /**
     * Custom locale format a date with ICU and wrap it
     *
     * @phpstan-return TReturn|null
     */
    public function pattern(
        DateTime|DateInterval|string|Stringable|int|null $date,
        string $pattern,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format date according to locale
     *
     * @phpstan-return TReturn|null
     */
    public function locale(
        DateTime|DateInterval|string|Stringable|int|null $date,
        string|int|bool|null $dateSize = true,
        string|int|bool|null $timeSize = true,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format full date time
     *
     * @phpstan-return TReturn|null
     */
    public function fullDateTime(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format full date
     *
     * @phpstan-return TReturn|null
     */
    public function fullDate(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format full time
     *
     * @phpstan-return TReturn|null
     */
    public function fullTime(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;


    /**
     * Format long date time
     *
     * @phpstan-return TReturn|null
     */
    public function longDateTime(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format long date
     *
     * @phpstan-return TReturn|null
     */
    public function longDate(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format long time
     *
     * @phpstan-return TReturn|null
     */
    public function longTime(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;


    /**
     * Format medium date time
     *
     * @phpstan-return TReturn|null
     */
    public function mediumDateTime(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format medium date
     *
     * @phpstan-return TReturn|null
     */
    public function mediumDate(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format medium time
     *
     * @phpstan-return TReturn|null
     */
    public function mediumTime(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;


    /**
     * Format short date time
     *
     * @phpstan-return TReturn|null
     */
    public function shortDateTime(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format short date
     *
     * @phpstan-return TReturn|null
     */
    public function shortDate(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format short time
     *
     * @phpstan-return TReturn|null
     */
    public function shortTime(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;




    /**
     * Format default date time
     *
     * @phpstan-return TReturn|null
     */
    public function dateTime(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format default date
     *
     * @phpstan-return TReturn|null
     */
    public function date(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;

    /**
     * Format default time
     *
     * @phpstan-return TReturn|null
     */
    public function time(
        DateTime|DateInterval|string|Stringable|int|null $date,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        ?string $locale = null
    ): mixed;




    /**
     * Format interval since date
     *
     * @phpstan-return TReturn|null
     */
    public function since(
        DateTime|DateInterval|string|Stringable|int|null $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ): mixed;

    /**
     * Format interval since date
     *
     * @phpstan-return TReturn|null
     */
    public function sinceAbs(
        DateTime|DateInterval|string|Stringable|int|null $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ): mixed;

    /**
     * Format interval since date
     *
     * @phpstan-return TReturn|null
     */
    public function sinceAbbr(
        DateTime|DateInterval|string|Stringable|int|null $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ): mixed;

    /**
     * Format interval until date
     *
     * @phpstan-return TReturn|null
     */
    public function until(
        DateTime|DateInterval|string|Stringable|int|null $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ): mixed;

    /**
     * Format interval until date
     *
     * @phpstan-return TReturn|null
     */
    public function untilAbs(
        DateTime|DateInterval|string|Stringable|int|null $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ): mixed;

    /**
     * Format interval until date
     *
     * @phpstan-return TReturn|null
     */
    public function untilAbbr(
        DateTime|DateInterval|string|Stringable|int|null $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ): mixed;


    /**
     * Format interval until date
     *
     * @phpstan-return TReturn|null
     */
    public function between(
        DateTime|DateInterval|string|Stringable|int|null $date1,
        DateTime|DateInterval|string|Stringable|int|null $date2,
        ?int $parts = 1,
        ?string $locale = null
    ): mixed;

    /**
     * Format interval until date
     *
     * @phpstan-return TReturn|null
     */
    public function betweenAbbr(
        DateTime|DateInterval|string|Stringable|int|null $date1,
        DateTime|DateInterval|string|Stringable|int|null $date2,
        ?int $parts = 1,
        ?string $locale = null
    ): mixed;
}
