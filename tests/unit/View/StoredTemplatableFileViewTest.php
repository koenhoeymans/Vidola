<?php

namespace Vidola\View;

class StoredTemplatableFileViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function rendersGivenTemplate()
    {
        $target = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'foo.html';

        if (file_exists($target)) {
            unlink($target);
        }

        $api = new \Vidola\TestApi();
        $api->set('name', 'bar');

        $template = __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'support'
            . DIRECTORY_SEPARATOR . 'Template.html';

        $view = new \Vidola\View\StoredTemplatableFileView();
        $view->setTemplate($template);
        $view->setFilename('foo');
        $view->setFileExtension('html');
        $view->setOutputDir(sys_get_temp_dir());
        $view->addApi($api);

        $view->render($template);

        $this->assertEquals('foo bar', file_get_contents($target));
    }

    /**
     * @test
     */
    public function targetDirMustExist()
    {
        $template = __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'support'
            . DIRECTORY_SEPARATOR . 'Template.html';
        $api = new \Vidola\TestApi();
        $api->set('name', 'bar');

        $view = new \Vidola\View\StoredTemplatableFileView();
        $view->setTemplate($template);
        $view->setFilename('foo');
        $view->setOutputDir('/doesnotexist');
        $view->addApi($api);

        try {
            $view->render($template);
            $this->fail();
        } catch (\Exception $e) {
            return;
        }
    }
}
