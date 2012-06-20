<?php

/**
 * @package Vidola
 */
namespace Vidola\View;

/**
 * @package Vidola
 */
interface ViewApi
{
	/**
	 * The name by which the API can be found in the view, eg $user for name 'user'.
	 */
	public function getName();
}