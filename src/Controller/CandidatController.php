<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Candidater;
use App\Entity\Sygesca\Membre;
use App\Utilities\GestionCandidature;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
	    $matricule = $this->session->get('matricule'); //dd($matricule);
	    $candidat = $this->getDoctrine()->getRepository(Membre::class, 'sygesca')->findOneBy(['matricule'=>$matricule]);
	    
		if($this->_candidature->verifCandidature($matricule)){
			$this->addFlash('danger', 'Attention vous avez déjà postulé à cette formation. Si vous pensez que c\'est une erreur merci de contacter le CONAFOR.');
			return $this->render('candidat/existe.html.twig');
		}
		
		if (!$matricule){
			$this->addFlash('danger', "Aucun matricule trouver");
			return $this->redirectToRoute('app_home');
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
	
	/**
	 * @Route("/paiement/", name="candidat_paiement", methods={"GET","POST"})
	 */
	public function paiement(Request $request)
	{
		$matricule = $this->session->get('matricule');
		if (!$matricule) return $this->redirectToRoute('app_home');
		
		$activite = $this->getDoctrine()->getRepository(Activite::class)->findActiviteEnCour();
		$candidater = $this->getDoctrine()->getRepository(Candidater::class)->findCandidatureValidee($matricule, $activite->getId());
		
		$montant = $activite->getMontant();
		$am = (int) $montant/(1 - 0.035);
		$am = $this->_candidature->arrondiSuperieur($am, 5);
		
		return $this->render('candidat/paiement.html.twig',[
			'candidater' => $candidater,
			'montant' => $am
		]);
	}
	
	/**
	 * @Route("/paiement/validation", name="candiat_paiement_validation", methods={"GET","POST"})
	 */
	public function validation(Request $request): JsonResponse
	{
		//Initialisation
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer = new Serializer($normalizers, $encoders);
		
		$candidater = $request->get('candidater');
		if ($candidater)
			$result = $this->_candidature->paiement($candidater);
		else
			return $this->json(['status'=>false]);
		
		//dd($this->json($result));
		//$this->session->clear();
		
		return $this->json($result);
	}
	
	
}
