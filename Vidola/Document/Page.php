<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface Page
{
	public function getContent();

	public function getFilename();

	public function getTitle();

	public function getNextPageName();

	public function getPreviousPageName();
}