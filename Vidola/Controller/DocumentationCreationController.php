<?php

/**
 * @package Vidola
 */
namespace Vidola\Controller;

use Vidola\Document\DocumentApiBuilder;
use Vidola\Document\DocumentationStructure;
use Vidola\View\TemplatableFileView;

/**
 * @package Vidola
 */
class DocumentationCreationController
{
	private $documentApiBuilder;

	private $documentationStructure;

	private $view;

	public function __construct(
		DocumentApiBuilder $documentApiBuilder,
		DocumentationStructure $documentationStructure,
		TemplatableFileView $view
	) {
		$this->documentApiBuilder = $documentApiBuilder;
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
		$this->view->addApi($this->documentApiBuilder->buildApi($file));
		$this->view->setFilename($this->documentationStructure->createFilename($file));
		$this->view->render();
	}
}