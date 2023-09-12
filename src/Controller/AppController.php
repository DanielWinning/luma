<?php

namespace App\Controller;

use DannyXCII\HttpComponent\Response;
use Luma\Framework\Controller\LumaController;

class AppController extends LumaController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('index', []);
    }

    /**
     * @return Response
     */
    public function testApi(): Response
    {
        return $this->json(['hello' => 'world']);
    }
}