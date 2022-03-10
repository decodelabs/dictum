<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum\Plugins;

use DecodeLabs\Dictum\Context;
use DecodeLabs\Dictum\Plugin\Time as TimePlugin;
use DecodeLabs\Dictum\Plugin\TimeTrait as TimePluginTrait;

/**
 * @implements TimePlugin<string>
 */
class Time implements TimePlugin
{
    /**
     * @use TimePluginTrait<string>
     */
    use TimePluginTrait;

    /**
     * @var Context
     */
    protected $context;

    /**
     * Init with parent Context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }


    /**
     * Custom format a date
     */
    public function format(
        $date,
        string $format,
        $timezone = true
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
        $date,
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
        $date,
        string $pattern,
        $timezone = true,
        ?string $locale = null
    ): ?string {
        return $this->formatRawIcuDate($date, $pattern, $timezone, $locale);
    }

    /**
     * Format date according to locale
     */
    public function locale(
        $date,
        $dateSize = true,
        $timeSize = true,
        $timezone = true,
        ?string $locale = null
    ): ?string {
        return $this->formatRawLocaleDate($date, $dateSize, $timeSize, $timezone, $locale);
    }
}
