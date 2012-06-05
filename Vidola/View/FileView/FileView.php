<?php

/**
 * @package Vidola
 */
namespace Vidola\View\FileView;

use Vidola\View\TemplateBasedView;
use Vidola\View\ViewApi;
use Vidola\Util\Writer;

/**
 * @package Vidola
 * 
 * Writes content of a template to a file.
 */
class FileView implements TemplateBasedView
{
	private $api = array();

	private $writer;

	public function __construct(Writer $writer)
	{
		$this->writer = $writer;
	}

	public function addApi(ViewApi $api)
	{
		$this->api[$api->getName()] = $api;
	}

	public function render($template)
 	{
		extract($this->api);
		ob_start();
		require($template);
		$output = ob_get_clean();

		$this->writer->write($output, $page->filename());
	}
}