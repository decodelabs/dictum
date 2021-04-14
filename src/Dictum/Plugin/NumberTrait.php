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
trait NumberTrait
{
    /**
     * Get unicode arrow for diff
     */
    protected function getDiffArrow(float $diff): string
    {
        if ($diff > 0) {
            return '⬆';
        } elseif ($diff < 0) {
            return '⬇';
        } else {
            return '⬌';
        }
    }

    /**
     * Normalize numeric value
     *
     * @param int|float|string|null $value
     * @return int|float|null
     */
    protected function normalizeNumeric($value, bool $checkInt = false)
    {
        if ($value === null) {
            return null;
        }

        if (
            is_string($value) &&
            is_numeric($value)
        ) {
            if ($checkInt) {
                $value = $value == (int)$value ?
                    (int)$value : (float)$value;
            } else {
                $value = (float)$value;
            }
        }

        if (
            !is_int($value) &&
            !is_float($value)
        ) {
            throw Exceptional::InvalidArgument('Value is not a number', null, $value);
        }

        return $value;
    }
}
