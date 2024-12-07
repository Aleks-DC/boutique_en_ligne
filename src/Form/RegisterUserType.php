<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
				'label' => 'Email',
				'attr' => [
					'placeholder' => 'exemple@votremail.fr'
					]
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
					'label' => 'Mot de passe',
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

            ->add('firstname', TextType::class, [
				'constraints' => [
					new Length([
						'min' => 2,
						'minMessage' => 'Votre prénom doit contenir au moins 2 caractères',
						'max' => 30,
						'maxMessage' => 'Votre prénom doit contenir au maximum 30 caractères'
					])
				],
				'label' => 'Prénom',
				'attr' => [
					'placeholder' => 'John'
				]
			])
            ->add('lastname', TextType::class, [
				'constraints' => [
					new Length([
						'min' => 2,
						'minMessage' => 'Votre nom doit contenir au moins 2 caractères',
						'max' => 30,
						'maxMessage' => 'Votre nom doit contenir au maximum 30 caractères'
					])
				],
				'label' => 'Nom',
				'attr' => [
					'placeholder' => 'Lennon'
				]
			])
			->add('submit', SubmitType::class, [
				'label' => 'Valider',
				'attr' => [
					'class' => 'btn btn-success'
				]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
			'constraints' => [
				new UniqueEntity([
					'entityClass' => User::class,
					'fields' => 'email',
					'message' => 'Cet email est déjà utilisé'
				]),
			],
            'data_class' => User::class,
        ]);
    }
}
