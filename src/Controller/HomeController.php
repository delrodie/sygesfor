<?php

namespace App\Controller;

use App\Entity\Activite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
		
        return $this->render('home/index.html.twig', [
            'activite' => $this->getDoctrine()->getRepository(Activite::class)->findActiviteEnCour(),
        ]);
    }
	
	/**
	 * @Route("/echec", name="app_recherche_echec")
	 */
	public function echec(Request $request, SessionInterface $session)
	{
		$matricule = $session->get('matricule');
		$session->clear();
		return $this->render('home/echec.html.twig',[
			'matricule' => $matricule
		]);
	}
}
