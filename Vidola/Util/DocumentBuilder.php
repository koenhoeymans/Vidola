<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

use Vidola\Patterns\Header,
	Vidola\TextReplacer\TextReplacer;

/**
 * @package Vidola
 */
class DocumentBuilder
{
	private $documentStructure;

	private $textReplacer;

	private $writer;

	public function __construct(
		DocumentStructure $documentStructure,
		TextReplacer $textReplacer,
		Writer $writer,
		Header $header
	) {
		$this->documentStructure = $documentStructure;
		$this->textReplacer = $textReplacer;
		$this->writer = $writer;
		$this->headers = $header;
	}

	/**
	 * Builds a document from a given target and puts it in the destination
	 * directory.
	 * 
	 * @param string $from
	 * @param string $targetDir
	 * @param string $targetFileName
	 */
	public function build($from, $targetDir)
	{
		$source = $this->formatSource($from);
		$targetDir = $this->formatTargetDir($targetDir);

		if (is_file($source))
		{
			$sourceFileName = substr($source, strripos($source, DIRECTORY_SEPARATOR) + 1);
			$sourceDir = substr($source, 0, strripos($source, DIRECTORY_SEPARATOR));
		}
		else
		{
			$sourceDir = $source;
			$sourceFileName = 'Index.vi';
			$source = $sourceDir . DIRECTORY_SEPARATOR . $sourceFileName;
		}

		$targetFileName = $this->createTargetFileName($sourceFileName);
		$text = file_get_contents($source);
		$this->headers->replace($text);
		$replacedText = $this->textReplacer->replace($text);

		$this->writer->write($replacedText, $targetDir . $targetFileName);

		foreach ($this->documentStructure->getSubFiles($text) as $subfile)
		{
			$this->build($sourceDir . DIRECTORY_SEPARATOR . $subfile, $targetDir);
		}
	}

	/**
	 * Source path conversion to absolute path if needed + canonicalized version.
	 * 
	 * @param string $source
	 */
	private function formatSource($source)
	{
		$formattedSource = realpath($source);
		return $formattedSource;
	}

	/**
	 * Build absolute and canonical target directory.
	 * 
	 * @param string $targetDir
	 */
	private function formatTargetDir($targetDir)
	{
		if (substr($targetDir, -1) !== DIRECTORY_SEPARATOR)
		{
			$targetDir .= DIRECTORY_SEPARATOR;
		}
		if ($targetDir[0] !== DIRECTORY_SEPARATOR)
		{
			$targetDir = getcwd() . DIRECTORY_SEPARATOR . $targetDir;
		}

		return $targetDir;
	}

	private function createTargetFileName($sourceFileName)
	{
		$nameWithoutExt = substr($sourceFileName, 0, strrpos($sourceFileName, '.'));
		$targetFileName = $nameWithoutExt . '.' . $this->textReplacer->getExtension();

		return $targetFileName;
	}
}