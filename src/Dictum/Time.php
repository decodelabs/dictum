<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum;

use DateInterval;
use DateTimeInterface;
use DateTimeZone;
use DecodeLabs\Cosmos\Extension\Time as TimeExtension;
use DecodeLabs\Cosmos\Extension\TimeTrait as TimeExtensionTrait;
use DecodeLabs\Cosmos\Locale;
use Stringable;

/**
 * @implements TimeExtension<string>
 */
class Time implements TimeExtension
{
    /**
     * @use TimeExtensionTrait<string>
     */
    use TimeExtensionTrait;


    /**
     * Custom format a date
     */
    public static function format(
        DateTimeInterface|DateInterval|string|Stringable|int|null $date,
        string $format,
        DateTimeZone|string|Stringable|bool|null $timezone = true
    ): ?string {
        if (!$date = static::prepare($date, $timezone, true)) {
            return null;
        }

        return $date->format($format);
    }

    /**
     * Custom format a date without time
     */
    public static function formatDate(
        DateTimeInterface|DateInterval|string|Stringable|int|null $date,
        string $format
    ): ?string {
        if (!$date = static::prepare($date, false, true)) {
            return null;
        }

        return $date->format($format);
    }

    /**
     * Custom locale format a date with ICU and wrap it
     */
    public static function pattern(
        DateTimeInterface|DateInterval|string|Stringable|int|null $date,
        string $pattern,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        string|Locale|null $locale = null
    ): ?string {
        return static::formatRawIcuDate($date, $pattern, $timezone, $locale);
    }

    /**
     * Format date according to locale
     */
    public static function locale(
        DateTimeInterface|DateInterval|string|Stringable|int|null $date,
        string|int|bool|null $dateSize = true,
        string|int|bool|null $timeSize = true,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        string|Locale|null $locale = null
    ): ?string {
        return static::formatRawLocaleDate($date, $dateSize, $timeSize, $timezone, $locale);
    }
}
