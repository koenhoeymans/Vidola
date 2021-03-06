<?php

namespace Vidola;

class TableOfContentsTest extends \Vidola\Tidy
{
    public function setup()
    {
        parent::setup();

        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        if (file_exists($dir . 'TableOfContents.html')) {
            unlink($dir . 'TableOfContents.html');
        }
    }

    public function teardown()
    {
        $this->setup();
    }

    /**
     * @test
     */
    public function createsLocalTableOfContents()
    {
        // given
        $bin = PHP_BINARY;
        $vidola = __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'src'
            . DIRECTORY_SEPARATOR . 'RunVidola.php';
        $source = __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'support'
            . DIRECTORY_SEPARATOR . 'TableOfContents.txt';
        $targetDir = sys_get_temp_dir();
        $template = __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'support'
            . DIRECTORY_SEPARATOR . 'MiniTemplate'
            . DIRECTORY_SEPARATOR . 'MiniTemplate.php';

        // when
        exec("$bin $vidola --source={$source} --target-dir={$targetDir} --template={$template}");

        // then
        $this->assertEquals(
            $this->tidy(file_get_contents(
                __DIR__
                . DIRECTORY_SEPARATOR . '..'
                . DIRECTORY_SEPARATOR . 'support'
                . DIRECTORY_SEPARATOR . 'TableOfContents.html'
            )),
            $this->tidy(file_get_contents(
                $targetDir . DIRECTORY_SEPARATOR.'TableOfContents.html'
            ))
        );
    }
}
