<?php

/**
 * @package Vidola
 */
namespace Vidola\Config;

/**
 * @package Vidola
 */
class CommandLineConfig implements Config
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

		foreach ($argv as $key => $value)
		{
			if (is_int($key) && substr($value, 0, 2) === '--')
			{
				$keyValue = explode('=', $value);

				# avoid phpunit --debug
				if (!isset($keyValue[1]))
				{
					$keyValue[1] = null;
				}

				$options[substr($keyValue[0], 2)] = $keyValue[1];
			}
			else
			{
				$options[$key] = $value;
			}
		}

		return $options;
	}
}