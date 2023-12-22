# Doctrine Encrypted Object Mapping

This bundle implement a doctrine mapping type for objects, that should be encrypted in storage. 

## Install

The bundle can be installed with composer:
```
composer require jfnetwork/doctrine-encrypted-object
```

For Sodium or Openssl encryption are corresponding PHP extensions required. For `Defuse` is [defuse/php-encryption](https://github.com/defuse/php-encryption) package required

## Sample Configuration
you should provide two environment variables:
```yaml
DOCTRINE_ENCRYPTED_OBJECT_KEY=xxx
DOCTRINE_ENCRYPTED_OBJECT_ENCRYPTION_WAY=sodium
```
The `DOCTRINE_ENCRYPTED_OBJECT_ENCRYPTION_WAY` variable is optional and has value `sodium` by default.

The `DOCTRINE_ENCRYPTED_OBJECT_KEY` variable should be generated with the command:
```
bin/console jf:doctrine-encrypted-object:create-key {encryption_way}
``` 
where encryption way is one of `sodium`, `openssl`, `defuse`

## Usage

### Annotations
```php
/**
 * @ORM\Column(name="your_secure_field", type="encoded_object")
 */
private $yourSecureField;
```

### Attributes
```php
use Jfnetwork\DoctrineEncryptedObject\DoctrineEncryptedObject;

#[ORM\Column(type: DoctrineEncryptedObject::TYPE_NAME)]
private $yourSecureField;
```

## Upgrade from 2.0 to 3.0

You should set `DOCTRINE_ENCRYPTED_OBJECT_ENCRYPTION_WAY` environment variable to `defuse`. No other configuration is required. Some migration tool to other encryption ways will be provided later.
