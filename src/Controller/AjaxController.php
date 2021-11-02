<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Candidater;
use App\Entity\Sygesca\Membre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/ajax")
 */
class AjaxController extends AbstractController
{
	private $session;
	
	public function __construct(SessionInterface $session)
	{
		$this->session = $session;
	}
	
    /**
     * @Route("/{matricule}", name="requete_ajax_matricule", methods={"GET","POST"})
     */
    public function index(Request $request, $matricule): Response
    {
	    //Initialisation
	    $encoders = [new XmlEncoder(), new JsonEncoder()];
	    $normalizers = [new ObjectNormalizer()];
	    $serializer = new Serializer($normalizers, $encoders);
		
		$data = $this->getDoctrine()->getRepository(Membre::class, 'sygesca')->findOneBy(['matricule'=>$matricule]);
		
		$this->session->set('matricule', $matricule);
		//$this->session->set('candidat', $data['matricule']);
		
        return $this->json($data);
    }
	
	/**
	 * @Route("/{matricule}/paiement", name="requete_ajax_paiement", methods={"GET","POST"})
	 */
	public function paiement(Request $request, $matricule)
	{
		//Initialisation
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer = new Serializer($normalizers, $encoders);
		
		$activite = $this->getDoctrine()->getRepository(Activite::class)->findActiviteEnCour();
		$candidater = $this->getDoctrine()->getRepository(Candidater::class)->findCandidature($matricule, $activite->getId());
		if ($candidater){
			if ($candidater->getValidation())
				$data = [
					'id' => $candidater->getId(),
					'status' => true,
				];
			else
				$data = [
					'id' => $candidater->getId(),
					'status' => false,
				];
		}else
			$data = [
				'id' => null,
				'status' => false
			];
		
		
		$this->session->set('matricule', $matricule); //dd($this->session->get('matricule'));
		
		return $this->json($data);
		
	}
}
