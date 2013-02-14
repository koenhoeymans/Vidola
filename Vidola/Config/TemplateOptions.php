<?php

/**
 * @package Vidola
 */
namespace Vidola\Config;

/**
 * @package Vidola
 */
interface TemplateOptions
{
	public function getTemplate();

	public function getCopyIncludedFiles();

	public function getCopyExcludedFiles();

	public function getTargetDir();
}