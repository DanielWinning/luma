<?php

use Dotenv\Dotenv;
use Luma\Framework\Luma;
use Luma\HttpComponent\Request;
use Luma\HttpComponent\StreamBuilder;
use Luma\HttpComponent\Web\WebServerUri;
use Tracy\Debugger;
use Tracy\ILogger;

require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__));

$dotenv = Dotenv::createImmutable(sprintf('%s/config', dirname(__DIR__)));
$dotenv->load();

session_start();

try {
    $luma = new Luma(
        sprintf('%s/config', dirname(__DIR__)),
        sprintf('%s/views', dirname(__DIR__)),
        sprintf('%s/cache', dirname(__DIR__))
    );
    $luma->run(new Request(
        $_SERVER['REQUEST_METHOD'],
        WebServerUri::generate(),
        getallheaders(),
        StreamBuilder::build(file_get_contents('php://input'))
    ));
} catch (\Exception|\Throwable $exception) {
    Debugger::log($exception, ILogger::EXCEPTION);
    Debugger::getBlueScreen()->render($exception);
}