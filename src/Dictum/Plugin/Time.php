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
use DecodeLabs\Veneer\Plugin;
use Locale;
use Stringable;

/**
 * @template TReturn
 */
interface Time extends Plugin
{
    /**
     * Custom format a date and wrap it
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function format($date, string $format, $timezone = true);

    /**
     * Custom format a date and wrap it
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function formatDate($date, string $format);

    /**
     * Format date according to locale
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param string|int|bool|null $dateSize
     * @param string|int|bool|null $timeSize
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function locale($date, $dateSize = true, $timeSize = true, $timezone = true, ?string $locale = null);

    /**
     * Format full date time
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function fullDateTime($date, $timezone = true, ?string $locale = null);

    /**
     * Format full date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function fullDate($date, $timezone = true, ?string $locale = null);

    /**
     * Format full time
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function fullTime($date, $timezone = true, ?string $locale = null);


    /**
     * Format long date time
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function longDateTime($date, $timezone = true, ?string $locale = null);

    /**
     * Format long date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function longDate($date, $timezone = true, ?string $locale = null);

    /**
     * Format long time
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function longTime($date, $timezone = true, ?string $locale = null);


    /**
     * Format medium date time
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function mediumDateTime($date, $timezone = true, ?string $locale = null);

    /**
     * Format medium date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function mediumDate($date, $timezone = true, ?string $locale = null);

    /**
     * Format medium time
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function mediumTime($date, $timezone = true, ?string $locale = null);


    /**
     * Format short date time
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function shortDateTime($date, $timezone = true, ?string $locale = null);

    /**
     * Format short date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function shortDate($date, $timezone = true, ?string $locale = null);

    /**
     * Format short time
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function shortTime($date, $timezone = true, ?string $locale = null);




    /**
     * Format default date time
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function dateTime($date, $timezone = true, ?string $locale = null);

    /**
     * Format default date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function date($date, $timezone = true, ?string $locale = null);

    /**
     * Format default time
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function time($date, $timezone = true, ?string $locale = null);




    /**
     * Format interval since date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function since($date, ?bool $positive = null, ?int $parts = 1, ?string $locale = null);

    /**
     * Format interval since date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function sinceAbs($date, ?bool $positive = null, ?int $parts = 1, ?string $locale = null);

    /**
     * Format interval since date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function sinceAbbr($date, ?bool $positive = null, ?int $parts = 1, ?string $locale = null);

    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function until($date, ?bool $positive = null, ?int $parts = 1, ?string $locale = null);

    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function untilAbs($date, ?bool $positive = null, ?int $parts = 1, ?string $locale = null);

    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function untilAbbr($date, ?bool $positive = null, ?int $parts = 1, ?string $locale = null);


    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date1
     * @param DateTime|DateInterval|string|Stringable|int|null $date2
     * @return TReturn|null
     */
    public function between($date1, $date2, ?int $parts = 1, ?string $locale = null);

    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date1
     * @param DateTime|DateInterval|string|Stringable|int|null $date2
     * @return TReturn|null
     */
    public function betweenAbbr($date1, $date2, ?int $parts = 1, ?string $locale = null);
}
