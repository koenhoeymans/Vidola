<?php

/**
 * @package Vidola
 */
namespace Vidola\View;

/**
 * @package Vidola
 * 
 * Renders content.
 */
interface View
{
	public function render();

	public function addApi(ViewApi $api);
}