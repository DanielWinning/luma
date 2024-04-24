<?php

namespace App\Security\Controller;

use App\Security\Entity\User;
use Luma\Framework\Controller\LumaController;
use Luma\Framework\Luma;
use Luma\HttpComponent\Request;
use Luma\HttpComponent\Response;

class SecurityController extends LumaController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function login(Request $request): Response
    {
        if (!$this->getLoggedInUser() && $request->getMethod() === 'POST') {
            $requestData = $request->getData();

            if (!isset($requestData[User::getSecurityIdentifier()]) || !isset($requestData['password'])) {
                $this->addError(sprintf('Invalid request data, please set %s and password', User::getSecurityIdentifier()));
            }

            $authenticator = Luma::getAuthenticator();
            $authenticationResult = $authenticator->login(
                $requestData[User::getSecurityIdentifier()],
                $requestData['password']
            );

            if (!$authenticationResult->isAuthenticated()) {
                $this->addError('Invalid credentials, please check and try again.');
            }
        }

        return $this->render('security/login');
    }

    public function logout()
    {

    }

    public function register()
    {

    }
}