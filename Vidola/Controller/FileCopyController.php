<?php

/**
 * @package Vidola
 */
namespace Vidola\Controller;

use Vidola\Util\FileCopy;

/**
 * @package Vidola
 */
class FileCopyController
{
	private $fileCopy;

	public function __construct(FileCopy $fileCopy)
	{
		$this->fileCopy = $fileCopy;
	}

	public function copyFiles($templateFile, $exclude, $include, $targetDir)
	{
		$include = (array) $include;
		$exclude = (array) $exclude;
		$exclude[] = $templateFile;
		$sourceDir = dirname($templateFile);

		$this->fileCopy->copy(
			$sourceDir, $targetDir, $exclude, $include
		);
	}
}