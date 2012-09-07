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
	 * The view can allow a template to access this API and can distinguish between
	 * different ones by their name.
	 */
	public function getName();
}