<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface DocumentStructure
{
	public function getFileList();

	public function getSubfiles($file);

	public function createFilename($file);
}