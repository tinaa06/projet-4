<?php

namespace App\Controller;

use App\Repository\ChambreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HotelhouseController extends AbstractController
{
    #[Route('/hotelhouse', name: 'app_hotelhouse')]
    public function index(ChambreRepository $repo): Response
    {
        $chambre = $repo->findAll();
        return $this->render('hotelhouse/index.html.twig', [
            'tabChambres' => $chambre,
        ]);
    }

    #[Route('/', name: 'app_home')]
    public function home(ChambreRepository $repo) : Response
    {
        $chambre = $repo->findAll();
        return $this->render('hotelhouse/home.html.twig',[
            'tabChambres' => $chambre,
        ]);
    }
    
}
