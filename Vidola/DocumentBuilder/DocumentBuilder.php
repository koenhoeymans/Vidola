<?php

/**
 * @package Vidola
 */
namespace Vidola\DocumentBuilder;

use Vidola\Pattern\Patterns\Header;
use	Vidola\TextReplacer\TextReplacer;
use Vidola\Util\DocumentStructure;
use Vidola\Util\DocFileRetriever;
use Vidola\View\View;
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

	public function __construct(
		DocumentStructure $documentStructure,
		TextReplacer $textReplacer,
		View $view,
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

		$textToTransform = $this->docFileRetriever->retrieveContent($fileName);

		$replacedText = $this->textReplacer->replace($textToTransform);

$page = new \Vidola\Document\SimplePage();

$page->setTitle($this->createTitle($fileName));
$page->setContent($replacedText);
$page->setFilename($filename);
$page->setNextPageName($this->documentStructure->getNextFile($filename));
$page->setPreviousPageName($this->documentStructure->getPreviousFile($fileName));

$pageApi = $this->pageApiFactory->createWith($page);
$this->view->addApi($pageApi);
$output = $this->view->render();

var_dump($output);
die();

//$this->outputBuilder->setFileName($fileName);
//$this->outputBuilder->setContent($replacedText);
//$this->outputBuilder->setTitle($this->createTitle($fileName));
//$previousDoc = $this->documentStructure->getPreviousFile($fileName);
//$this->outputBuilder->setPreviousDoc($previousDoc);
//$nextDoc = $this->documentStructure->getNextFile($fileName);
//$this->outputBuilder->setNextDoc($nextDoc);

		$this->outputBuilder->build();

		foreach ($this->documentStructure->getSubFiles($fileName) as $subfile)
		{
			$this->build($subfile);
		}
	}

	private function createTitle($fileName)
	{
		return str_replace(DIRECTORY_SEPARATOR, ' ', $fileName);
	}
}