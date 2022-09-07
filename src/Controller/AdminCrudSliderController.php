<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCrudSliderController extends AbstractController
{
    #[Route('/admin/crud/slider', name: 'app_admin_crud_slider')]
    public function index(): Response
    {
        return $this->render('admin_crud_slider/index.html.twig', [
            'controller_name' => 'AdminCrudSliderController',
        ]);
    }
}
