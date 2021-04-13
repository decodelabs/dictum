<?php

/**
 * @package Dictum
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

/**
 * global helpers
 */
namespace DecodeLabs\Dictum
{
    use DecodeLabs\Dictum;
    use DecodeLabs\Veneer;

    // Register the Veneer facade
    Veneer::register(Context::class, Dictum::class);
}
