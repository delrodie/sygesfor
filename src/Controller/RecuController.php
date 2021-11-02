<?php

namespace App\Controller;

use App\Entity\Candidater;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recu")
 */
class RecuController extends AbstractController
{
	private $session;
	
	public function __construct(SessionInterface $session)
	{
		$this->session = $session;
	}
	
    /**
     * @Route("/{matricule}", name="recu_paiement")
     */
    public function index(Request $request, $matricule): Response
    {
		$session = $this->session->get('candidater');
		if ($session)
			$candidater = $this->getDoctrine()->getRepository(Candidater::class)->findOneBy(['matricule'=>$matricule]);
		else
			return $this->forward('App\Controller\HomeController::index');
		
        return $this->render('recu/index.html.twig', [
            'candidater' => $candidater,
        ]);
    }
}
