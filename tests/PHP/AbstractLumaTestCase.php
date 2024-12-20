<?php

namespace Tests;

use Dotenv\Dotenv;
use Luma\DependencyInjectionComponent\Exception\NotFoundException;
use Luma\Framework\Luma;
use Luma\HttpComponent\Request;
use Luma\HttpComponent\Uri;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\ErrorHandler;

abstract class AbstractLumaTestCase extends TestCase
{
    protected Luma $app;

    /**
     * @return void
     *
     * @throws NotFoundException|\Throwable
     */
    protected function setUp(): void
    {
        $configDirectory = sprintf('%s/config', dirname(__DIR__, 2));
        $templateDirectory = sprintf('%s/views', dirname(__DIR__, 2));
        $cacheDirectory = sprintf('%s/cache', dirname(__DIR__, 2));

        $dotenv = Dotenv::createImmutable($configDirectory);
        $dotenv->load();

        $this->app = new Luma($configDirectory, $templateDirectory, $cacheDirectory);
    }

    /**
     * @return void
     */
    protected function restoreErrorHandler(): void
    {
        while (true) {
            $previousHandler = set_error_handler(static fn() => null);

            restore_error_handler();

            if ($previousHandler instanceof ErrorHandler) {
                break;
            }

            restore_error_handler();
        }
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->restoreErrorHandler();
        restore_exception_handler();
    }

    /**
     * @param string $method
     * @param string $path
     *
     * @return Request
     */
    protected function buildTestRequest(string $method, string $path): Request
    {
        $uri = new Uri('https', '127.0.0.1', $path, '');

        return new Request($method, $uri);
    }
}