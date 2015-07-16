<?php

/**
 * @package Vidola
 */
namespace Vidola\Config;

/**
 * @package Vidola
 */
class CommandLineConfig implements Config, TemplateOptions
{
    private $settings;

    public function __construct(array $argv)
    {
        $this->settings = $this->parseArgv($argv);
    }

    public function get($name)
    {
        return isset($this->settings[$name]) ? $this->settings[$name] : null;
    }

    private function parseArgv(array $argv)
    {
        $options = array();

        foreach ($argv as $key => $value) {
            if ((substr($value, 0, 2) === '--') && (strpos($value, "=") !== false)) {
                $value = substr($value, 2);
                $option = explode('=', $value);
                $options[$option[0]] = $option[1];
            }
        }

        if (isset($options['buildfile'])) {
            $config = require $options['buildfile'];
            $options = array_merge($options, $config);
        }

        return $options;
    }

    public function getTemplate()
    {
        return $this->get('template') ?:
            __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'Templates'
            . DIRECTORY_SEPARATOR . 'Default'
            . DIRECTORY_SEPARATOR . 'Index.php';
    }

    public function getCopyIncludedFiles()
    {
        return (array) $this->get('copy-include');
    }

    public function getCopyExcludedFiles()
    {
        return (array) $this->get('copy-exclude');
    }

    public function getTargetDir()
    {
        return $this->get('target-dir');
    }
}
