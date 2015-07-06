<?php

/**
 * @package Vidola
 */
namespace Vidola\Plugin;

/**
 * @package Vidola
 */
trait Pluggable
{
    protected $observers = array();

    public function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    protected function notify(Event $event)
    {
        foreach ($this->observers as $observer) {
            $observer->notify($event);
        }
    }
}
