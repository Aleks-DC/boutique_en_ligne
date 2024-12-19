<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
				'label' => 'Prénom',
				'attr' => [
					'placeholder' => 'Entrez votre prénom'
				]
			])
            ->add('lastname', TextType::class, [
				'label' => 'Nom',
				'attr' => [
					'placeholder' => 'Entrez votre nom'
				]
			])
            ->add('address', TextType::class, [
				'label' => 'Adresse',
				'attr' => [
					'placeholder' => 'Entrez votre adresse'
				]
			])
            ->add('postal', TextType::class, [
				'label' => 'Code postal',
				'attr' => [
					'placeholder' => 'Entrez votre code postal'
				]
			])
            ->add('city', TextType::class, [
				'label' => 'Ville',
				'attr' => [
					'placeholder' => 'Entrez votre ville'
				]
			])
            ->add('country', CountryType::class, [
				'label' => 'Pays',
			])
            ->add('phone', TextType::class, [
				'label' => 'Téléphone',
				'attr' => [
					'placeholder' => 'Entrez votre numéro de téléphone'
				]
			])
			->add('submit', SubmitType::class, [
				'label' => 'Sauvegarder',
				'attr' => [
					'class' => 'btn btn-success'
				]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
