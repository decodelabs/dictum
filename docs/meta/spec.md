# Dictum — Package Specification

> **Cluster:** `frontend`
> **Language:** `php`
> **Milestone:** `m3`
> **Repo:** `https://github.com/decodelabs/dictum`
> **Role:** Text formatting

This document describes the purpose, contracts, and design of **Dictum** within the Decode Labs ecosystem.

It is aimed at:

- Developers **using** Dictum in their own applications or libraries.
- Contributors **maintaining or extending** Dictum.
- Tools and AI assistants that need to reason about its behaviour.

---

## 1. Overview

### 1.1 Purpose

Dictum provides a collection of commonly required text parsing and processing features. It offers a comprehensive set of text formatting utilities including name formatting, slug generation, case conversion, encoding handling, and multibyte-aware string manipulation. The package also provides Cosmos extensions for locale-aware number and time formatting, and Lucid processors for value sanitization. It's designed to be a one-stop solution for text formatting needs in PHP applications.

### 1.2 Non-Goals

Dictum does **not**:

- Provide HTML markup generation — see Tagged package for HTML output
- Handle complex natural language processing — it's focused on formatting
- Provide translation or internationalization — it formats text, doesn't translate it
- Handle file I/O operations — it works with strings
- Provide template engines — it's a formatting library
- Handle database operations — it's a pure text processing library
- Provide validation logic — see Lucid package for validation

---

## 2. Role in the Ecosystem

### 2.1 Cluster & Positioning

- **Cluster:** `frontend` (see Chorus taxonomy)
- Dictum is a frontend package that provides text formatting tools for the Decode Labs ecosystem. It sits in the frontend cluster alongside other presentation and formatting utilities. It depends on Cosmos, Exceptional, Fluidity, Kingdom, Nuance, and Symfony Polyfill MBString. It's used extensively throughout the ecosystem for text formatting, slug generation, name formatting, and locale-aware number/time formatting.

### 2.2 Typical Usage Contexts

Typical places Dictum appears:

- URL slug generation
- Form field formatting
- Display name formatting
- File name sanitization
- Text case conversion
- Locale-aware number formatting
- Locale-aware time formatting
- Text validation and sanitization (via Lucid processors)
- Text encoding conversion
- ASCII transliteration

Dictum is intended to be used whenever code needs to format, transform, or sanitize text strings in a predictable and multibyte-aware way.

---

## 3. Public Surface

> This section focuses on the conceptual API, not every symbol.

### 3.1 Key Types

The primary public types are:

- `DecodeLabs\Dictum`
  Main service class implementing `Kingdom\Service`. Provides static methods for text formatting operations.

- `DecodeLabs\Dictum\Text`
  Immutable multibyte-aware text manipulation class. Implements `Then`, `ArrayAccess`, `Countable`, `Stringable`, and `Dumpable`. Provides comprehensive string manipulation methods.

- `DecodeLabs\Dictum\Number`
  Cosmos number extension implementation. Provides locale-aware number formatting methods (format, decimal, currency, percent, scientific, spellout, ordinal, diff, fileSize, fileSizeDec).

- `DecodeLabs\Dictum\Time`
  Cosmos time extension implementation. Provides locale-aware time formatting methods (format, formatDate, pattern, locale, and interval methods like since, until, between).

- `DecodeLabs\Lucid\Processor\Slug`
  Lucid processor for converting values to URL-friendly slugs.

- `DecodeLabs\Lucid\Processor\Camel`
  Lucid processor for converting values to camelCase.

- `DecodeLabs\Lucid\Processor\Name`
  Lucid processor for converting values to formatted names.

- `DecodeLabs\Lucid\Processor\PathSlug`
  Lucid processor for converting values to path-friendly slugs.

### 3.2 Main Entry Points

The main usage pattern is through static methods on the `Dictum` class:

```php
use DecodeLabs\Dictum;

$name = Dictum::name('geoff-randomName');
$slug = Dictum::slug('here\'s a Random-string of text');
$id = Dictum::id('here\'s a Random-string of text');
```

For advanced text manipulation, use the `Text` class:

```php
use DecodeLabs\Dictum\Text;

$text = new Text('Hello World');
$formatted = $text->toTitleCase()->replace(' ', '-');
```

For locale-aware formatting, use `Number` and `Time`:

```php
use DecodeLabs\Dictum\Number;
use DecodeLabs\Dictum\Time;

$number = Number::currency(16.53, 'GBP');
$time = Time::dateTime('tomorrow');
```

---

## 4. Dependencies

### 4.1 Decode Labs

- `decodelabs/cosmos` (required)
  Used for locale-aware formatting via `Number` and `Time` extension interfaces.

- `decodelabs/exceptional` (required)
  Used for exception handling when text operations fail.

- `decodelabs/fluidity` (required)
  Used for fluent interface support via `Then` interface on `Text` class.

- `decodelabs/kingdom` (required)
  Used for service interface (`Service`, `ServiceTrait`).

- `decodelabs/nuance` (required)
  Used for debugging and inspection via `Dumpable` interface on `Text` class.

### 4.2 External

- `symfony/polyfill-mbstring` (required)
  Used for multibyte string function polyfills to ensure consistent behavior across PHP versions.

### 4.3 Optional Integrations

- `decodelabs/lucid` (dev dependency, conflict <0.8)
  Used for Lucid processors (Slug, Camel, Name, PathSlug). Detected at runtime if installed, used for value sanitization in Lucid schemas.

---

## 5. Behaviour & Contracts

### 5.1 Invariants

- `Text` class is immutable — all operations return new instances
- All text operations are multibyte-aware using `mb_*` functions
- Encoding defaults to `mb_internal_encoding()` if not specified
- Regex operations use `mb_ereg_*` functions (no delimiters in patterns)
- ASCII conversion supports multiple languages (en, de, bg)
- Name formatting handles common prefixes (Mr, Ms, Mrs, Miss, Dr)
- Slug generation normalizes to lowercase with hyphens
- Boolean conversion recognizes common true/false strings
- Numeric-to-alpha conversion uses base-26 encoding

### 5.2 Input & Output Contracts

**Dictum Static Methods:**
- `text(mixed $text, ?string $encoding): ?Text` — Creates Text instance
- `name(mixed $name, ?string $encoding): ?string` — Formats as display name
- `firstName(mixed $fullName, ?string $encoding): ?string` — Extracts first name
- `initials(mixed $name, bool $extendShort, ?string $encoding): ?string` — Gets initials
- `initialsAndSurname(mixed $name, ?string $encoding): ?string` — Formats as initials + surname
- `initialMiddleNames(mixed $name, ?string $encoding): ?string` — Formats as first + middle initials + surname
- `consonants(mixed $text, ?string $encoding): ?string` — Removes vowels
- `label(mixed $label, ?string $encoding): ?string` — Formats as label
- `id(mixed $id, ?string $encoding): ?string` — Formats as identifier (PascalCase)
- `camel(mixed $id, ?string $encoding): ?string` — Formats as camelCase
- `constant(mixed $constant, ?string $encoding): ?string` — Formats as CONSTANT_CASE
- `slug(mixed $slug, string $allowedChars, ?string $encoding): ?string` — Formats as URL slug
- `pathSlug(mixed $slug, string $allowedChars, ?string $encoding): ?string` — Formats as path slug
- `actionSlug(mixed $slug, ?string $encoding): ?string` — Formats as action slug
- `fileName(mixed $fileName, bool $allowSpaces, ?string $encoding): ?string` — Formats as file name
- `shorten(mixed $text, int $length, bool $rtl, ?string $encoding): ?string` — Shortens text with ellipsis
- `numericToAlpha(?int $number, ?string $encoding): ?string` — Converts number to letters
- `alphaToNumeric(mixed $text, ?string $encoding): ?int` — Converts letters to number
- `toBoolean(mixed $text, ?string $encoding): bool` — Converts text to boolean
- `compare(mixed $string1, mixed $string2): bool` — Compares strings (normalized)
- `isAlpha(mixed $text): bool` — Checks if text is alphabetic
- `isAlphaNumeric(mixed $text): bool` — Checks if text is alphanumeric
- `isDigit(mixed $text): bool` — Checks if text is digits
- `isWhitespace(mixed $text): bool` — Checks if text is whitespace
- `isBlank(mixed $text): bool` — Checks if text is blank
- `isHex(mixed $text): bool` — Checks if text is hexadecimal
- `countWords(mixed $text): int` — Counts words in text

**Text Class Operations:**
- `create(?string $text, ?string $encoding): static` — Factory method
- `__construct(?string $text, ?string $encoding)` — Constructor
- `__toString(): string` — String conversion
- `isEmpty(): bool` — Checks if empty
- `count(): int` — Gets length
- `getLength(): int` — Gets length
- `getEncoding(): string` — Gets encoding
- `convertEncoding(string $encoding): static` — Converts encoding
- `toUtf8(): static` — Converts to UTF-8
- `getChar(int $index): static` — Gets character at index
- `replaceChar(int $index, string $char): static` — Replaces character
- `insert(int $index, string $string): static` — Inserts string
- `removeChar(int $index): static` — Removes character
- `hasCharAt(int $index): bool` — Checks if character exists
- `getIndexOf(string $needle, int $offset): ?int` — Finds first index
- `getIndexOfCi(string $needle, int $offset): ?int` — Finds first index (case-insensitive)
- `getLastIndexOf(string $needle, int $offset): ?int` — Finds last index
- `getLastIndexOfCi(string $needle, int $offset): ?int` — Finds last index (case-insensitive)
- `contains(string ...$needles): bool` — Checks if contains
- `containsCi(string ...$needles): bool` — Checks if contains (case-insensitive)
- `containsAll(string ...$needles): bool` — Checks if contains all
- `containsAllCi(string ...$needles): bool` — Checks if contains all (case-insensitive)
- `beginsWith(string ...$starts): bool` — Checks if begins with
- `beginsWithCi(string ...$starts): bool` — Checks if begins with (case-insensitive)
- `endsWith(string ...$ends): bool` — Checks if ends with
- `endsWithCi(string ...$ends): bool` — Checks if ends with (case-insensitive)
- `padLeft(int $size, string $value): static` — Pads left
- `padRight(int $size, string $value): static` — Pads right
- `padBoth(int $size, string $value): static` — Pads both sides
- `countInstances(string $string): int` — Counts occurrences
- `countInstancesCi(string $string): int` — Counts occurrences (case-insensitive)
- `countWords(): int` — Counts words
- `slice(int $start, ?int $length): static` — Gets substring
- `sliceRandom(int $length): static` — Gets random slice
- `sliceDelimited(string $start, string $end, int $offset): static` — Gets delimited substring
- `append(Text|string|Stringable|null $text, ?string $encoding): static` — Appends text
- `prepend(Text|string|Stringable|null $text, ?string $encoding): static` — Prepends text
- `surroundWith(Text|string|Stringable|null $text, ?string $encoding): static` — Surrounds with text
- `truncate(int $length, ?string $cap): static` — Truncates text
- `collapseWhitespace(): static` — Collapses whitespace
- `stripWhitespace(): static` — Strips whitespace
- `matches(string $pattern, string $options): bool` — Regex match
- `match(string $pattern, string $options): ?array` — Regex match with captures
- `replace(array|string $search, array|string $replace, int &$count): static` — String replace
- `regexReplace(string $pattern, string|callable $replacement, string $options): static` — Regex replace
- `split(string $delimiter, int $limit): array` — Splits by delimiter
- `regexSplit(string $pattern, int $limit): array` — Splits by regex
- `delimit(string $delimiter): static` — Delimits text
- `trim(?string $chars): static` — Trims both sides
- `trimLeft(?string $chars): static` — Trims left
- `trimRight(?string $chars): static` — Trims right
- `isAlpha(): bool` — Checks if alphabetic
- `isAlphaNumeric(): bool` — Checks if alphanumeric
- `isDigit(): bool` — Checks if digits
- `isWhitespace(): bool` — Checks if whitespace
- `isBlank(): bool` — Checks if blank
- `isHex(): bool` — Checks if hexadecimal
- `isJson(): bool` — Checks if valid JSON
- `isLowerCase(): bool` — Checks if lowercase
- `hasLowerCase(): bool` — Checks if has lowercase
- `toLowerCase(): static` — Converts to lowercase
- `firstToLowerCase(): static` — Converts first to lowercase
- `isUpperCase(): bool` — Checks if uppercase
- `hasUpperCase(): bool` — Checks if has uppercase
- `toUpperCase(): static` — Converts to uppercase
- `firstToUpperCase(): static` — Converts first to uppercase
- `toTitleCase(): static` — Converts to title case
- `swapCase(): static` — Swaps case
- `toAscii(string $language, bool $removeUnsupported): static` — Converts to ASCII
- `toBoolean(?bool $default): bool` — Converts to boolean
- `tabsToSpaces(int $tabLength): static` — Converts tabs to spaces
- `spacesToTabs(int $tabLength): static` — Converts spaces to tabs
- `numericToAlpha(int $number): static` — Converts number to letters
- `alphaToNumeric(): ?int` — Converts letters to number
- `htmlEncode(int $flags): static` — HTML encodes
- `htmlDecode(int $flags): static` — HTML decodes
- `stripTags(?string $allowableTags): static` — Strips HTML tags
- `tidyMsWord(): static` — Tidies MS Word characters
- `scan(): iterable` — Iterates over characters
- `scanMatches(string $pattern, ?int $limit, bool $yieldMatch, string $options): iterable` — Iterates over matches
- `scanLines(): iterable` — Iterates over lines
- `scanWords(): iterable` — Iterates over words
- `searchAll(string $pattern, ?int $limit, string $options): iterable` — Searches all matches
- `toArray(): array` — Converts to array
- `jsonSerialize(): string` — JSON serialization
- `isMutable(): bool` — Always returns false (immutable)

**Number Operations:**
- `format(int|float|string|null $value, ?string $unit, string|Locale|null $locale): ?string` — Formats number with optional unit
- `pattern(int|float|string|null $value, string $pattern, string|Locale|null $locale): ?string` — Formats with pattern
- `decimal(int|float|string|null $value, ?int $precision, string|Locale|null $locale): ?string` — Formats as decimal
- `currency(int|float|string|null $value, ?string $code, ?bool $rounded, string|Locale|null $locale): ?string` — Formats as currency
- `percent(int|float|string|null $value, float $total, int $decimals, string|Locale|null $locale): ?string` — Formats as percentage
- `scientific(int|float|string|null $value, string|Locale|null $locale): ?string` — Formats as scientific notation
- `spellout(int|float|string|null $value, string|Locale|null $locale): ?string` — Formats as words
- `ordinal(int|float|string|null $value, string|Locale|null $locale): ?string` — Formats as ordinal
- `diff(int|float|string|null $diff, ?bool $invert, string|Locale|null $locale): ?string` — Formats difference
- `fileSize(?int $bytes, string|Locale|null $locale): ?string` — Formats file size (binary)
- `fileSizeDec(?int $bytes, string|Locale|null $locale): ?string` — Formats file size (decimal)

**Time Operations:**
- `format(DateTimeInterface|DateInterval|string|Stringable|int|null $date, string $format, DateTimeZone|string|Stringable|bool|null $timezone): ?string` — Custom format
- `formatDate(DateTimeInterface|DateInterval|string|Stringable|int|null $date, string $format): ?string` — Custom format (date only)
- `pattern(DateTimeInterface|DateInterval|string|Stringable|int|null $date, string $pattern, DateTimeZone|string|Stringable|bool|null $timezone, string|Locale|null $locale): ?string` — ICU pattern format
- `locale(DateTimeInterface|DateInterval|string|Stringable|int|null $date, string|int|bool|null $dateSize, string|int|bool|null $timeSize, DateTimeZone|string|Stringable|bool|null $timezone, string|Locale|null $locale): ?string` — Locale format
- Plus all interval methods from `TimeExtensionTrait` (since, until, sinceAbs, untilAbs, between, dateTime, longDateTime, shortDateTime, date, longDate, mediumDate, shortDate, time, longTime, mediumTime, shortTime)

**Lucid Processor Operations:**
- `coerce(mixed $value): ?string` — Converts value to processed string
- `getDefaultConstraints(): array` — Gets default constraints (Name processor)

### 5.3 Text Immutability

The `Text` class is immutable — all operations return new instances. This ensures thread-safety and predictable behavior. The class implements `ArrayAccess` for read-only character access (setting/unsetting throws exceptions).

### 5.4 Multibyte Awareness

All text operations use PHP's `mb_*` functions for multibyte-aware string handling. This ensures correct behavior with Unicode characters, including emoji, accented characters, and multi-byte encodings.

### 5.5 Regex Patterns

Regex operations use `mb_ereg_*` functions which don't require delimiters in patterns. Patterns are passed directly without `/` delimiters or flags.

### 5.6 ASCII Conversion

ASCII conversion supports multiple languages:
- `en` (default) — English transliteration
- `de` — German (ä→ae, ö→oe, ü→ue)
- `bg` — Bulgarian (х→h, щ→sht, etc.)

### 5.7 Name Formatting

Name formatting handles:
- Common prefixes (Mr, Ms, Mrs, Miss, Dr) — automatically skipped
- Short names — extended with consonants if needed
- Multiple formats — initials, initials+surname, first+middle initials+surname

### 5.8 Slug Generation

Slug generation:
- Normalizes to lowercase
- Replaces spaces/special chars with hyphens
- Removes invalid characters
- Collapses multiple hyphens
- Trims hyphens from edges
- Supports custom allowed characters

### 5.9 Boolean Conversion

Boolean conversion recognizes:
- `true`: 'true', '1', 'yes', 'y', 'on', 'enabled'
- `false`: 'false', '0', 'no', 'n', 'off', 'disabled'
- Numeric values: >0 = true, 0 = false

### 5.10 Numeric-Alpha Conversion

Numeric-to-alpha uses base-26 encoding (a-z). Alpha-to-numeric reverses this process.

---

## 6. Error Handling

- Invalid regex patterns throw `Exceptional::Runtime` when `mb_ereg_*` operations fail
- Array access setting/unsetting throws `Exceptional::Implementation` (immutable)
- Invalid encoding conversions may return false (handled gracefully)
- Null inputs return null for most operations
- Empty strings are handled according to operation semantics

---

## 7. Configuration & Extensibility

- Encoding can be specified per operation or defaults to `mb_internal_encoding()`
- ASCII conversion supports language-specific mappings
- Slug generation supports custom allowed characters
- File name formatting supports allowing spaces
- Text shortening supports RTL mode
- Cosmos extensions support locale customization
- Lucid processors can be extended for custom formatting

---

## 8. Interactions with Other Packages

### 8.1 Cosmos

Dictum implements Cosmos extension interfaces:
- `NumberExtension` — provides locale-aware number formatting
- `TimeExtension` — provides locale-aware time formatting

These extensions allow Dictum to be used wherever Cosmos formatters are expected, providing plain text output (as opposed to Tagged's HTML output).

### 8.2 Exceptional

Dictum uses Exceptional for all exception handling, providing consistent error reporting across the ecosystem.

### 8.3 Fluidity

Dictum uses Fluidity's `Then` interface on the `Text` class, enabling fluent method chaining for text operations.

### 8.4 Kingdom

Dictum implements Kingdom's `Service` interface, allowing it to be registered as a service in the Kingdom service container.

### 8.5 Nuance

Dictum implements Nuance's `Dumpable` interface on the `Text` class, allowing text objects to be inspected and debugged using Nuance's debugging tools.

### 8.6 Lucid

Dictum provides Lucid processors for value sanitization:
- `Slug` processor — converts values to URL-friendly slugs
- `Camel` processor — converts values to camelCase
- `Name` processor — converts values to formatted names
- `PathSlug` processor — converts values to path-friendly slugs

These processors can be used in Lucid schemas to automatically format/sanitize input values.

### 8.7 Symfony Polyfill MBString

Dictum uses Symfony Polyfill MBString to ensure consistent multibyte string behavior across PHP versions, especially for older PHP versions that may have incomplete `mb_*` function support.

---

## 9. Usage Examples

### 9.1 Basic Formatting

```php
use DecodeLabs\Dictum;

// Name formatting
echo Dictum::name('geoff-randomName');
// Geoff Random Name

echo Dictum::firstName('geoff-randomName');
// Geoff

echo Dictum::initials('geoff-randomName');
// GRN

// Slug generation
echo Dictum::slug('here\'s a Random-string of text');
// heres-a-random-string-of-text

// Case conversion
echo Dictum::id('here\'s a Random-string of text');
// HeresARandomStringOfText

echo Dictum::camel('here\'s a Random-string of text');
// heresARandomStringOfText

echo Dictum::constant('here\'s a Random-string of text');
// HERE_S_A_RANDOM_STRING_OF_TEXT
```

### 9.2 Text Class

```php
use DecodeLabs\Dictum\Text;

$text = new Text('Hello World');

// Fluent operations
$formatted = $text
    ->toTitleCase()
    ->replace(' ', '-')
    ->toLowerCase();

// Character access
$firstChar = $text[0]; // Returns Text instance

// Searching
$contains = $text->contains('Hello'); // true
$index = $text->getIndexOf('World'); // 6

// Slicing
$slice = $text->slice(0, 5); // "Hello"
```

### 9.3 Number Formatting

```php
use DecodeLabs\Dictum\Number;

// Basic formatting
echo Number::format(16.5, 'px');
// 16.5 px

echo Number::format(16.5, 'px', 'de');
// 16,5 px

// Decimal
echo Number::decimal(16.534643, 2);
// 16.53

// Currency
echo Number::currency(16.534643, 'GBP');
// £16.53

// Percentage
echo Number::percent(16.534643, 50, 2);
// 33.07%

// File size
echo Number::fileSize(16534643);
// 15.77 MiB
```

### 9.4 Time Formatting

```php
use DecodeLabs\Dictum\Time;

// Custom format
echo Time::format('now', 'd/m/Y', 'Europe/London');
// 01/01/2024

// Locale format
echo Time::locale('now', 'long', 'long', true);
// January 1, 2024 at 12:00:00 PM GMT

// Shortcuts
echo Time::dateTime('tomorrow');
echo Time::longTime('yesterday');
echo Time::shortDate('yesterday');

// Intervals
echo Time::since('yesterday');
// 1 day ago

echo Time::until('tomorrow');
// 1 day from now
```

### 9.5 Lucid Processors

```php
use DecodeLabs\Lucid\Processor\Slug;
use DecodeLabs\Lucid\Processor\Camel;
use DecodeLabs\Lucid\Processor\Name;

$slugProcessor = new Slug();
$slug = $slugProcessor->coerce('Hello World');
// hello-world

$camelProcessor = new Camel();
$camel = $camelProcessor->coerce('hello world');
// helloWorld

$nameProcessor = new Name();
$name = $nameProcessor->coerce('hello world');
// Hello World
```

### 9.6 Advanced Text Operations

```php
use DecodeLabs\Dictum\Text;

$text = new Text('Hello World');

// Regex operations
$matches = $text->matches('^Hello');
$replaced = $text->regexReplace('World', 'Universe');

// Splitting
$parts = $text->split(' ');
// [Text('Hello'), Text('World')]

// Iteration
foreach ($text->scan() as $char) {
    // Iterate over characters
}

foreach ($text->scanLines() as $line) {
    // Iterate over lines
}

// Encoding
$utf8 = $text->toUtf8();
$ascii = $text->toAscii('en');
```

---

## 10. Implementation Notes (for Contributors)

### 10.1 Text Immutability

The `Text` class is designed to be immutable. All operations return new instances, ensuring thread-safety and predictable behavior. The class uses `protected(set)` properties to prevent external mutation.

### 10.2 Multibyte Functions

All string operations use PHP's `mb_*` functions:
- `mb_strlen()` for length
- `mb_substr()` for slicing
- `mb_strpos()` for searching
- `mb_ereg_*` for regex operations
- `mb_convert_encoding()` for encoding conversion
- `mb_strtolower()` / `mb_strtoupper()` for case conversion

### 10.3 Regex Encoding

Regex operations temporarily set the regex encoding to match the text encoding:
```php
$encoding = mb_regex_encoding();
mb_regex_encoding($this->encoding);
// ... regex operation ...
mb_regex_encoding($encoding);
```

### 10.4 ASCII Conversion

ASCII conversion uses character mapping tables:
- `AsciiChars` — maps Unicode characters to ASCII equivalents
- `LanguageAsciiChars` — language-specific mappings (de, bg)

The conversion process:
1. Apply language-specific mappings
2. Apply general ASCII mappings
3. Optionally remove unsupported characters

### 10.5 Name Formatting

Name formatting logic:
1. Replace hyphens/underscores with spaces
2. Split camelCase words
3. Convert to title case
4. Handle prefixes (Mr, Ms, etc.)
5. Extract initials or format as needed

### 10.6 Slug Generation

Slug generation process:
1. Convert to UTF-8
2. Convert to ASCII
3. Detect camelCase and split
4. Convert to lowercase
5. Replace spaces/special chars with hyphens
6. Remove invalid characters (except allowed)
7. Collapse multiple hyphens
8. Trim hyphens from edges

### 10.7 Cosmos Extensions

Cosmos extensions use traits:
- `NumberExtensionTrait` — provides number formatting logic
- `TimeExtensionTrait` — provides time formatting logic

These traits handle locale resolution, formatting, and wrapping.

### 10.8 Lucid Processors

Lucid processors implement the `Processor` interface:
- `coerce()` method converts values to processed strings
- `OutputTypes` constant defines supported output types
- `getDefaultConstraints()` can provide default constraints

Processors use `Coercion` to convert input values to strings before processing.

---

## 11. Testing & Quality

- **Code Quality Score:** 4/5
- **README Quality Score:** 3/5
- **Documentation Score:** 0/5 (this spec)
- **Test Coverage Score:** 0/5

See `composer.json` for supported PHP versions.

---

## 12. Roadmap & Future Ideas

- Add test coverage
- Improve documentation and usage examples
- Consider adding more text transformation methods
- Consider adding more locale-specific formatting options
- Consider adding text similarity/distance algorithms
- Consider adding text encryption/obfuscation methods
- Consider adding more Lucid processors
- Consider adding text templating capabilities

---

## 13. References

- [Cosmos Package](https://github.com/decodelabs/cosmos) — Locale-aware formatting
- [Exceptional Package](https://github.com/decodelabs/exceptional) — Exception handling
- [Fluidity Package](https://github.com/decodelabs/fluidity) — Fluent interfaces
- [Kingdom Package](https://github.com/decodelabs/kingdom) — Service container
- [Nuance Package](https://github.com/decodelabs/nuance) — Debugging tools
- [Lucid Package](https://github.com/decodelabs/lucid) — Value sanitization
- [Tagged Package](https://github.com/decodelabs/tagged) — HTML output equivalents
- [Symfony Polyfill MBString](https://github.com/symfony/polyfill-mbstring) — Multibyte polyfills
- [Chorus Package Index](../../../chorus/config/packages.json) — Ecosystem metadata

