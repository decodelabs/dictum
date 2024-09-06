<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum\Plugins;

use DateInterval;
use DateTimeInterface;
use DateTimeZone;
use DecodeLabs\Cosmos\Extension\Time as TimePlugin;
use DecodeLabs\Cosmos\Extension\TimeTrait as TimePluginTrait;
use DecodeLabs\Cosmos\Locale;
use DecodeLabs\Dictum\Context;
use Stringable;

/**
 * @implements TimePlugin<string>
 */
class Time implements TimePlugin
{
    /**
     * @use TimePluginTrait<string>
     */
    use TimePluginTrait;

    protected Context $context;

    /**
     * Init with parent Context
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }


    /**
     * Custom format a date
     */
    public function format(
        DateTimeInterface|DateInterval|string|Stringable|int|null $date,
        string $format,
        DateTimeZone|string|Stringable|bool|null $timezone = true
    ): ?string {
        if (!$date = $this->prepare($date, $timezone, true)) {
            return null;
        }

        return $date->format($format);
    }

    /**
     * Custom format a date without time
     */
    public function formatDate(
        DateTimeInterface|DateInterval|string|Stringable|int|null $date,
        string $format
    ): ?string {
        if (!$date = $this->prepare($date, false, true)) {
            return null;
        }

        return $date->format($format);
    }

    /**
     * Custom locale format a date with ICU and wrap it
     */
    public function pattern(
        DateTimeInterface|DateInterval|string|Stringable|int|null $date,
        string $pattern,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        string|Locale|null $locale = null
    ): ?string {
        return $this->formatRawIcuDate($date, $pattern, $timezone, $locale);
    }

    /**
     * Format date according to locale
     */
    public function locale(
        DateTimeInterface|DateInterval|string|Stringable|int|null $date,
        string|int|bool|null $dateSize = true,
        string|int|bool|null $timeSize = true,
        DateTimeZone|string|Stringable|bool|null $timezone = true,
        string|Locale|null $locale = null
    ): ?string {
        return $this->formatRawLocaleDate($date, $dateSize, $timeSize, $timezone, $locale);
    }
}
