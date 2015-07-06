<?php

/**
 * @package Vidola
 */
namespace Vidola\Plugin;

/**
 * @package Vidola
 */
interface EventMapper
{
    public function register($event, $callback);
}
