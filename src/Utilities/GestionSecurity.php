<?php
	
	namespace App\Utilities;
	
	use App\Entity\User;
	use App\Repository\UserRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
	use Symfony\Component\Security\Core\Encoder\PasswordHasherEncoder;
	use Symfony\Component\Security\Core\Security;
	
	class GestionSecurity
	{
		private $_em;
		private $security;
		private $userRepository;
		private $hasherEncoder;
		
		public function __construct(EntityManagerInterface $_em, Security $security, UserPasswordHasherInterface $hasherEncoder,UserRepository $userRepository)
		{
			$this->_em = $_em;
			$this->security = $security;
			$this->userRepository = $userRepository;
			$this->hasherEncoder = $hasherEncoder;
		}
		
		/**
		 * Initialisation du compte SUPER ADMIN
		 *
		 * @return bool
		 */
		public function initialisation(): bool
		{
			$verif = $this->userRepository->findOneBy(['username'=>'delrodie']);
			$result = false;
			if (!$verif){
				$user = new User();
				$user->setUsername('delrodie');
				$user->setEmail('delrodieamoikon@gmail.com');
				$user->setPassword($this->hasherEncoder->hashPassword($user, 'conafor1'));
				$user->setRoles(['ROLE_SUPER_ADMIN']);
				
				$this->_em->persist($user);
				$this->_em->flush();
				
				$result = true;
			}
			
			return $result;
		}
		
		/**
		 * Mise a jour de la table utilisateur
		 *
		 * @return bool
		 */
		public function connexion(): bool
		{
			$user = $this->userRepository->findOneBy(['username'=>$this->security->getUser()->getUserIdentifier()]);
			
			$nombre_connexion = (int)$user->getLoginCount();
			$user->setLoginCount($nombre_connexion + 1);
			$user->setLastConnectedAt(new \DateTime());
			
			$this->_em->flush();
			
			return true;
		}
	}
