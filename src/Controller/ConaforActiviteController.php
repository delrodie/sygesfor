<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conafor/activite")
 */
class ConaforActiviteController extends AbstractController
{
    /**
     * @Route("/", name="conafor_activite_index", methods={"GET"})
     */
    public function index(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('conafor_activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="conafor_activite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
			$slugify = new Slugify();
			$slug = $slugify->slugify($activite->getNom());
			$activite->setSlug($slug); //dd($activite);
            $entityManager->persist($activite);
            $entityManager->flush();
			
			$this->addFlash('success', 'La formation '.$activite->getNom().' a été enregistrée avec succès!');

            return $this->redirectToRoute('conafor_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conafor_activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="conafor_activite_show", methods={"GET"})
     */
    public function show(Activite $activite): Response
    {
        return $this->render('conafor_activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="conafor_activite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Activite $activite): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        $slugify = new Slugify();
	        $slug = $slugify->slugify($activite->getNom());
	        $activite->setSlug($slug);
			
	        $this->getDoctrine()->getManager()->flush();
	
	        $this->addFlash('success', 'La formation '.$activite->getNom().' a été modifiée avec succès!');

            return $this->redirectToRoute('conafor_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conafor_activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="conafor_activite_delete", methods={"POST"})
     */
    public function delete(Request $request, Activite $activite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('conafor_activite_index', [], Response::HTTP_SEE_OTHER);
    }
}
