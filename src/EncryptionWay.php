<?php

namespace Jfnetwork\DoctrineEncryptedObject;

enum EncryptionWay: string
{
    case Defuse = 'defuse';
    case Openssl = 'openssl';
    case Sodium = 'sodium';
}
