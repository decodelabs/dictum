<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum\Plugin;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;

use DateInterval;
use DateTime;
use DateTimeZone;

use DecodeLabs\Exceptional;

use IntlDateFormatter;
use Locale;
use Stringable;

/**
 * @template TReturn
 */
trait TimeTrait
{
    use SystemicProxyTrait;

    /**
     * Format raw ICU date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     */
    protected function formatRawIcuDate(
        &$date,
        string $format,
        $timezone = true,
        ?string $locale = null
    ): ?string {
        if (!$date = $this->prepare($date, $timezone, true)) {
            return null;
        }

        $formatter = new IntlDateFormatter(
            $this->getLocale($locale),
            $this->normalizeLocaleSize('full'),
            $this->normalizeLocaleSize('full'),
            $date->getTimezone(),
            null,
            $format
        );

        return (string)$formatter->format($date);
    }


    /**
     * Format raw locale date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param string|int|bool|null $dateSize
     * @param string|int|bool|null $timeSize
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     */
    protected function formatRawLocaleDate(
        &$date,
        $dateSize = true,
        $timeSize = true,
        $timezone = true,
        ?string &$locale = null,
        ?string &$wrapFormat = null
    ): ?string {
        $dateSize = $this->normalizeLocaleSize($dateSize);
        $timeSize = $this->normalizeLocaleSize($timeSize);

        $hasDate = $dateSize !== IntlDateFormatter::NONE;
        $hasTime = ($timeSize !== IntlDateFormatter::NONE) && ($timezone !== false);

        if (!$hasDate && !$hasTime) {
            return null;
        }

        if ($hasDate && $hasTime) {
            $wrapFormat = DateTime::W3C;
        } elseif ($hasDate) {
            $wrapFormat = 'Y-m-d';
        } elseif ($hasTime) {
            $wrapFormat = 'H:i:s';
        } else {
            $wrapFormat = '';
        }

        if (!$date = $this->prepare($date, $timezone, $hasTime)) {
            return null;
        }

        $formatter = new IntlDateFormatter(
            $this->getLocale($locale),
            $dateSize,
            $timeSize,
            $date->getTimezone()
        );

        return (string)$formatter->format($date);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function fullDateTime(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, 'full', 'full', $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function fullDate(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, 'full', false, $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function fullTime(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, false, 'full', $timezone, $locale);
    }


    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function longDateTime(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, 'long', 'long', $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function longDate(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, 'long', false, $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function longTime(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, false, 'long', $timezone, $locale);
    }


    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function mediumDateTime(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, 'medium', 'medium', $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function mediumDate(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, 'medium', false, $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function mediumTime(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, false, 'medium', $timezone, $locale);
    }


    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function shortDateTime(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, 'short', 'short', $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function shortDate(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, 'short', false, $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function shortTime(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, false, 'short', $timezone, $locale);
    }




    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function dateTime(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, 'medium', 'medium', $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function date(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, 'medium', false, $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @return TReturn|null
     */
    public function time(
        $date,
        $timezone = true,
        ?string $locale = null
    ) {
        return $this->locale($date, false, 'short', $timezone, $locale);
    }





    /**
     * Format interval since date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function since(
        $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ) {
        return $this->formatNowInterval($date, false, $parts, false, false, $positive, $locale);
    }

    /**
     * Format interval since date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function sinceAbs(
        $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ) {
        return $this->formatNowInterval($date, false, $parts, false, true, $positive, $locale);
    }

    /**
     * Format interval since date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function sinceAbbr(
        $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ) {
        return $this->formatNowInterval($date, false, $parts, true, true, $positive, $locale);
    }

    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function until(
        $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ) {
        return $this->formatNowInterval($date, true, $parts, false, false, $positive, $locale);
    }

    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function untilAbs(
        $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ) {
        return $this->formatNowInterval($date, true, $parts, false, true, $positive, $locale);
    }

    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    public function untilAbbr(
        $date,
        ?bool $positive = null,
        ?int $parts = 1,
        ?string $locale = null
    ) {
        return $this->formatNowInterval($date, true, $parts, true, true, $positive, $locale);
    }



    /**
     * Format interval
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @return TReturn|null
     */
    protected function formatNowInterval(
        $date,
        bool $invert,
        ?int $parts,
        bool $short = false,
        bool $absolute = false,
        ?bool $positive = false,
        ?string $locale = null
    ) {
        return $this->formatRawNowInterval($date, $interval, $invert, $parts, $short, $absolute, $positive, $locale);
    }


    /**
     * Format interval
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     */
    protected function formatRawNowInterval(
        &$date,
        ?DateInterval &$interval,
        bool $invert,
        ?int $parts,
        bool $short = false,
        bool $absolute = false,
        ?bool $positive = false,
        ?string $locale = null
    ): ?string {
        $this->checkCarbon();

        if ($date instanceof DateInterval) {
            $interval = CarbonInterval::instance($date);
            $interval->invert = (int)!$interval->invert;
            $date = $this->normalizeDate($date);
        } else {
            if (!$date = $this->normalizeDate($date)) {
                return null;
            }

            if (null === ($now = $this->normalizeDate('now'))) {
                throw Exceptional::UnexpectedValue('Unable to create now date');
            }

            if (null === ($interval = CarbonInterval::make($date->diff($now)))) {
                throw Exceptional::UnexpectedValue('Unable to create interval');
            }
        }

        $locale = $this->getLocale($locale);
        $interval->locale($locale);

        if (null === ($interval = CarbonInterval::make($interval))) {
            throw Exceptional::UnexpectedValue('Unable to create interval');
        }

        $inverted = $interval->invert;

        if ($invert) {
            if ($inverted) {
                $absolute = true;
            }

            $inverted = !$inverted;
        }

        return
            ($inverted && $absolute ? '-' : '') .
            $interval->forHumans([
                'short' => $short,
                'join' => true,
                'parts' => $parts,
                'options' => CarbonInterface::JUST_NOW | CarbonInterface::ONE_DAY_WORDS | CarbonInterface::ROUND,
                'syntax' => $absolute ? CarbonInterface::DIFF_ABSOLUTE : CarbonInterface::DIFF_RELATIVE_TO_NOW
            ]);
    }




    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date1
     * @param DateTime|DateInterval|string|Stringable|int|null $date2
     * @return TReturn|null
     */
    public function between(
        $date1,
        $date2,
        ?int $parts = 1,
        ?string $locale = null
    ) {
        return $this->formatBetweenInterval($date1, $date2, $parts, false, $locale);
    }

    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date1
     * @param DateTime|DateInterval|string|Stringable|int|null $date2
     * @return TReturn|null
     */
    public function betweenAbbr(
        $date1,
        $date2,
        ?int $parts = 1,
        ?string $locale = null
    ) {
        return $this->formatBetweenInterval($date1, $date2, $parts, true, $locale);
    }

    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date1
     * @param DateTime|DateInterval|string|Stringable|int|null $date2
     * @return TReturn|null
     */
    protected function formatBetweenInterval(
        $date1,
        $date2,
        ?int $parts = 1,
        bool $short = false,
        ?string $locale = null
    ) {
        return $this->formatRawBetweenInterval($date1, $date2, $interval, $parts, $short, $locale);
    }

    /**
     * Format interval until date
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date1
     * @param DateTime|DateInterval|string|Stringable|int|null $date2
     */
    protected function formatRawBetweenInterval(
        &$date1,
        &$date2,
        ?DateInterval &$interval,
        ?int $parts = 1,
        bool $short = false,
        ?string &$locale = null
    ): ?string {
        $this->checkCarbon();

        if (!$date1 = $this->normalizeDate($date1)) {
            return null;
        }

        if (!$date2 = $this->normalizeDate($date2)) {
            return null;
        }

        if (null === ($interval = CarbonInterval::make($date1->diff($date2)))) {
            throw Exceptional::UnexpectedValue('Unable to create interval');
        }

        $locale = $this->getLocale($locale);
        $interval->locale($locale);

        return
            ($interval->invert ? '-' : '') .
            $interval->forHumans([
                'short' => $short,
                'join' => true,
                'parts' => $parts,
                'options' => CarbonInterface::JUST_NOW | CarbonInterface::ONE_DAY_WORDS | CarbonInterface::ROUND,
                'syntax' => CarbonInterface::DIFF_ABSOLUTE
            ]);
    }




    /**
     * Prepare date for formatting
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     */
    protected function prepare(
        $date,
        $timezone = true,
        bool $includeTime = true
    ): ?DateTime {
        if (null === ($date = $this->normalizeDate($date))) {
            return null;
        }

        if ($timezone === false) {
            $timezone = null;
            //$includeTime = false;
        }

        if ($timezone !== null) {
            $date = clone $date;

            if ($timezone = $this->normalizeTimezone($timezone)) {
                $date->setTimezone($timezone);
            }
        }

        return $date;
    }


    /**
     * Normalize a date input
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     */
    protected function normalizeDate($date): ?DateTime
    {
        if ($date === null) {
            return null;
        } elseif ($date instanceof DateTime) {
            return $date;
        }

        if ($date instanceof DateInterval) {
            $int = $date;

            if (null === ($now = $this->normalizeDate('now'))) {
                throw Exceptional::UnexpectedValue('Unable to create now date');
            }

            return $now->add($int);
        }

        $timestamp = null;

        if (is_numeric($date)) {
            $timestamp = $date;
            $date = 'now';
        }

        $date = new DateTime((string)$date);

        if ($timestamp !== null) {
            $date->setTimestamp((int)$timestamp);
        }

        return $date;
    }

    /**
     * Normalize timezone
     *
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     */
    protected function normalizeTimezone($timezone): ?DateTimeZone
    {
        if ($timezone === false || $timezone === null) {
            return null;
        }

        if ($timezone === true) {
            $timezone = $this->getTimezone();
        }

        if ($timezone instanceof DateTimeZone) {
            return $timezone;
        }

        return new DateTimeZone((string)$timezone);
    }

    /**
     * Normalize locale format size
     *
     * @param string|int|bool|null $size
     */
    protected function normalizeLocaleSize($size): int
    {
        if ($size === false || $size === null) {
            return IntlDateFormatter::NONE;
        }

        if ($size === true) {
            return IntlDateFormatter::LONG;
        }

        switch ($size) {
            case 'full':
                return IntlDateFormatter::FULL;

            case 'long':
                return IntlDateFormatter::LONG;

            case 'medium':
                return IntlDateFormatter::MEDIUM;

            case 'short':
                return IntlDateFormatter::SHORT;

            case IntlDateFormatter::FULL:
            case IntlDateFormatter::LONG:
            case IntlDateFormatter::MEDIUM:
            case IntlDateFormatter::SHORT:
                return (int)$size;

            default:
                throw Exceptional::InvalidArgument(
                    'Invalid locale formatter size: ' . $size
                );
        }
    }

    /**
     * Check Carbon installed
     */
    protected function checkCarbon(): void
    {
        if (!class_exists(Carbon::class)) {
            throw Exceptional::ComponentUnavailable(
                'nesbot/carbon is required for formatting intervals'
            );
        }
    }
}
