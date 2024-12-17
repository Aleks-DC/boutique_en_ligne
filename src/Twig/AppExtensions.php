<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtensions extends AbstractExtension
{
	public function getFilters(): array
	{
		return [
			new TwigFilter('price', $this->formatPrice(...)),
		];
	}

	public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ','): string
	{
		return number_format($number, '2', ','). ' €';

	}
}