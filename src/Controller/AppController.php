<?php

namespace App\Controller;

use Luma\Framework\Controller\LumaController;
use Luma\HttpComponent\Response;

class AppController extends LumaController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('app/index', []);
    }
}