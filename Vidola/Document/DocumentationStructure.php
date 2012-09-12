<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface DocumentationStructure
{
	public function getFileList();

	public function getSubfiles($file);

	public function createFilename($file);
}