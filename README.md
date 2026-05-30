# Twig extensions bundle

Collection of Twig extensions as a Symfony Bundle, which provides:
- array operations;
  - set/unset a specific array key (`array_set`, `array_unset`)
  - add a value to front/back of array (`array_push`, `array_unshift`)
  - remove a value from front/back of array (`array_pop`, `array_shift`)
- currency operations:
  - format a money amount (`amount|money_amount`)
- inlining operations:
  - dump the raw content of a file (`inline('style.css')`)
- sorting operations:
  - sort arrays by value (`sort`, `rsort`)
  - sort arrays by value, keeping associativity (`asort`, `arsort`)
  - sort arrays by key (`ksort`, `krsort`)
  - sort arrays by properties (`psort`)
- time-related operations:
  - adds direct access to PHP microtime() function
  - adds direct access to PHP intl_format() function
- type-related operations:
  - adds direct access to PHP get_class() and gettype() functions
  - adds support to identify a Twig variable type with new tests (`is array`, `is string`, etc) 

## Installation & Requirements

Install with [Composer](https://getcomposer.org):

```shell script
composer require jmf/twig-extensions-bundle
```

If you have [Flex](https://symfony.com/packages/Symfony%20Flex) installed, the Twig extensions are then instantly available without any need for initial configuration.
If not, complete your `config/bundles.php` file as indicated below:

```php
<?php

return [
    // ...
    // Other existing bundles.
    // ...
    Jmf\TwigExtensionsBundle\JmfTwigExtensionsBundle::class => ['all' => true],
];
```

## Configuration (optional)

Either run the following command:
```
bin/console config:dump jmf_twig_extensions > config/packages/jmf_twig_extensions.yaml
```
Or create a `jmf_twig_extensions.yaml` file in the `config/package` directory as follows:
```yaml
jmf_twig_extensions:

    # Enable or disable all Twig extensions. Can be overridden per extension.
    enabled:              true

    # Optional prefix before function and filter names. Can be overridden per extension.
    prefix:               ''

    extensions:

        # Twig "array" extension configuration.
        array:
            # Override the root "enabled" setting. Omit to inherit from root.
            enabled:          true

            # Override the root "prefix" setting. Omit to inherit from root.
            prefix:           ''

        # Twig "currency" extension configuration.
        currency:
            enabled:          true
            prefix:           ''

        # Twig "inline" extension configuration.
        inline:
            enabled:          true
            prefix:           ''

            # Base path to restrict file inlining. Also, all calls to the "inline" function will be relative to this path.
            basePath:         '%kernel.project_dir%/templates'

        # Twig "sort" extension configuration.
        sort:
            enabled:          true
            prefix:           ''

        # Twig "time" extension configuration.
        time:
            enabled:          true
            prefix:           ''

            # Optional locale for date and time representation.
            locale:           null

        # Twig "type" extension configuration.
        type:
            enabled:          true
            prefix:           ''
```
You may then alter any parameter to fit your needs.

## Included Twig Extensions

### Twig array extension

See https://packagist.org/packages/jmf/twig-array for documentation.

### Twig currency extension

See https://packagist.org/packages/jmf/twig-currency for documentation.

### Twig inline extension

See https://packagist.org/packages/jmf/twig-inline for documentation.

### Twig sort extension

See https://packagist.org/packages/jmf/twig-sort for documentation.

### Twig time extension

See https://packagist.org/packages/jmf/twig-time for documentation.

### Twig type extension

See https://packagist.org/packages/jmf/twig-type for documentation.
