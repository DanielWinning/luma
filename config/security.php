<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

return [
    \Luma\SecurityComponent\Authentication\Providers\DatabaseUserProvider::class => [
        \App\Security\Entity\User::class,
    ],
    \Luma\SecurityComponent\Authentication\Authenticators\DatabaseAuthenticator::class => [
        new \Luma\SecurityComponent\Authentication\Providers\DatabaseUserProvider(\App\Security\Entity\User::class),
    ],
];