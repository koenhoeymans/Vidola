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

	private $template;

	public function __construct(Writer $writer)
	{
		$this->writer = $writer;
	}

	public function addApi(ViewApi $api)
	{
		$this->api[$api->getName()] = $api;
	}

	public function render()
 	{
		extract($this->api);
		ob_start();
		require($this->getTemplate());
		$output = ob_get_clean();

		$this->writer->write($output, $page->filename());
	}

	public function setTemplate($template)
	{
		$this->template = $template;
	}

	private function getTemplate()
	{
		if (isset($this->template))
		{
			return $this->template;
		}
		return __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Templates'
			. DIRECTORY_SEPARATOR . 'Default'
			. DIRECTORY_SEPARATOR . 'Index.php';
	}
}