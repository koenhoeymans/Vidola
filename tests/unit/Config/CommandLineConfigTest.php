<?php

namespace Vidola\Config;

class CommandLineConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function readsConfigFromCommandLine()
    {
        // given
        $_SERVER['argv'][3] = '--foo=bar';
        $config = new \Vidola\Config\CommandLineConfig($_SERVER['argv']);

        // when
        $configOption = $config->get('foo');

        // then
        $this->assertEquals('bar', $configOption);
    }

    /**
     * @test
     */
    public function loadsBuildFileWithArrayWithKeys()
    {
        // given
        $_SERVER['argv'][5] = '--buildfile=' . __DIR__
                . DIRECTORY_SEPARATOR .'..'
                . DIRECTORY_SEPARATOR .'..'
                . DIRECTORY_SEPARATOR .'support'
                . DIRECTORY_SEPARATOR .'Config.php';
        $config = new \Vidola\Config\CommandLineConfig($_SERVER['argv']);

        // when
        $configOption = $config->get('foo');

        // then
        $this->assertEquals('bar', $configOption);
    }

    /**
     * @test
     */
    public function getsTemplate()
    {
        $template = __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'support'
            . DIRECTORY_SEPARATOR . 'BuildFileTemplate'
            . DIRECTORY_SEPARATOR . 'MiniTemplate.php';
        $_SERVER['argv'][8] = '--template=' .  $template;
        $config = new \Vidola\Config\CommandLineConfig($_SERVER['argv']);

        $this->assertEquals($template, $config->getTemplate());
    }

    /**
     * @test
     */
    public function getsCopyIncludedFiles()
    {
        $_SERVER['argv'][7] = '--copy-include=file';
        $config = new \Vidola\Config\CommandLineConfig($_SERVER['argv']);

        $this->assertEquals(array('file'), $config->getCopyIncludedFiles());
    }

    /**
     * @test
     */
    public function getsCopyExcludedFiles()
    {
        $_SERVER['argv'][9] = '--copy-exclude=file';
        $config = new \Vidola\Config\CommandLineConfig($_SERVER['argv']);

        $this->assertEquals(array('file'), $config->getCopyExcludedFiles());
    }

    /**
     * @test
     */
    public function getsTargetDir()
    {
        $_SERVER['argv'][4] = '--target-dir=/tmp';
        $config = new \Vidola\Config\CommandLineConfig($_SERVER['argv']);

        $this->assertEquals('/tmp', $config->getTargetDir());
    }
}
