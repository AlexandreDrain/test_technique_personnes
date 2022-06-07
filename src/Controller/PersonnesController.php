<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneFormType;
use App\Repository\PersonneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonnesController extends AbstractController
{
    /**
     * @Route("/personnes", name="app_personnes")
     */
    public function index(PersonneRepository $personneRepository)
    {
        return $this->render("personne/index.html.twig", [
            "personnes" => $personneRepository->findBy([], ["lastname" => "ASC"])
        ]);
    }

    /**
     * @Route("/personnes/ajouter", name="app_add_personnes")
     */
    public function add(Request $request): Response
    {
        // Création du formulaire basé sur la classe Stories
        $personne = new Personne();
        $form = $this->createForm(PersonneFormType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($personne);
            $entityManager->flush();

            $this->addFlash('success', "Personne bien enregistrée");
            return $this->redirectToRoute("app_home");
        }

        return $this->render('personne/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
