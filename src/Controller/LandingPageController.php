<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    #[Route(path: '/', name: 'landing_page')]
    public function index(): Response
    {
        return $this->redirectToRoute('admin_category');
    }
}
