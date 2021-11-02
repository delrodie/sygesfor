<?php
	
	namespace App\Utilities;
	
	use App\Repository\ActiviteRepository;
	use Doctrine\ORM\EntityManagerInterface;
	
	class GestionActivite
	{
		private $_em;
		private $activiteRepository;
		
		public function __construct(EntityManagerInterface $_em, ActiviteRepository $activiteRepository)
		{
			$this->_em = $_em;
			$this->activiteRepository = $activiteRepository;
		}
		
		public function activiteEnCours()
		{
		
		}
	}
