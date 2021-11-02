<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Candidat;
use App\Entity\Candidater;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use App\Utilities\GestionCandidature;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conafor/candidat")
 */
class ConaforCandidatController extends AbstractController
{
	private $_candidature;
	
	public function __construct(GestionCandidature $_candidature)
	{
		$this->_candidature = $_candidature;
	}
	
    /**
     * @Route("/", name="conafor_candidat_index", methods={"GET"})
     */
    public function index(CandidatRepository $candidatRepository): Response
    {
		$candidats  = $this->_candidature->listeCandidat(); //dd($candidats);
        return $this->render('conafor_candidat/index.html.twig', [
            'candidats' => $candidats,
	        'activites' => $this->getDoctrine()->getRepository(Activite::class)->findAll()
        ]);
    }

    /**
     * @Route("/new", name="conafor_candidat_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($candidat);
            $entityManager->flush();

            return $this->redirectToRoute('conafor_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conafor_candidat/new.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="conafor_candidat_show", methods={"GET"})
     */
    public function show(Candidat $candidat): Response
    {
		//dd($candidat);
	    $activite = $this->getDoctrine()->getRepository(Activite::class)->findActiviteEnCour();
		$candidater = $this->getDoctrine()->getRepository(Candidater::class)
			->findOneBy(['activite'=>$activite->getId(), 'candidat'=>$candidat->getId()]);
        return $this->render('conafor_candidat/show.html.twig', [
            'candidat' => $candidat,
	        'activite' => $activite,
	        'candidater' => $candidater
        ]);
    }

    /**
     * @Route("/{id}/edit", name="conafor_candidat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Candidat $candidat): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('conafor_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conafor_candidat/edit.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="conafor_candidat_delete", methods={"POST"})
     */
    public function delete(Request $request, Candidat $candidat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($candidat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('conafor_candidat_index', [], Response::HTTP_SEE_OTHER);
    }
}
