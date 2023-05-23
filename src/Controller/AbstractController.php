<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    protected function getUser(): ?UserInterface
    {
        $user = parent::getUser();
        if ($user instanceof UserInterface) {
            return $user;
        }

        return null;
    }

    protected function getRequestContent(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }
}
