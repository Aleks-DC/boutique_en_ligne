<?php

namespace App\Class;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
	public function __construct(private readonly RequestStack $requestStack)
	{

	}
	public function getCart()
	{
		return $this->requestStack->getSession()->get('cart');
	}
	public function add($product): void
	{
		// Appeler la session de Symfony
		$cart = $this->requestStack->getSession()->get('cart');

		// Ajouter une quantité +1 à mon produit
		if (!empty($cart[$product->getId()])) {
			$cart[$product->getId()] = [
				'object' => $product,
				'quantity' => $cart[$product->getId()]['quantity'] + 1
			];
		} else {
			$cart[$product->getId()] = [
				'object' => $product,
				'quantity' => 1
			];
		}

		// Créer ma session Cart
		$this->requestStack->getSession()->set('cart', $cart);
	}

	public function decrease ($id): void
	{
		$cart = $this->requestStack->getSession()->get('cart');

		// Supprimer une quantité -1 à mon produit
		if ($cart[$id]['quantity'] > 1) {
			$cart[$id]['quantity'] = $cart[$id]['quantity'] - 1;
		} else {
			unset($cart[$id]);
		}

		$this->requestStack->getSession()->set('cart', $cart);
	}

	public function remove()
	{
		return $this->requestStack->getSession()->remove('cart');
	}
}