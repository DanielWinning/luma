<?php

namespace Tests\Controller;

use App\Controller\AppController;
use Luma\DependencyInjectionComponent\Exception\NotFoundException;
use Tests\AbstractLumaTestCase;

class AppControllerTest extends AbstractLumaTestCase
{
    private ?AppController $testClass = null;

    /**
     * @throws NotFoundException|\Throwable
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->testClass = new AppController();
    }

    /**
     * @return void
     */
    public function testItInstantiatesWithRequiredServices(): void
    {
        $this->assertInstanceOf(AppController::class, $this->testClass);
    }

    /**
     * @throws \ReflectionException|\Throwable
     */
    public function testIndexAction(): void
    {
        $request = $this->buildTestRequest('GET', '/');

        $this->assertEquals(200, $this->app->run($request)->getStatusCode());
    }
}