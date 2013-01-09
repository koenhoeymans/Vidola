<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Config_CommandLineConfigTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function readsConfigFromCommandLine()
	{
		// given
		$_SERVER['argv']['foo'] = 'bar';
		$config = new \Vidola\Config\CommandLineConfig($_SERVER['argv']);

		// when
		$configOption = $config->get('foo');

		// then
		$this->assertEquals('bar', $configOption);
	}

	/**
	 * @test
	 */
	public function acceptsFileWithArrayWithKeys()
	{
		// given
		$_SERVER['argv'][0] = __DIR__
				. DIRECTORY_SEPARATOR . '..'
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR .'Config.php';
		$config = new \Vidola\Config\CommandLineConfig($_SERVER['argv']);

		// when
		$configOption = $config->get('foo');

		// then
		$this->assertEquals('bar', $configOption);
	}
}