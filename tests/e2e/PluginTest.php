<?php

namespace Vidola;

class PluginTest extends \Vidola\Tidy
{
    public function setup()
    {
        parent::setup();

        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        if (file_exists($dir . 'Plugin.html')) {
            unlink($dir . 'Plugin.html');
        }
    }

    public function teardown()
    {
        $this->setup();
    }

    /**
     * @test
     */
    public function allowsPluginToAlterOutput()
    {
        // given
        $bin = PHP_BINARY;
        $vidola = __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'src'
            . DIRECTORY_SEPARATOR . 'RunVidola.php';
        $build = '--buildfile=' . __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'support'
            . DIRECTORY_SEPARATOR . 'Plugin'
            . DIRECTORY_SEPARATOR . 'BuildFile.php';

        // when
        exec("$bin $vidola $build", $output);

        // then
        $this->assertEquals(
            $this->tidy(file_get_contents(
                __DIR__
                . DIRECTORY_SEPARATOR . '..'
                . DIRECTORY_SEPARATOR . 'support'
                . DIRECTORY_SEPARATOR . 'Plugin.html'
            )),
            $this->tidy(file_get_contents(
                sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Plugin.html'
            ))
        );
    }
}
