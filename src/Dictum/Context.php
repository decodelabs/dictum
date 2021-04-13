<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dictum;

class Context
{
    /**
     * New text buffer
     */
    public function text(string $string, ?string $encoding = null): Text
    {
        return new Text($string, $encoding);
    }
}
