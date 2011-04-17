<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

use Vidola\TextReplacer\TextReplacer;

/**
 * @package Vidola
 */
class DocumentBuilder
{
	private $textReplacer;

	private $writer;

	public function __construct(TextReplacer $textReplacer, Writer $writer)
	{
		$this->textReplacer = $textReplacer;
		$this->writer = $writer;
	}

	/**
	 * @param string $from
	 * @param string $targetDir
	 * @param bool $single Defaults to false but is always true when $from is a file.
	 */
	public function build($from, $targetDir, $targetFileName = false)
	{
		$source = $this->formatSource($from);
		$targetDir = $this->formatTargetDir($targetDir);

		if (is_file($source))
		{
			if (!$targetFileName)
			{
				$sourceFileName = substr(strrchr($source, DIRECTORY_SEPARATOR), 1);
				$targetFileName = $this->createTargetFileName($sourceFileName);
			}

			$files = array($targetFileName => file_get_contents($source));
		}
		else
		{
			$files = $this->buildTargetFileList($source);
		}

		if ($targetFileName)
		{
			$this->buildSingleFile($files, $targetDir . $targetFileName);
		}
		else
		{
			$this->buildMultipleFiles($files, $targetDir);
		}
	}

	/**
	 * Source path conversion to absolute path if needed + canonicalized version.
	 * 
	 * @param string $source
	 */
	private function formatSource($source)
	{
		if (!($formattedSource = realpath($source)))
		{
			$formattedSource = realpath(getcwd() . DIRECTORY_SEPARATOR . $source);
		}

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

	private function buildTargetFileList($source)
	{
		$targetFiles = array();
		$sourceFiles = scandir($source);
		if ($sourceFiles === false)
		{
			throw new Exception("Failed to get list of files of $source.");
		}
		foreach ($sourceFiles as $sourceFileName)
		{
			if ($sourceFileName === '.' || $sourceFileName ==='..')
			{
				continue;
			}

			$targetFiles[$this->createTargetFileName($sourceFileName)] = file_get_contents(
				$source . DIRECTORY_SEPARATOR . $sourceFileName
			);
		}

		return $targetFiles;
	}

	private function buildSingleFile(array $inputFiles, $target)
	{
		$text = '';
		foreach ($inputFiles as $targetFileName => $textToTransform)
		{
			$text .= $textToTransform;
		}

		$this->writer->write($this->textReplacer->replace($text), $target);
	}

	private function buildMultipleFiles(array $inputFiles, $targetDir)
	{
		foreach ($inputFiles as $targetFileName => $contents)
		{
			$this->writer->write(
				$this->textReplacer->replace($contents),
				$targetDir . DIRECTORY_SEPARATOR . $targetFileName
			);
		}
	}
}