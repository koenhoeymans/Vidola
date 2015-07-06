<?php

/**
 * @package Vidola
 */
namespace Vidola\Controller;

use Vidola\View\StoredTemplatableFileView;
use Vidola\Document\FilenameCreator;
use Vidola\Document\DocumentationApiBuilder;
use Vidola\Document\PageList;

/**
 * @package Vidola
 */
class DocumentationCreationController
{
	private $filenameCreator;

	private $documentationApiBuilder;

	private $pageLIst;

	private $view;

	public function __construct(
		FilenameCreator $filenameCreator,
		DocumentationApiBuilder $documentationApiBuilder,
		PageList $pageList,
		StoredTemplatableFileView $view
	) {
		$this->filenameCreator = $filenameCreator;
		$this->documentationApiBuilder = $documentationApiBuilder;
		$this->pageList = $pageList;
		$this->view = $view;
	}

	public function createDocumentation()
	{
		foreach($this->pageList->getPages() as $page)
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