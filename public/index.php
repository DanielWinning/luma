<?php

use Luma\Framework\Luma;
use Tracy\Debugger;

require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__));

Debugger::enable(Debugger::Development);
Debugger::$logDirectory = sprintf('%s/log', dirname(__DIR__));

$app = new Luma();
