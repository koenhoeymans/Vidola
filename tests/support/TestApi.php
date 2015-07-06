<?php

namespace Vidola;

class TestApi implements \Vidola\View\ViewApi
{
    private $vars = array();

    public function getName()
    {
        return 'testApi';
    }

    public function set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    public function get($name)
    {
        return $this->vars[$name];
    }
}
