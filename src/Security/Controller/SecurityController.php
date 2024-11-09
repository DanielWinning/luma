<?php

namespace App\Security\Controller;

use App\Security\Entity\Role;
use App\Security\Entity\User;
use Luma\AuroraDatabase\Utils\Collection;
use Luma\Framework\Controller\LumaController;
use Luma\Framework\Luma;
use Luma\Framework\Messages\FlashMessage;
use Luma\HttpComponent\Request;
use Luma\HttpComponent\Response;
use Luma\RoutingComponent\Attribute\RequireUnauthenticated;
use Luma\SecurityComponent\Authentication\Password;
use Tracy\Debugger;
use Tracy\ILogger;

class SecurityController extends LumaController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    #[RequireUnauthenticated]
    public function register(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $emailAddress = $request->get('emailAddress');
            $password = $request->get('password');
            $repeatPassword = $request->get('repeatPassword');

            if (!$emailAddress || !$password || !$repeatPassword) {
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
                $redirectWithErrors = false;

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