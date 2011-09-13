<?php

/**
 * @package Vidola
 */
namespace Vidola\DocumentBuilder;

use Vidola\OutputBuilder\OutputBuilder;
use Vidola\Pattern\Patterns\Header;
use	Vidola\TextReplacer\TextReplacer;
use Vidola\Util\DocumentStructure;
use Vidola\Util\DocFileRetriever;

/**
 * @package Vidola
 */
class DocumentBuilder
{
	private $documentStructure;

	private $textReplacer;

	private $outputBuilder;

	private $docFileRetriever;

	public function __construct(
		DocumentStructure $documentStructure,
		TextReplacer $textReplacer,
		OutputBuilder $outputBuilder,
		Header $header,
		DocFileRetriever $docFileRetriever
	) {
		$this->documentStructure = $documentStructure;
		$this->textReplacer = $textReplacer;
		$this->outputBuilder = $outputBuilder;
		$this->headers = $header;
		$this->docFileRetriever = $docFileRetriever;
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
			$fileName = pathinfo($fileOrDirectory, PATHINFO_FILENAME);
		}
		else
		{
			$fileName = $fileOrDirectory;
		}

		$textToTransform = $this->docFileRetriever->retrieveContent($fileName);

		$this->headers->replace($textToTransform); // fill headers before toc but replace nothing

		$replacedText = $this->textReplacer->replace($textToTransform);

		$this->outputBuilder->setFileName($fileName);
		$this->outputBuilder->setContent($replacedText);
		$this->outputBuilder->setTitle($this->createTitle($fileName));
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