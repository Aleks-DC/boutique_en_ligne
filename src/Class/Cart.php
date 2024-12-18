<?php

namespace App\Class;

use Symfony\Component\HttpFoundation\RequestStack;

readonly class Cart
{
	// Utilisation d'un constructeur pour injecter la stack de requêtes Symfony
	public function __construct(private RequestStack $requestStack)
	{
	}

	// Récupère le panier actuel depuis la session
	public function getCart(): ?array // Retourne null ou un tableau
	{
		// Assurez-vous que la session retourne toujours un tableau vide si 'cart' est null
		return $this->requestStack->getSession()->get('cart', []);
	}

	// Ajoute un produit au panier
	public function add($product): void
	{
		// Vérifie si le panier existe et le récupère
		$cart = $this->getCart();

		// Ajoute le produit ou incrémente la quantité existante
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

		// Sauvegarde le panier mis à jour dans la session
		$this->requestStack->getSession()->set('cart', $cart);
	}

	// Diminue la quantité d'un produit dans le panier
	public function decrease($id): void
	{
		$cart = $this->getCart();

		if (isset($cart[$id])) {
			// Si la quantité est > 1, la décrémente, sinon supprime l'article
			if ($cart[$id]['quantity'] > 1) {
				$cart[$id]['quantity']--;
			} else {
				unset($cart[$id]);
			}

			// Met à jour le panier dans la session
			$this->requestStack->getSession()->set('cart', $cart);
		}
	}

	// Calcule la quantité totale de produits dans le panier
	public function fullQuantity(): int
	{
		$cart = $this->getCart();
		$quantity = 0;

		// Parcourt le panier pour additionner les quantités
		foreach ($cart as $product) {
			$quantity += $product['quantity'];
		}

		return $quantity;
	}

	// Calcule le prix total WT (With Tax)
	public function getTotalWT(): float
	{
		$cart = $this->getCart();
		$price = 0.0;

		// Parcourt le panier pour calculer le total
		foreach ($cart as $product) {
			$price += $product['object']->getPriceWT() * $product['quantity'];
		}

		return $price;
	}

	// Supprime le panier de la session
	public function remove(): void
	{
		// Supprime l'entrée 'cart' de la session
		$this->requestStack->getSession()->remove('cart');
	}
}
