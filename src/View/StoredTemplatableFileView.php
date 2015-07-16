<?php

/**
 * @package Vidola
 */
namespace Vidola\View;

/**
 * @package Vidola
 *
 * Writes content of a template to a file.
 */
class StoredTemplatableFileView implements TemplatableFileView
{
    private $api = array();

    private $outputDir;

    private $template;

    private $filename;

    private $extension = '';

    /**
     * Set output directory to write files to. The default value used is
     * the system temp dir.
     *
     * @param string $dir
     */
    public function setOutputDir($dir)
    {
        $this->outputDir = $dir;
    }

    private function getOutputDir()
    {
        if (isset($this->outputDir)) {
            return $this->outputDir;
        }

        return sys_get_temp_dir();
    }

    /**
     * The name of the file, without directory and extension.
     *
     * @param string $name
     */
    public function setFilename($name)
    {
        $this->filename = $name;
    }

    private function getFilename()
    {
        if (isset($this->filename)) {
            return $this->filename;
        }

        return 'index';
    }

    /**
     * The file extension, without a `.`.
     *
     * @param string $ext
     */
    public function setFileExtension($ext)
    {
        $this->extension = $ext;
    }

    private function getFileExtension()
    {
        return $this->extension;
    }

    /**
     * Adds an API the view can use.
     *
     * @param ViewApi $api
     */
    public function addApi(ViewApi $api)
    {
        $this->api[$api->getName()] = $api;
    }

    /**
     * Render the view.
     */
    public function render()
    {
        extract($this->api);
        ob_start();
        require $this->getTemplate();
        $output = ob_get_clean();

        $this->write($output);
    }

    /**
     * Set the template to use. Full path.
     *
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    private function getTemplate()
    {
        if (isset($this->template)) {
            return $this->template;
        }

        return __DIR__
            . DIRECTORY_SEPARATOR.'..'
            . DIRECTORY_SEPARATOR.'Templates'
            . DIRECTORY_SEPARATOR.'Default'
            . DIRECTORY_SEPARATOR.'Index.php';
    }

    private function write($text)
    {
        $filename = $this->getFilename();

        $dir = $this->getOutputDir() . DIRECTORY_SEPARATOR . substr(
            $filename,
            0,
            strrpos($filename, DIRECTORY_SEPARATOR)
        );
        $filename = substr($filename, strrpos($filename, DIRECTORY_SEPARATOR));

        if (!is_dir($dir)) {
            mkdir($dir, 0700, true);
        }

        $file = $dir . $filename . '.' . $this->extension;

        $fileHandle = fopen($file, 'w');

        if (!$fileHandle) {
            throw new \Exception('Writer::write() was unable to open ' . $file);
        }

        if (fwrite($fileHandle, $text) === false) {
            throw new \Exception('Writer::write() was unable to write to ' . $file);
        }

        fclose($fileHandle);
    }
}
