<?php

/**
 * @package Vidola
 */
namespace Vidola\View\TemplateBasedView;

/**
 * @package Vidola
 */
abstract class TemplateApi
{
	/**
	 * The name by which the API can be found in the template, eg $user for name 'user'.
	 */
	abstract public function getName();
}