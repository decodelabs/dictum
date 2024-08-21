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
use DecodeLabs\Veneer\Plugin\Wrapper as PluginWrapper;
use Stringable as Ref0;
use DecodeLabs\Dictum\Text as Ref1;

class Dictum implements Proxy
{
    use ProxyTrait;

    const Veneer = 'DecodeLabs\\Dictum';
    const VeneerTarget = Inst::class;

    public static Inst $instance;
    /** @var NumberPlugin|PluginWrapper<NumberPlugin> $number */
    public static NumberPlugin|PluginWrapper $number;
    /** @var TimePlugin|PluginWrapper<TimePlugin> $time */
    public static TimePlugin|PluginWrapper $time;

    public static function text(Ref0|string|int|float|null $text, ?string $encoding = NULL): ?Ref1 {
        return static::$instance->text(...func_get_args());
    }
    public static function name(Ref0|string|int|float|null $name, ?string $encoding = NULL): ?string {
        return static::$instance->name(...func_get_args());
    }
    public static function firstName(Ref0|string|int|float|null $fullName, ?string $encoding = NULL): ?string {
        return static::$instance->firstName(...func_get_args());
    }
    public static function initials(Ref0|string|int|float|null $name, bool $extendShort = true, ?string $encoding = NULL): ?string {
        return static::$instance->initials(...func_get_args());
    }
    public static function initialsAndSurname(Ref0|string|int|float|null $name, ?string $encoding = NULL): ?string {
        return static::$instance->initialsAndSurname(...func_get_args());
    }
    public static function initialMiddleNames(Ref0|string|int|float|null $name, ?string $encoding = NULL): ?string {
        return static::$instance->initialMiddleNames(...func_get_args());
    }
    public static function consonants(Ref0|string|int|float|null $text, ?string $encoding = NULL): ?string {
        return static::$instance->consonants(...func_get_args());
    }
    public static function label(Ref0|string|int|float|null $label, ?string $encoding = NULL): ?string {
        return static::$instance->label(...func_get_args());
    }
    public static function id(Ref0|string|int|float|null $id, ?string $encoding = NULL): ?string {
        return static::$instance->id(...func_get_args());
    }
    public static function camel(Ref0|string|int|float|null $id, ?string $encoding = NULL): ?string {
        return static::$instance->camel(...func_get_args());
    }
    public static function constant(Ref0|string|int|float|null $constant, ?string $encoding = NULL): ?string {
        return static::$instance->constant(...func_get_args());
    }
    public static function slug(Ref0|string|int|float|null $slug, string $allowedChars = '', ?string $encoding = NULL): ?string {
        return static::$instance->slug(...func_get_args());
    }
    public static function pathSlug(Ref0|string|int|float|null $slug, string $allowedChars = '', ?string $encoding = NULL): ?string {
        return static::$instance->pathSlug(...func_get_args());
    }
    public static function actionSlug(Ref0|string|int|float|null $slug, ?string $encoding = NULL): ?string {
        return static::$instance->actionSlug(...func_get_args());
    }
    public static function fileName(Ref0|string|int|float|null $fileName, bool $allowSpaces = false, ?string $encoding = NULL): ?string {
        return static::$instance->fileName(...func_get_args());
    }
    public static function shorten(Ref0|string|int|float|null $text, int $length, bool $rtl = false, ?string $encoding = NULL): ?string {
        return static::$instance->shorten(...func_get_args());
    }
    public static function numericToAlpha(?int $number, ?string $encoding = NULL): ?string {
        return static::$instance->numericToAlpha(...func_get_args());
    }
    public static function alphaToNumeric(Ref0|string|int|float|null $text, ?string $encoding = NULL): ?int {
        return static::$instance->alphaToNumeric(...func_get_args());
    }
    public static function toBoolean(Ref0|string|int|float|null $text, ?string $encoding = NULL): bool {
        return static::$instance->toBoolean(...func_get_args());
    }
    public static function compare(Ref0|string|int|float|null $string1, Ref0|string|int|float|null $string2): bool {
        return static::$instance->compare(...func_get_args());
    }
    public static function isAlpha(Ref0|string|int|float|null $text): bool {
        return static::$instance->isAlpha(...func_get_args());
    }
    public static function isAlphaNumeric(Ref0|string|int|float|null $text): bool {
        return static::$instance->isAlphaNumeric(...func_get_args());
    }
    public static function isDigit(Ref0|string|int|float|null $text): bool {
        return static::$instance->isDigit(...func_get_args());
    }
    public static function isWhitespace(Ref0|string|int|float|null $text): bool {
        return static::$instance->isWhitespace(...func_get_args());
    }
    public static function isBlank(Ref0|string|int|float|null $text): bool {
        return static::$instance->isBlank(...func_get_args());
    }
    public static function isHex(Ref0|string|int|float|null $text): bool {
        return static::$instance->isHex(...func_get_args());
    }
    public static function countWords(Ref0|string|int|float|null $text): int {
        return static::$instance->countWords(...func_get_args());
    }
};
