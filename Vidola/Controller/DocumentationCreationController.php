<?php

/**
 * @package Vidola
 */
namespace Vidola\Controller;

use Vidola\Document\DocumentationApiBuilder;
use Vidola\Document\DocumentationStructure;
use Vidola\View\TemplatableFileView;

/**
 * @package Vidola
 */
class DocumentationCreationController
{
	private $documentationApiBuilder;

	private $documentationStructure;

	private $view;

	public function __construct(
		DocumentationApiBuilder $documentationApiBuilder,
		DocumentationStructure $documentationStructure,
		TemplatableFileView $view
	) {
		$this->documentationApiBuilder = $documentationApiBuilder;
		$this->documentationStructure = $documentationStructure;
		$this->view = $view;
	}

	public function createDocumentation()
	{
		foreach($this->documentationStructure->getFileList() as $file)
		{
			$this->createSingleDoc($file);
		}
	}

	private function createSingleDoc($file)
	{
		$this->view->addApi($this->documentationApiBuilder->buildApi($file));
		$this->view->setFilename($this->documentationStructure->createFilename($file));
		$this->view->render();
	}
}