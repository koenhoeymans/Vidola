<?php

/**
 * @package Vidola
 */
namespace Vidola\DocumentBuilder;

use Vidola\Pattern\Patterns\Header;
use	Vidola\TextReplacer\TextReplacer;
use Vidola\Util\DocumentStructure;
use Vidola\Util\DocFileRetriever;
use Vidola\View\FileView\FileView;
use Vidola\TemplateApi\PageApiFactory;

/**
 * @package Vidola
 * 
 * Responsible for building the document.
 */
class DocumentBuilder
{
	private $documentStructure;

	private $textReplacer;

	private $view;

	private $docFileRetriever;

	private $pageApiFactory;

	private $template;

	public function __construct(
		DocumentStructure $documentStructure,
		TextReplacer $textReplacer,
		FileView $view,
		DocFileRetriever $docFileRetriever,
		PageApiFactory $pageApiFactory
	) {
		$this->documentStructure = $documentStructure;
		$this->textReplacer = $textReplacer;
		$this->view = $view;
		$this->docFileRetriever = $docFileRetriever;
		$this->pageApiFactory = $pageApiFactory;
	}

	/**
	 * Builds a document from a source file or directory and puts it in the destination
	 * directory.
	 * 
	 * @param string $file Source file or directory
	 */
	public function build($fileOrDirectory)
	{
		if (is_dir($fileOrDirectory))
		{
			$fileOrDirectory = 'Index';
		}

		if (file_exists($fileOrDirectory))
		{
			$filename = pathinfo($fileOrDirectory, PATHINFO_FILENAME);
		}
		else
		{
			$filename = $fileOrDirectory;
		}

		$textToTransform = $this->docFileRetriever->retrieveContent($filename);

		$replacedText = $this->textReplacer->replace($textToTransform);

		// @todo remove need for new
		$page = new \Vidola\Document\SimplePage();
		$page->setTitle($this->createTitle($filename));
		$page->setContent($replacedText);
		$page->setFilename($filename);
		$page->setNextPageName($this->documentStructure->getNextFile($filename));
		$page->setPreviousPageName($this->documentStructure->getPreviousFile($filename));

		$this->view->addApi($this->pageApiFactory->createWith($page));
		$this->view->setFilename($filename);
		$this->view->render();

		foreach ($this->documentStructure->getSubFiles($filename) as $subfile)
		{
			$this->build($subfile);
		}
	}

	private function createTitle($filename)
	{
		return str_replace(DIRECTORY_SEPARATOR, ' ', $filename);
	}
}