<?php

/**
 * @package Vidola
 */
namespace Vidola\Controller;

use Vidola\Document\DocumentApiBuilder;
use Vidola\Document\DocumentStructure;
use Vidola\View\TemplatableFileView;

/**
 * @package Vidola
 */
class DocumentCreationController
{
	private $documentApiBuilder;

	private $documentStructure;

	private $view;

	public function __construct(
		DocumentApiBuilder $documentApiBuilder,
		DocumentStructure $documentStructure,
		TemplatableFileView $view
	) {
		$this->documentApiBuilder = $documentApiBuilder;
		$this->documentStructure = $documentStructure;
		$this->view = $view;
	}

	public function createDocumentation()
	{
		foreach($this->documentStructure->getFileList() as $file)
		{
			$this->createSingleDoc($file);
		}
	}

	private function createSingleDoc($file)
	{
		$this->view->addApi($this->documentApiBuilder->buildApi($file));
		$this->view->setFilename($this->documentStructure->getFilename($file));
		$this->view->render();
	}
}