<?php

/**
 * @package Vidola
 */
namespace Vidola\View\TemplateBasedView;

use Vidola\View\View;

/**
 * @package Vidola
 * 
 * Renders content of a template file.
 */
class TemplateBasedView implements View
{
	private $template;

	private $api = array();

	public function __construct($pathToTemplate)
	{
		$this->template = $pathToTemplate;
	}

	public function addApi(TemplateApi $api)
	{
		$this->api[$api->getName()] = $api;
	}

	public function render()
 	{
		$render = function($template, $api)
		{
			extract($api);
			ob_start();
			require_once($template);
			return ob_get_clean();
		};

		return $render($this->template, $this->api);
	}
}