<?php
	
	namespace App\Utilities;
	
	use App\Entity\Activite;
	use App\Entity\Candidat;
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
		
		public function formulaire($request, $candidat)
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
			
			return true;
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
