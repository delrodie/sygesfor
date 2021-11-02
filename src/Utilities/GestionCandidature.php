<?php
	
	namespace App\Utilities;
	
	use App\Entity\Activite;
	use App\Entity\Candidat;
	use App\Entity\Candidater;
	use App\Entity\Sygesca\Membre;
	use App\Entity\Sygesca\Region;
	use Doctrine\ORM\EntityManagerInterface;
	
	class GestionCandidature
	{
		private $em;
		
		public function __construct(EntityManagerInterface $em)
		{
			$this->em = $em;
		}
		
		public function verifCandidature($matricule): bool
		{
			$activite = $this->em->getRepository(Activite::class)->findActiviteEnCour(); //dd($activite);
			$verif = $this->em->getRepository(Candidater::class)->findCandidature($matricule, $activite->getId());
			if ($verif) $result = true;
			else $result = false;
			
			return $result;
		}
		
		public function formulaire($request, $candidat): bool
		{
			$participant = [
				'nom' => $candidat->getNom(),
				'prenoms' => $candidat->getPrenoms(),
				'dateNaissance' => $candidat->getDateNaissance(),
				'lieuNaissance' => $candidat->getLieuNaissance(),
				'sexe' => $candidat->getSexe(),
				'fonction' => $candidat->getFonction(),
				'slug' => $candidat->getSlug(),
				'matricule' => $candidat->getMatricule(),
				'carteScoute' => $candidat->getCarte(),
				'region' => $candidat->getGroupe()->getDistrict()->getRegion(),
				'dateEntree' => $this->validForm($request->get('scout_dateentree')),
				'nieauEtude' => $this->validForm($request->get('scout_niveau_etude')),
				'profession' => $this->validForm($request->get('scout_profession')),
				'residence' => $this->validForm($request->get('scout_residence')),
				'email' => $this->validForm($request->get('scout_email')),
				'contact' => $this->validForm($request->get('scout_contact')),
			];
			$region = $this->em->getRepository(Region::class)->findOneBy(['id'=>$participant['region']]);
			//dd($region);
			$candidature = new Candidat();
			$candidature->setNom($participant['nom']);
			$candidature->setPrenoms($participant['prenoms']);
			$candidature->setMatricule($participant['matricule']);
			$candidature->setCarteScoute($participant['carteScoute']);
			$candidature->setDateNaissance($participant['dateNaissance']);
			$candidature->setLieuNaissance($participant['lieuNaissance']);
			$candidature->setFonction($participant['fonction']);
			$candidature->setDateEntree($participant['dateEntree']);
			$candidature->setNiveauEtude($participant['nieauEtude']);
			$candidature->setProfession($participant['profession']);
			$candidature->setResidence($participant['residence']);
			$candidature->setEmail($participant['email']);
			$candidature->setContact($participant['contact']);
			$candidature->setSlug($participant['slug']);
			$candidature->setRegion($region);
			$candidature->setSexe($participant['sexe']); //dd($candidature);
			
			$this->em->persist($candidature);
			$this->em->flush();
			
			$activite = $this->em->getRepository(Activite::class)->findActiviteEnCour();
			$candidater = new Candidater();
			$candidater->setActivite($activite);
			$candidater->setCandidat($candidature);
			
			$this->em->persist($candidater);
			$this->em->flush();
			
			return true;
		}
		
		public function listeCandidat(): array
		{
			$activite = $this->em->getRepository(Activite::class)->findActiviteEnCour();
			$candidats = $this->em->getRepository(Candidater::class)->findListCandidatByActivite($activite->getId());
			$list=[]; $i=0; //dd($candidats);
			foreach ($candidats as $candidat){
				$list[$i++]=[
					'matricule' => $candidat->getCandidat()->getMatricule(),
					'carte' => $candidat->getCandidat()->getCarteScoute(),
					'nom' => $candidat->getCandidat()->getNom(),
					'prenoms' => $candidat->getCandidat()->getPrenoms(),
					'date_naissance' => $candidat->getCandidat()->getDateNaissance(),
					'lieu' => $candidat->getCandidat()->getLieuNaissance(),
					'fonction' => $candidat->getCandidat()->getFonction(),
					'email' => $candidat->getCandidat()->getEmail(),
					'contact' => $candidat->getcandidat()->getContact(),
					'slug' => $candidat->getCandidat()->getSlug(),
					'region' => $candidat->getCandidat()->getRegion()->getNom(),
					'sexe' => $candidat->getCandidat()->getSexe(),
					'activite_nom' => $candidat->getActivite()->getNom(),
					'activite_id' => $candidat->getActivite()->getId(),
					'activite_slug' => $candidat->getActivite()->getSlug(),
					'validation' => $candidat->getValidation(),
					'date_entree' => $candidat->getCandidat()->getDateEntree(),
					'validation' => $candidat->getValidation()
				];
			}
			return $list;
		}
		
		/**
		 * Mise a jour de la table Candidater après validation
		 *
		 * @param $candidat
		 * @param $activite
		 * @return bool
		 */
		public function validation($candidat,$activite): bool
		{
			$candidater = $this->em->getRepository(Candidater::class)->findCandidature($candidat,$activite);
			$candidater->setValidation(true);
			$this->em->flush();
			
			// Envoie de mail au candidat
			
			return true;
		}
		
		/**
		 * Mise a jour de la table Candidater et affichage
		 *
		 * @param $candidaterID
		 * @return array
		 */
		public function paiement($candidaterID)
		{
			// Variables
			$id_transaction = time().''.substr(uniqid("",true), -9, 9);
			$status_paiement = "UNKNOW";
			
			$candidater = $this->em->getRepository(Candidater::class)->findOneBy(['id'=>$candidaterID]);
			
			$montant = $candidater->getActivite()->getMontant();
			$am = (int) $montant/(1 - 0.035);
			$am = $this->arrondiSuperieur($am, 5);
			
			//$candidater->setIdTransaction($id_transaction);
			$candidater->setStatusPaiement($status_paiement);
			$candidater->setMontant($am);
			
			$this->em->flush();
		
			$data= [
				'id' => $candidater->getId(),
				'validation' => $candidater->getValidation(),
				'id_transaction' => $candidater->getIdTransaction(),
				'montant' => $candidater->getMontant(),
				'activite' => $candidater->getActivite()->getNom(),
				'nom' => $candidater->getCandidat()->getNom(),
				'prenoms' => $candidater->getCandidat()->getPrenoms(),
				'region' => $candidater->getCandidat()->getRegion()->getNom(),
			];
			
			return $data;
		}
		
		/**
		 * Paiement cinetpay
		 *
		 * @param $data
		 * @return Candidater|mixed|object|null
		 */
		public function cinetpay($data)
		{
			$candidate = $this->em->getRepository(Candidater::class)->findOneBy(['id'=>$data['candidate']]);
			$candidate->setResponseId($data['response_id']);
			$candidate->setToken($data['token']);
			$candidate->setUrl($data['url']);
			$this->em->flush();
			
			return $candidate;
		}
		
		/**
		 * Fonction pour arrondir au supérieur
		 *
		 * @param $nombre
		 * @param $arrondi
		 * @return float|int
		 */
		public function arrondiSuperieur($nombre, $arrondi)
		{
			return ceil($nombre / $arrondi) * $arrondi;
		}
		
		/**
		 * fonction verification des valeurs
		 *
		 * @param $donnee
		 * @return string
		 */
		public function validForm($donnee)
		{
			$result = htmlspecialchars(stripslashes(trim($donnee)));
			
			return $result;
		}
	}
