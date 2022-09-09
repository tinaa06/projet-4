<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Entity\Commande;
use App\Form\ResaChambreType;
use App\Form\AdminCommandeType;
use App\Repository\ChambreRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(ChambreRepository $repo): Response
    {
        return $this->render('main/index.html.twig', [
            'chmbs' => $repo->findAll()
        ]);
    }

    #[Route('/main/resa/{id}', name : 'resa_chambre')]
    public function resa(Chambre $chambre = null, EntityManagerInterface $manager, Request $rq)
    {
        if ($this->getUser()){
        if (!$chambre)
            return $this->redirectToRoute('app_main');

        $commande = new Commande;
        $form = $this->createForm(ResaChambreType::class, $commande);
        $form->handleRequest($rq);
      
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $commande->setUser($user);
            $commande->setNom($user->getNom());
            $commande->setPrenom($user->getPrenom());
            $commande->setEmail($user->getEmail());
            $commande->setDateEnregistrement(new \DateTime());
            $commande->setChambre($chambre);

            $depart = $commande->getDateArrivee();
            if ($depart->diff($commande->getDateDepart())->invert == 1) {
                $this->addFlash('danger', 'Une période de temps ne peut pas être négative.');
                return $this->redirectToRoute('resa_chambre', [
                    'id' => $chambre->getId()
                ]);
            }
            $jours = $depart->diff($commande->getDateDepart())->days;
            $prixTotal = ($commande->getChambre()->getPrixJournalier() * $jours) + $commande->getChambre()->getPrixJournalier();
            $commande->setPrixTotal($prixTotal);

            $manager->persist($commande);
            $manager->flush();
            $this->addFlash('success', 'Votre commande a bien été enregistrée !');
            return $this->redirectToRoute('profil');
        }

        return $this->renderForm('main/resa.html.twig', [
            'form' => $form,
            'chmb' => $chambre
        ]);
    }
    else {
        return $this->redirectToRoute('app_login');
    }
    }
      
    #[Route("/main/profil", name:"profil")]
    
    public function profil(CommandeRepository $repo)
    {
        $commandes = $repo->findBy(['user' => $this->getUser()]);

        return $this->render("main/profil.html.twig", [
            'commandes' => $commandes
        ]);
    }
}
