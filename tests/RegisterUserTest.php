<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
		/*
		 * 1. Créer faux client pointant vers la page d'inscription
		 * 2. Remplir les champs du formulaire avec des données valides
		 * 3. Suivre la redirection vers la page de connexion
		 * 4. Vérifier si mon flashMessage s'affiche
		 */

		// 1. GET
        $client = static::createClient();
		$client->request('GET', '/inscription');

		// 2. CREATE
		$client->submitForm('Valider', [
			'register_user[email]' => 'mathilde@amour.fr',
			'register_user[plainPassword][first]' => 'Password123&',
			'register_user[plainPassword][second]' => 'Password123&',
			'register_user[firstname]' => 'Mathilde',
			'register_user[lastname]' => 'Amour'
		]);

		//3. FOLLOW
		$this->assertResponseRedirects('/connexion');
		$client->followRedirect();

		// 4. FLASHMESSAGE
		$this->assertSelectorExists('div:contains("Votre compte a bien été créé")');
    }
}
