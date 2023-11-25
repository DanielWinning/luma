<?php

use DannyXCII\HttpComponent\Request;
use DannyXCII\HttpComponent\StreamBuilder;
use DannyXCII\HttpComponent\Web\WebServerUri;
use Luma\Framework\Luma;
use Tracy\Debugger;

require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__));

Debugger::enable(Debugger::Development);
Debugger::$logDirectory = sprintf('%s/log', dirname(__DIR__));

$app = new Luma(sprintf('%s/config', dirname(__DIR__)));
$app->run(new Request(
    $_SERVER['REQUEST_METHOD'],
    WebServerUri::generate(),
    getallheaders(),
    StreamBuilder::build('')
));
