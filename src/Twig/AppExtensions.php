<?php

namespace App\Twig;

use App\Class\Cart;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{
	private $cart;

	public function __construct(Cart $cart)
	{
		$this->cart = $cart;
	}

	public function getFilters(): array
	{
		return [
			new TwigFilter('price', $this->formatPrice(...)),
		];
	}

	public function formatPrice($number): string
	{
		return number_format($number, '2', ',', ' '). ' €';
	}

	public function getGlobals(): array
	{
		return [
			'fullCartQuantity' => $this->cart->fullQuantity(),
		];
	}
}