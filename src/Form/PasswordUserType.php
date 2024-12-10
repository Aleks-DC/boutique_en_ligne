<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
			->add('actualPassword', PasswordType::class, [
				'label' => 'Mot de passe actuel',
				'attr' => [
					'placeholder' => 'xxxxxxxx'
				],
				'mapped' => false
			])
			->add('plainPassword', RepeatedType::class, [
				'type' => PasswordType::class,
				'constraints' => [
					new Length([
						'min' => 8,
						'minMessage' => 'Votre mot de passe doit contenir au moins 8 caractères',
						'max' => 30,
						'maxMessage' => 'Votre mot de passe doit contenir au maximum 30 caractères'
					]),
					new Regex([
						'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
						'message' => 'Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre, et un caractère spécial',
					])
				],
				'first_options'  => [
					'label' => 'Nouveau mot de passe',
					'attr' => [
						'placeholder' => 'xxxxxxxx'
					],
					'hash_property_path' => 'password'
				],
				'second_options' => [
					'label' => 'Confirmez votre mot de passe',
					'attr' => [
						'placeholder' => 'xxxxxxxx'
					]
				],
				'mapped' => false,
			])
			->add('submit', SubmitType::class, [
				'label' => 'Valider',
				'attr' => [
					'class' => 'btn btn-success'
				]])
			->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
				$form = $event->getForm();
				$user = $form->getConfig()->getOptions()['data'];
				$passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

				// 1. Récupérer le mot de passe saisi par l'utilisateur et le comparer au mdp en BDD (dans l'entité)
				$isValid = $passwordHasher->isPasswordValid(
					$user,
					$form->get('actualPassword')->getData()
				);

				// 2. Si le mot de passe n'est pas valide, envoyer une erreur
				if(!$isValid) {
					$form->get('actualPassword')->addError(new FormError('Le mot de passe saisi est incorrect'));
				}

    		});

	}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
			'passwordHasher' => null
        ]);
    }
}
