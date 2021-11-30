# Remove-Prefix-Patcher for PHP-Scoper

[PHP-Scoper](https://github.com/humbug/php-scoper) Patcher for removing prefixes from global functions/classes/traits.

## Installation
```sh
composer require --dev pxlrbt/php-scoper-prefix-remover
```

## Usage

Import the required classes either manually or via composer autoloader:
```php
require __DIR__ . '/vendor/autoload.php';

use pxlrbt\PhpScoper\PrefixRemover\IdentifierExtractor;
use pxlrbt\PhpScoper\PrefixRemover\RemovePrefixPatcher;
```

Extract the functions/classes/traits/interfaces you want the prefix removed from a stub file:
```php
$identifiers = (new IdentifierExtractor())
                    ->addStub('stub-file.php')
                    ->extract();
```

Add the patcher in the patcher section of PHPScoper and pass the identifiers you extracted earlier:
```php
'patchers' => [
    // Some patcher
    (new RemovePrefixPatcher($identifiers)),
    // More patchers
```


## WordPress

For removing prefixes from WordPress functions/classes install [WordPress stubs](https://github.com/php-stubs/wordpress-stubs).
```sh
composer require --dev php-stubs/wordpress-stubs
```

Then add the stubs file to the extractor:

```php
use pxlrbt\PhpScoper\PrefixRemover\IdentifierExtractor;

$identifiers = (new IdentifierExtractor())
                    ->addStub('vendor/php-stubs/wordpress-stubs/wordpress-stubs.php')
                    ->extract();
```

## Specific PHP version

There might be issues with new reserved keywords (e.g. readonly as new keyword in PHP 8.1 and WordPress function name). You can set the targeted PHP version by providing a non default lexer like this:

```php
use pxlrbt\PhpScoper\PrefixRemover\IdentifierExtractor;
use PhpParser\Lexer\Emulative;

$identifiers = (new IdentifierExtractor())
    ->addStub('vendor/php-stubs/wordpress-stubs/wordpress-stubs.php')
    ->setLexer(new Emulative(['phpVersion' => '8.0']))
    ->extract();
```

