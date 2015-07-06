<?php

/**
 * @package Vidola
 */
namespace Vidola\Plugin;

/**
 * @package Vidola
 */
interface Observer
{
    public function notify(Event $event);
}
