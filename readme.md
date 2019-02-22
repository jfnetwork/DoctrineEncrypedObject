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
## Sample Configuration
```yaml
doctrine_encrypted_object:
    key: '%env(DOCTRINE_ENCRYPTED_OBJECT_KEY)%'
```
And the ENV variable should be generated with command:
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

## Upgrade from 1.0 to 1.1

The field type was changed from TEXT to BLOB with 1.1. The Doctrine should make a suitable migration for you. Optionally you can add update query from HEX values to binary. For example for MySQL:
```sql
UPDATE `your_table` SET `your_secret_field` = UNHEX(`your_secret_field`)
```
