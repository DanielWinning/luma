<?php

use App\Entity\User;
use Luma\SecurityComponent\Authentication\Authenticators\DatabaseAuthenticator;
use Luma\SecurityComponent\Authentication\Providers\DatabaseUserProvider;

require_once dirname(__DIR__) . '/vendor/autoload.php';

return [
    DatabaseUserProvider::class => [
        User::class,
    ],
    DatabaseAuthenticator::class => [
        new DatabaseUserProvider(User::class),
    ],
];