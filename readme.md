# Doctrine Encrypted Object Mapping

This bundle implement a doctrine mapping type for objects, that should be encrypted in storage. 

## Install

The bundle can be installed with composer:
```
composer require jfnetwork/doctrine-encrypted-object
```
Then it should be added to your bundle list:

### Symfony 4
bundles.php:
```php
<?php

return [
    ...,
    Jfnetwork\DoctrineEncryptedObject\DoctrineEncryptedObjectBundle::class => ['all' => true],
];
```

### Symfony 3
AppKernel.php:
```php
class AppKernel extends Kernel
{
    public function registerBundles(): array
    {
        $bundles = [
            ...,
            new Jfnetwork\DoctrineEncryptedObject\DoctrineEncryptedObjectBundle(),
        ];
```

## Sample Configuration
```yaml
doctrine_encrypted_object:
    key: 'your_secure_key'
```
The key can be generated with command:
```
vendor/bin/generate-defuse-key
``` 
More info about key generation at [tutorial](https://github.com/defuse/php-encryption/blob/master/docs/Tutorial.md) from **defuse/php-encryption** package

## Usage

```php
/**
 * @ORM\Column(name="your_secure_field", type="encoded_object")
 */
private $yourSecureField;
```
