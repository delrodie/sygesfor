<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Utilities\GestionSecurity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	private $_security;
	private $passwordHasher;
	
	public function __construct(GestionSecurity $_security, UserPasswordHasherInterface $passwordHasher)
	{
		$this->_security = $_security;
		$this->passwordHasher = $passwordHasher;
	}
	
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
	    
	    if ($this->_security->initialisation())
			$this->addFlash('success', "Utilisateur 'conafor1' initiaisé avec succès");

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
	
	/**
	 * @Route("/change-password/", name="app_password_change")
	 */
	public function password(Request $request)
	{
		$user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=>$this->getUser()->getUserIdentifier()]);
		$form = $this->createForm(ChangePasswordType::class, $user);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()){
			// Encode du nouveau mot de passe
			if ($user->getPassword()){
				$password = $this->passwordHasher->hashPassword($user, $user->getPassword());
				$user->setPassword($password);
			}
			$this->getDoctrine()->getManager()->flush();
			
			$this->addFlash('success', "Vos informations ont été mises à jour avec succès. Veuillez-vous reconnecter");
			
			return $this->redirectToRoute('app_login');
		}
		
		return $this->renderForm('security/password.html.twig',[
			'form' => $form,
			'user' => $user
		]);
	}
}
