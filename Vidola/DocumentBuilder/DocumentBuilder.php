<?php

/**
 * @package Vidola
 */
namespace Vidola\DocumentBuilder;

use Vidola\Pattern\Patterns\Header;
use	Vidola\TextReplacer\TextReplacer;
use Vidola\Util\DocumentStructure;
use Vidola\Util\DocFileRetriever;
use Vidola\Util\Writer;
use Vidola\View\TemplateBasedView;
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
		TemplateBasedView $view,
		DocFileRetriever $docFileRetriever,
		PageApiFactory $pageApiFactory
	) {
		$this->documentStructure = $documentStructure;
		$this->textReplacer = $textReplacer;
		$this->view = $view;
		$this->docFileRetriever = $docFileRetriever;
		$this->pageApiFactory = $pageApiFactory;
	}

	public function setTemplate($path)
	{
//@todo move this to view ??
		$this->template = $path;
	}

	private function getTemplate()
	{
		if (isset($this->template))
		{
			return $this->template;
		}
		return __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Templates'
			. DIRECTORY_SEPARATOR . 'Default'
			. DIRECTORY_SEPARATOR . 'Index.php';
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

		$pageApi = $this->pageApiFactory->createWith($page);
		$this->view->addApi($pageApi);
		$this->view->render($this->getTemplate());

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