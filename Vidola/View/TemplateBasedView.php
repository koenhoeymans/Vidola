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
	public function render($template);

	public function addApi(ViewApi $api);
}