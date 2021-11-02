<?php

namespace App\Controller;

use App\Entity\Sygesca\Membre;
use App\Utilities\GestionCandidature;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/candidat")
 */
class CandidatController extends AbstractController
{
	private $session;
	private $_candidature;
	
	public function __construct(SessionInterface $session, GestionCandidature $_candidature)
	{
		$this->session = $session;
		$this->_candidature = $_candidature;
	}
	
    /**
     * @Route("/", name="candidat_index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
	    $matricule = $this->session->get('matricule');
	    $candidat = $this->getDoctrine()->getRepository(Membre::class, 'sygesca')->findOneBy(['matricule'=>$matricule]);
	    
		if($this->_candidature->verifCandidature($matricule)){
			$this->addFlash('danger', 'Attention vous avez déjà postulé à cette formation. Si vous pensez que c\'est une erreur merci de contacter le CONAFOR.');
			return $this->render('candidat/existe.html.twig');
		}
	    
		$this->session->clear();
        return $this->render('candidat/index.html.twig', [
			'candidat' => $candidat,
        ]);
    }
	
	/**
	 * @Route("/new", name="candidat_new", methods={"GET","POST"})
	 */
	public function new(Request $request)
	{
		$candidat = $this->getDoctrine()->getRepository(Membre::class, 'sygesca')->findOneBy(['matricule'=>$request->get('scout_candidat')]);
		$this->_candidature->formulaire($request, $candidat);
		
		$this->addFlash('success', 'Votre candidature a bien été envoyée. Vous recevrez un email de confirmation');
		
		return $this->render('candidat/new.html.twig',[
		
		]);
	}
}
