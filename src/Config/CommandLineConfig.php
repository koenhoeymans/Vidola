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

        if (count($argv) === 2 && isset($argv[1]) && file_exists($argv[1])) {
            $config = require $argv[1];
            $argv = array_merge($argv, $config);
        }

        foreach ($argv as $key => $value) {
            if (is_int($key) && substr($value, 0, 2) === '--') {
                $keyValue = explode('=', $value);
                $options[substr($keyValue[0], 2)] = $keyValue[1];
            } else {
                $options[$key] = $value;
            }
        }

        return $options;
    }

    public function getTemplate()
    {
        return $this->get('template') ?:
            __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Templates'
            .DIRECTORY_SEPARATOR.'Default'
            .DIRECTORY_SEPARATOR.'Index.php';
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
