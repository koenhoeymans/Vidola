<?php

/**
 * @package Vidola
 */
namespace Vidola\View;

/**
 * @package Vidola
 * 
 * Renders representation of domain.
 */
interface TemplateBasedView
{
	public function setTemplate($template);

	public function render();

	public function addApi(ViewApi $api);
}