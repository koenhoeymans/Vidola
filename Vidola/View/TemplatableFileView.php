<?php

/**
 * @package Vidola
 */
namespace Vidola\View;

/**
 * @package Vidola
 * 
 * Renders representation of domain writing it to a file. The output can
 * be changed by use of different templates.
 */
interface TemplatableFileView
{
	public function setTemplate($template);

	public function addApi(ViewApi $api);

	public function setOutputDir($dir);

	public function setFilename($name);

	public function render();
}