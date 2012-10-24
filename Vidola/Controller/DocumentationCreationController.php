<?php

/**
 * @package Vidola
 */
namespace Vidola\Controller;

use Vidola\Document\FilenameCreator;
use Vidola\Document\DocumentationApiBuilder;
use Vidola\Document\Structure;
use Vidola\View\TemplatableFileView;

/**
 * @package Vidola
 */
class DocumentationCreationController
{
	private $filenameCreator;

	private $documentationApiBuilder;

	private $structure;

	private $view;

	public function __construct(
		FilenameCreator $filenameCreator,
		DocumentationApiBuilder $documentationApiBuilder,
		Structure $structure,
		TemplatableFileView $view
	) {
		$this->filenameCreator = $filenameCreator;
		$this->documentationApiBuilder = $documentationApiBuilder;
		$this->structure = $structure;
		$this->view = $view;
	}

	public function createDocumentation()
	{
		foreach($this->structure->getPages() as $page)
		{
			$this->createSingleDoc($page);
		}
	}

	private function createSingleDoc($page)
	{
		$this->view->addApi($this->documentationApiBuilder->buildApi($page));
		$this->view->setFilename($this->filenameCreator->createFilename($page));
		$this->view->render();
	}
}