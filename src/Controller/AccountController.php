<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
	private $entityManager;
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}
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

	#[Route('/compte/adresse/ajouter/{id}', name: 'app_account_address_form', defaults: ['id' => null])]
	public function addressForm(Request $request, $id, AddressRepository $addressRepository): Response
	{
		if ($id) {
			$address = $addressRepository->findOneById($id);
		} else {
			$address = new Address();
			$address->addUser($this->getUser());
		}


		$form = $this->createForm(AddressUserType::class, $address);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->entityManager->persist($address);
			$this->entityManager->flush();
			$this->addFlash(
				'success',
				'Votre adresse a bien été ajoutée');

			return $this->redirectToRoute('app_account_addresses');
		}

		return $this->render('account/addressForm.html.twig', [
			'addressForm' => $form->createView(),
		]);
	}

	#[Route('/compte/modif-mdp', name: 'app_account_modify_pwd')]
	public function password(Request $request, UserPasswordHasherInterface $passwordHasher): Response
	{
		$user = $this->getUser();

		$form = $this->createForm(PasswordUserType::class, $user, [
			'passwordHasher' => $passwordHasher,
		]);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->entityManager->flush();
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
