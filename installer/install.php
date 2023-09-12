<?php

$composerJsonPath = sprintf('%s/composer.json', dirname(__DIR__));

file_put_contents(
    $composerJsonPath,
    str_replace('lumax/luma', 'vendor/luma-project', file_get_contents($composerJsonPath))
);