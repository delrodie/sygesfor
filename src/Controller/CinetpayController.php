<?php

namespace App\Controller;

use App\Entity\Candidater;
use App\Utilities\GestionCandidature;
use CinetPay\CinetPay;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/cinetpay")
 */
class CinetpayController extends AbstractController
{
	private $_candidature;
	private $em;
	private $client;
	
	public function __construct(GestionCandidature $_candidature, EntityManagerInterface $em, HttpClientInterface $client)
	{
		$this->_candidature = $_candidature;
		$this->em = $em;
		$this->client = $client;
	}
	
    /**
     * @Route("/", name="cinetpay_paiement")
     */
    public function index(Request $request): Response
    {
	    //Initialisation
	    $encoders = [new XmlEncoder(), new JsonEncoder()];
	    $normalizers = [new ObjectNormalizer()];
	    $serializer = new Serializer($normalizers, $encoders);
		
		$data = [
			'token' => $request->get('token'),
			'candidate' => $request->get('candidater'),
			'response_id' => $request->get('api_response_id'),
			'url' => $request->get('url')
		];
		
		$result = $this->_candidature->cinetpay($data);
		
		
		return $this->json($result);
    }
	
	/**
	 * @Route("/notify", name="cinetpay_notify", methods={"GET","POST"})
	 */
	public function notify(Request $request)
	{
		//Initialisation
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer = new Serializer($normalizers, $encoders);
		
		$cpmTransId = $request->get('cpm_trans_id'); //dd($cpmTransId);
		if (isset($cpmTransId)){
			try {
				$id_transaction = $cpmTransId;
				$url = 'https://api-checkout.cinetpay.com/v2/payment/check';
				$apiKey = '18714242495c8ba3f4cf6068.77597603';
				$site_id = 422630;
				$plateform = "PROD"; // Valorisé à PROD si vous êtes en production
				
				// Verification du status de l'opération
				$candidater = $this->em->getRepository(Candidater::class)->findOneBy(['idTransaction'=>$cpmTransId]);
				//dd($candidater);
				if ($candidater){
					if ($candidater->getStatusPaiement() === 'VALID'){
						$message = [
							'status' => false,
							'matricule' => $candidater->getCandidat()->getMatricule()
						];
					}else{
						$data = [
							'apikey' => $apiKey,
							'site_id' => $site_id,
							'token' => $candidater->getToken()
						];
						
						// Creation d'option
						$options = [
							'http' => [
								'method' =>"POST",
								'header' => "Content-Type: application/json\r\n",
								//'ignore_errors' => true,
								'content' => json_encode($data)
							]
						]; //dd($options);
						
						// Creation du context
						$context = stream_context_create($options); //dd($context);
						
						// Execution de la requete
						$result =  file_get_contents('https://api-checkout.cinetpay.com/v2/payment/check', false, $context);
						
						$donnee = json_decode($result); //dd($donnee);
						if ($donnee->code === '00'){
							$candidater->setStatusPaiement('VALID');
							$candidater->setOperateurMobile($donnee->data->payment_method);
							$candidater->setOperatorId($donnee->data->operator_id);
							$candidater->setPaymentDate($donnee->data->payment_date);
							$this->em->flush();
							$view = $this->render('cinetpay/index.html.twig',[
								'candidate' =>  $candidater
							]);
						}elseif ($donnee->code === '602'){
							$this->addFlash('danger', 'Le solde de paiement est insuffisant. Merci de recharger ton compte.');
							$view = $this->render('cinetpay/echec.html.twig');
						}else{
							$this->addFlash('danger', 'Le paiement a échoué. Prière reprendre');
							$view = $this->render('cinetpay/echec.html.twig');
						}
					}
				}
				
			}catch (\Exception $e){
				echo "Erreur :". $e->getMessage();
				$this->addFlash('danger', "Erreur : ".$e->getMessage());
				$view = $this->render('cinetpay/echec.html.twig');
			}
		}else{
			throw new \Exception("Echec! Page inaccessible.!");
			$this->addFlash('danger', 'Echec: Page inaccessible.!');
			$view = $this->render('cinetpay/echec.html.twig');
		}
		
		return $view;
	}
}
