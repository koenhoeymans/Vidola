<?php

/**
 * @package vidola
 */
namespace Vidola\Plugin;

/**
 * @package Vidola
 */
interface Observable
{
	public function addObserver(Observer $observer);
}