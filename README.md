# Remove-Prefix-Patcher for PHP-Scoper

PHP-Scoper Patcher for removing prefixes from global functions/classes/traits.

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
