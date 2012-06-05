<?php

/**
 * @package Vidola
 */
namespace Vidola\View;

/**
 * @package Vidola
 */
abstract class ViewApi
{
	/**
	 * The name by which the API can be found in the view, eg $user for name 'user'.
	 */
	abstract public function getName();
}