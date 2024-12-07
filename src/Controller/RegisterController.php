<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
		$user = new User();

		// Création du formulaire
		$form = $this->createForm(RegisterUserType::class, $user);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($user);
			$entityManager->flush();
		}

		// Si le formulaire est soumis alors :
		// Tu enregistres les datas en BDD
		// Tu envoies un message de confirmation de création de compte
		// Tu rediriges l'utilisateur vers la page de connexion

        return $this->render('register/index.html.twig', [
			'registerForm' => $form->createView()
		]);
    }
}
