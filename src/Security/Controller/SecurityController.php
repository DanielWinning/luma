<?php

namespace App\Security\Controller;

use App\Security\Entity\Role;
use App\Security\Entity\User;
use App\Security\Services\AuthenticationHelper;
use Luma\AuroraDatabase\Utils\Collection;
use Luma\Framework\Controller\LumaController;
use Luma\Framework\Luma;
use Luma\Framework\Messages\FlashMessage;
use Luma\HttpComponent\HttpClient;
use Luma\HttpComponent\Request;
use Luma\HttpComponent\Response;
use Luma\SecurityComponent\Authentication\Password;
use Tracy\Debugger;
use Tracy\ILogger;

class SecurityController extends LumaController
{
    private AuthenticationHelper $authenticationHelper;
    private HttpClient $httpClient;

    /**
     * @param AuthenticationHelper $authenticationHelper
     * @param HttpClient $httpClient
     */
    public function __construct(AuthenticationHelper $authenticationHelper, HttpClient $httpClient)
    {
        parent::__construct();
        $this->authenticationHelper = $authenticationHelper;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function login(Request $request): Response
    {
        if ($this->authenticationHelper->isAlreadyLoggedIn('login')) {
            return $this->redirect('/');
        }

        if ($request->getMethod() === 'POST') {
            if (!$request->get(User::getSecurityIdentifier()) || !$request->get('password')) {
                $this->addFlashMessage(
                    new FlashMessage(sprintf('Invalid request, please set %s and password.', User::getSecurityIdentifier())),
                    FlashMessage::ERROR
                );

                return $this->redirect('/login');
            }

            $loginResult = Luma::getAuthenticator()->login(
                $request->get(User::getSecurityIdentifier()),
                $request->get('password')
            );

            if (!$loginResult->isAuthenticated()) {
                $this->addFlashMessage(
                    new FlashMessage('Invalid credentials, please check and try again.'),
                    FlashMessage::ERROR,
                );
            } else {
                $this->addFlashMessage(
                    new FlashMessage('Successfully logged in, welcome back.'),
                    FlashMessage::SUCCESS
                );

                return $this->redirect('/');
            }
        }

        return $this->render('security/login');
    }

    /**
     * @return Response
     */
    public function logout(): Response
    {
        if ($this->getLoggedInUser()) {
            Luma::getAuthenticator()->logout();

            $this->addFlashMessage(
                new FlashMessage('You have successfully logged out of your account.'),
                FlashMessage::INFO
            );
        }

        return $this->redirect('/login');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function register(Request $request): Response
    {
        if ($this->authenticationHelper->isAlreadyLoggedIn('registration')) {
            return $this->redirect('/');
        }

        if ($request->getMethod() === 'POST') {
            $emailAddress = $request->get('emailAddress');
            $username = $request->get('username');
            $password = $request->get('password');
            $repeatPassword = $request->get('repeatPassword');

            if (!$emailAddress || !$username || !$password || !$repeatPassword) {
                $this->addFlashMessage(
                    new FlashMessage('Invalid request data, please fill in all required fields.'),
                    FlashMessage::ERROR
                );

                return $this->redirect('/register');
            }

            if ($password !== $repeatPassword) {
                $this->addFlashMessage(
                    new FlashMessage('Passwords do not match.'),
                    FlashMessage::ERROR
                );

                return $this->redirect('/register');
            }

            try {
                $existingUserByEmailAddress = User::select(['emailAddress'])->whereIs('emailAddress', $emailAddress)->get();
                $existingUserByUsername = User::select(['username'])->whereIs('username', $username)->get();
                $redirectWithErrors = false;

                if ($existingUserByUsername) {
                    $this->addFlashMessage(
                        new FlashMessage('A user already exists with this username.'),
                        FlashMessage::ERROR
                    );
                    $redirectWithErrors = true;
                }

                if ($existingUserByEmailAddress) {
                    $this->addFlashMessage(
                        new FlashMessage('A user already exists with this email address.'),
                        FlashMessage::ERROR
                    );
                    $redirectWithErrors = true;
                }

                if ($redirectWithErrors) {
                    return $this->redirect('/register');
                }

                $user = User::create([
                    'username' => $username,
                    'emailAddress' => $emailAddress,
                    'password' => Password::hash($password),
                    'roles' => new Collection([
                        Role::select()->whereIs('handle', 'user')->get(),
                    ]),
                ]);

                $user->save();

                Luma::getAuthenticator()->login($emailAddress, $password);

                return $this->redirect('/');
            } catch (\Exception $exception) {
                $this->addFlashMessage(
                    new FlashMessage('Something went wrong, please try again.'),
                    FlashMessage::ERROR
                );
                Debugger::log($exception->getMessage(), ILogger::EXCEPTION);
            }
        }

        return $this->render('security/register');
    }
}