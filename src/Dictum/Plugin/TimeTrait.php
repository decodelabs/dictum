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

use DecodeLabs\Exceptional;

use IntlDateFormatter;
use Locale;
use Stringable;

/**
 * @template TReturn
 */
trait TimeTrait
{
    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function fullDateTime($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, 'full', 'full', $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function fullDate($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, 'full', false, $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function fullTime($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, false, 'full', $timezone, $locale);
    }


    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function longDateTime($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, 'long', 'long', $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function longDate($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, 'long', false, $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function longTime($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, false, 'long', $timezone, $locale);
    }


    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function mediumDateTime($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, 'medium', 'medium', $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function mediumDate($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, 'medium', false, $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function mediumTime($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, false, 'medium', $timezone, $locale);
    }


    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function shortDateTime($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, 'short', 'short', $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function shortDate($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, 'short', false, $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function shortTime($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, false, 'short', $timezone, $locale);
    }




    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function dateTime($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, 'medium', 'medium', $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function date($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, 'medium', false, $timezone, $locale);
    }

    /**
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     * @param Locale|string|null $locale
     * @return TReturn|null
     */
    public function time($date, $timezone = true, $locale = null)
    {
        return $this->locale($date, false, 'short', $timezone, $locale);
    }




    /**
     * Prepare date for formatting
     *
     * @param DateTime|DateInterval|string|Stringable|int|null $date
     * @param DateTimeZone|string|Stringable|bool|null $timezone
     */
    protected function prepare($date, $timezone = true, bool $includeTime = true): ?DateTime
    {
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
}
