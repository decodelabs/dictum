<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;

use DecodeLabs\Veneer\Proxy as Proxy;
use DecodeLabs\Veneer\ProxyTrait as ProxyTrait;
use DecodeLabs\Dictum\Context as Inst;
use DecodeLabs\Dictum\Plugins\Number as NumberPlugin;
use DecodeLabs\Dictum\Plugins\Time as TimePlugin;
use Stringable as Ref0;
use DecodeLabs\Dictum\Text as Ref1;

class Dictum implements Proxy
{
    use ProxyTrait;

    public const Veneer = 'DecodeLabs\\Dictum';
    public const VeneerTarget = Inst::class;

    protected static Inst $_veneerInstance;
    public static NumberPlugin $number;
    public static TimePlugin $time;

    public static function text(Ref0|string|int|float|null $text, ?string $encoding = NULL): ?Ref1 {
        return static::$_veneerInstance->text(...func_get_args());
    }
    public static function name(Ref0|string|int|float|null $name, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->name(...func_get_args());
    }
    public static function firstName(Ref0|string|int|float|null $fullName, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->firstName(...func_get_args());
    }
    public static function initials(Ref0|string|int|float|null $name, bool $extendShort = true, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->initials(...func_get_args());
    }
    public static function initialsAndSurname(Ref0|string|int|float|null $name, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->initialsAndSurname(...func_get_args());
    }
    public static function initialMiddleNames(Ref0|string|int|float|null $name, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->initialMiddleNames(...func_get_args());
    }
    public static function consonants(Ref0|string|int|float|null $text, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->consonants(...func_get_args());
    }
    public static function label(Ref0|string|int|float|null $label, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->label(...func_get_args());
    }
    public static function id(Ref0|string|int|float|null $id, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->id(...func_get_args());
    }
    public static function camel(Ref0|string|int|float|null $id, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->camel(...func_get_args());
    }
    public static function constant(Ref0|string|int|float|null $constant, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->constant(...func_get_args());
    }
    public static function slug(Ref0|string|int|float|null $slug, string $allowedChars = '', ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->slug(...func_get_args());
    }
    public static function pathSlug(Ref0|string|int|float|null $slug, string $allowedChars = '', ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->pathSlug(...func_get_args());
    }
    public static function actionSlug(Ref0|string|int|float|null $slug, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->actionSlug(...func_get_args());
    }
    public static function fileName(Ref0|string|int|float|null $fileName, bool $allowSpaces = false, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->fileName(...func_get_args());
    }
    public static function shorten(Ref0|string|int|float|null $text, int $length, bool $rtl = false, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->shorten(...func_get_args());
    }
    public static function numericToAlpha(?int $number, ?string $encoding = NULL): ?string {
        return static::$_veneerInstance->numericToAlpha(...func_get_args());
    }
    public static function alphaToNumeric(Ref0|string|int|float|null $text, ?string $encoding = NULL): ?int {
        return static::$_veneerInstance->alphaToNumeric(...func_get_args());
    }
    public static function toBoolean(Ref0|string|int|float|null $text, ?string $encoding = NULL): bool {
        return static::$_veneerInstance->toBoolean(...func_get_args());
    }
    public static function compare(Ref0|string|int|float|null $string1, Ref0|string|int|float|null $string2): bool {
        return static::$_veneerInstance->compare(...func_get_args());
    }
    public static function isAlpha(Ref0|string|int|float|null $text): bool {
        return static::$_veneerInstance->isAlpha(...func_get_args());
    }
    public static function isAlphaNumeric(Ref0|string|int|float|null $text): bool {
        return static::$_veneerInstance->isAlphaNumeric(...func_get_args());
    }
    public static function isDigit(Ref0|string|int|float|null $text): bool {
        return static::$_veneerInstance->isDigit(...func_get_args());
    }
    public static function isWhitespace(Ref0|string|int|float|null $text): bool {
        return static::$_veneerInstance->isWhitespace(...func_get_args());
    }
    public static function isBlank(Ref0|string|int|float|null $text): bool {
        return static::$_veneerInstance->isBlank(...func_get_args());
    }
    public static function isHex(Ref0|string|int|float|null $text): bool {
        return static::$_veneerInstance->isHex(...func_get_args());
    }
    public static function countWords(Ref0|string|int|float|null $text): int {
        return static::$_veneerInstance->countWords(...func_get_args());
    }
};
