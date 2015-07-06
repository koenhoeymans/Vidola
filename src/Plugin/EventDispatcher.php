<?php

/**
 * @package Vidola
 */
namespace Vidola\Plugin;

/**
 * @package Vidola
 */
class EventDispatcher implements Observer, EventMapper
{
    private $observers = array();

    public function notify(Event $event)
    {
        $eventName = get_class($event);
        if (!isset($this->observers[$eventName])) {
            return;
        }
        foreach ($this->observers[$eventName] as $callback) {
            $callback($event);
        }
    }

    public function register($event, $callback)
    {
        $this->observers[$event][] = $callback;
    }

    public function registerPlugin(Plugin $plugin)
    {
        $plugin->register($this);
    }
}
