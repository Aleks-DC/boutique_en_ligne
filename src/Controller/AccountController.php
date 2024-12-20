<?php

namespace App\Controller;

use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

	#[Route('/compte/adresses', name: 'app_account_addresses')]
	public function addresses(): Response
	{
		return $this->render('account/adresses.html.twig');
	}

	#[Route('/compte/adresse/ajouter', name: 'app_account_address_form')]
	public function addressForm(Request $request): Response
	{
		$form = $this->createForm(AddressUserType::class);

		$form->handleRequest($request);

		return $this->render('account/addressForm.html.twig', [
			'addressForm' => $form->createView(),
		]);
	}

	#[Route('/compte/modif-mdp', name: 'app_account_modify_pwd')]
	public function password(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
	{
		$user = $this->getUser();

		$form = $this->createForm(PasswordUserType::class, $user, [
			'passwordHasher' => $passwordHasher,
		]);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->flush();

			$this->addFlash(
				'success',
				'Votre mot de passe a bien été modifié');

			return $this->redirectToRoute('app_account');
		}


		return $this->render('account/password.html.twig', [
			'modifyPwd' => $form->createView(),
		]);
	}
}
