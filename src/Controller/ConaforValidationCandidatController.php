<?php

namespace App\Controller;

use App\Utilities\GestionCandidature;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conafor/validation")
 */
class ConaforValidationCandidatController extends AbstractController
{
	private $_candidature;
	
	public function __construct(GestionCandidature $_candidature)
	{
		$this->_candidature = $_candidature;
	}
	
    /**
     * @Route("/", name="conafor_validation_candidat_index")
     */
    public function index(Request $request): Response
    {
		$candidat = $request->get('candidat');
		$activite = $request->get('activite');
		$this->_candidature->validation($candidat,$activite);
		
        return $this->redirectToRoute('conafor_candidat_index');
    }
}
