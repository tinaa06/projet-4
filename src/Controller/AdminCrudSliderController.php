<?php

namespace App\Controller;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCrudSliderController extends AbstractController
{
    #[Route('/admin/crud/slider', name: 'app_admin_crud_slider')]
    public function index(SliderRepository $repo, EntityManagerInterface $manager): Response
    {
        $colonnes = $manager->getClassMetadata(Slider::class)->getFieldNames();
        $slds = $repo->findAll();
        return $this->render('admin_crud_slider/index.html.twig', [
            'colonnes' => $colonnes,
            'slds' => $slds
        ]);
    }

    #[Route('/admin/crud/slider/new', name : 'admin_crud_slider_new')]
    #[Route('/admin/crud/slider/edit/{id}', name: 'admin_crud_slider_edit')]

    public function form(Slider $slider = null, Request $rq, EntityManagerInterface $manager)
    {
        if (!$slider)
        {
            $slider = new Slider;
            $slider->setDateEnregistrement(new \DateTime());
        }
        $form = $this->createForm(SliderType::class, $slider);

        $form->handleRequest($rq);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($slider);
            $manager->flush();
            return $this->redirectToRoute('app_admin_crud_slider');
        }
        return $this->renderForm('admin_crud_slider/form.html.twig', [
            'form' => $form,
            'editMode' => $slider->getId() != NULL
        ]);
    }

      #[Route('/admin/crud/slider/delete/{id}', name:'admin_crud_slider_delete')]

    public function delete(Slider $slider = null, EntityManagerInterface $manager)
    {
        if ($slider) {
            $manager->remove($slider);
            $manager->flush();
            $this->addFlash('success', 'Le slider a bien été supprimé !');
        }
        return $this->redirectToRoute('app_admin_crud_slider');
    }

}
