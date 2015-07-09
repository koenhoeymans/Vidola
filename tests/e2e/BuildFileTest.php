<?php

namespace Vidola;

class BuildFileTest extends \Vidola\Tidy
{
    public function setup()
    {
        $dir = sys_get_temp_dir().DIRECTORY_SEPARATOR;
        if (file_exists($dir . 'BuildFile.css')) {
            unlink($dir . 'BuildFile.css');
        }
        if (file_exists($dir . 'BuildSub' . DIRECTORY_SEPARATOR . 'BuildFile.js')) {
            unlink($dir . 'BuildSub' . DIRECTORY_SEPARATOR . 'BuildFile.js');
        }
        if (file_exists($dir . 'BuildSub'.DIRECTORY_SEPARATOR . 'Excluded.php')) {
            unlink($dir . 'BuildSub' . DIRECTORY_SEPARATOR . 'Excluded.php');
        }
    }

    public function teardown()
    {
        $this->setup();
    }

    /**
     * @test
     */
    public function usesBuildFile()
    {
        // given
        $bin = PHP_BINARY;
        $vidola = __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'src'
            . DIRECTORY_SEPARATOR . 'RunVidola.php';
        $build = __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'support'
            . DIRECTORY_SEPARATOR . 'BuildFile.php';

        // when
        exec("$bin $vidola $build", $output);

        // then
        $this->assertEquals(
            $this->tidy(file_get_contents(
                __DIR__
                . DIRECTORY_SEPARATOR . '..'
                . DIRECTORY_SEPARATOR .'support'
                . DIRECTORY_SEPARATOR .'BuildFile.html'
            )),
            $this->tidy(file_get_contents(
                sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'BuildFile.html'
            ))
        );
        $this->assertTrue(
            file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'BuildFile.css')
        );
        $this->assertTrue(
            file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'BuildSub' . DIRECTORY_SEPARATOR . 'BuildFile.js')
        );
        $this->assertFalse(
            file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'BuildSub' . DIRECTORY_SEPARATOR . 'Excluded.php')
        );
    }
}
