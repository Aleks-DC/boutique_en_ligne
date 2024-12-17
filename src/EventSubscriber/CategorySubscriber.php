<?php

namespace App\EventSubscriber;

use App\Repository\CategoryRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class CategorySubscriber implements EventSubscriberInterface
{
	private CategoryRepository $categoryRepository;
	private Environment $twig;

	public function __construct(CategoryRepository $categoryRepository, Environment $twig)
	{
		$this->categoryRepository = $categoryRepository;
		$this->twig = $twig;
	}

	public static function getSubscribedEvents(): array
	{
		// On écoute l'événement KernelEvents::CONTROLLER
		return [
			KernelEvents::CONTROLLER => 'onControllerEvent',
		];
	}

	public function onControllerEvent(ControllerEvent $event): void
	{
		// Charger les catégories via le repository
		$categories = $this->categoryRepository->findAll();

		// Injecter les catégories comme variable globale Twig
		$this->twig->addGlobal('allCategories', $categories);
	}
}