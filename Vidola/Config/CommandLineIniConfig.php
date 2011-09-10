<?php

/**
 * @package Vidola
 */
namespace Vidola\Config;

/**
 * @package Vidola
 */
class CommandLineIniConfig implements Config
{
	private $argv;

	public function __construct(array $argv, $ini)
	{
		$this->settings = array_merge(parse_ini_file($ini), $this->parseArgv($argv));
	}

	public function get($name)
	{
		if (isset($this->settings[$name]))
		{
			return $this->settings[$name];
		}

		return null;
	}

	private function parseArgv(array $argv)
	{
		$options = array();

		foreach ($argv as $key => $value)
		{
			if (is_int($key) && substr($value, 0, 2) === '--')
			{
				$keyValue = explode('=', $value);
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