<?php

use DannyXCII\DependencyInjectionComponent\DependencyContainer;
use DannyXCII\DependencyInjectionComponent\DependencyManager;

require_once dirname(__DIR__) . '/vendor/autoload.php';

try {
    $container = new DependencyContainer();
    $dependencyManager = new DependencyManager($container);
    $dependencyManager->loadDependenciesFromFile(dirname(__DIR__) . '/config/services.yaml');
} catch (\Exception $exception) {
    die($exception->getMessage());
}