<?php

namespace App\Security\Services;

use App\Security\Entity\User;
use Luma\Framework\Luma;
use Luma\Framework\Messages\FlashMessage;
use Luma\HttpComponent\Request;

class AuthenticationHelper
{
    /**
     * @param string $pageTitle
     *
     * @return bool
     */
    public function isAlreadyLoggedIn(string $pageTitle): bool
    {
        if (!Luma::getLoggedInUser()) {
            return false;
        }

        $_SESSION['messages'][FlashMessage::INFO][] = new FlashMessage(
            sprintf('Cannot access %s page, you are already logged in.', $pageTitle)
        );

        return true;
    }
}