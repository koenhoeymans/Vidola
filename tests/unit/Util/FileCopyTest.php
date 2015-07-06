<?php

namespace Vidola\Util;

class FileCopyTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->fileCopy = new \Vidola\Util\FileCopy();
    }

    private function removeFile($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }

    private function removeDir($dir)
    {
        if (is_dir($dir)) {
            rmdir($dir);
        }
    }

    /**
     * @test
     */
    public function copiesWholeDirectoryToAnother()
    {
        $this->removeFile(
            sys_get_temp_dir().DIRECTORY_SEPARATOR.'File.php'
        );

        // given
        $sourceDir = __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Support'
            .DIRECTORY_SEPARATOR.'OneFileDir';

        // when
        $this->fileCopy->copy($sourceDir, sys_get_temp_dir());

        // then
        $this->assertTrue(
            file_exists(sys_get_temp_dir().DIRECTORY_SEPARATOR.'File.php')
        );

        $this->removeFile(
            sys_get_temp_dir().DIRECTORY_SEPARATOR.'File.php'
        );
    }

    /**
     * @test
     */
    public function copyIncludesSubDirectory()
    {
        $this->removeFile(
            sys_get_temp_dir()
            . DIRECTORY_SEPARATOR.'SubDir'
            . DIRECTORY_SEPARATOR.'File1.php'
        );
        $this->removeFile(
            sys_get_temp_dir()
            . DIRECTORY_SEPARATOR.'SubDir'
            . DIRECTORY_SEPARATOR.'File2.php'
        );
        $this->removeDir(sys_get_temp_dir().DIRECTORY_SEPARATOR.'SubDir');

        // given
        $sourceDir = __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Support'
            .DIRECTORY_SEPARATOR.'DirWithSubDir';

        // when
        $this->fileCopy->copy($sourceDir, sys_get_temp_dir());

        // then
        $this->assertTrue(
            file_exists(
                sys_get_temp_dir()
                .DIRECTORY_SEPARATOR.'SubDir'
                .DIRECTORY_SEPARATOR.'File1.php'
            )
        );
        $this->assertTrue(
            file_exists(
                sys_get_temp_dir()
                .DIRECTORY_SEPARATOR.'SubDir'
                .DIRECTORY_SEPARATOR.'File2.php'
            )
        );

        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File1.php'
        );
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File2.php'
        );
        $this->removeDir(sys_get_temp_dir().DIRECTORY_SEPARATOR.'SubDir');
    }

    /**
     * @test
     */
    public function possibleToExcludeFile()
    {
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File1.php'
        );
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File2.php'
        );
        $this->removeDir(sys_get_temp_dir().DIRECTORY_SEPARATOR.'SubDir');

        // given
        $sourceDir = __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Support'
            .DIRECTORY_SEPARATOR.'DirWithSubDir';
        $exclude = __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Support'
            .DIRECTORY_SEPARATOR.'DirWithSubDir'
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File2.php';

        // when
        $this->fileCopy->copy($sourceDir, sys_get_temp_dir(), $exclude);

        // then
        $this->assertTrue(
            file_exists(
                sys_get_temp_dir()
                .DIRECTORY_SEPARATOR.'SubDir'
                .DIRECTORY_SEPARATOR.'File1.php'
            )
        );
        $this->assertFalse(
            file_exists(
                sys_get_temp_dir()
                .DIRECTORY_SEPARATOR.'SubDir'
                .DIRECTORY_SEPARATOR.'File2.php'
            )
        );

        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File1.php'
        );
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File2.php'
        );
        $this->removeDir(sys_get_temp_dir().DIRECTORY_SEPARATOR.'SubDir');
    }

    /**
     * @test
     */
    public function possibleToExcludeMultipleFiles()
    {
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File1.php'
        );
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File2.php'
        );
        $this->removeDir(sys_get_temp_dir().DIRECTORY_SEPARATOR.'SubDir');

        // given
        $sourceDir = __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Support'
            .DIRECTORY_SEPARATOR.'DirWithSubDir';
        $exclude = array(
            __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Support'
            .DIRECTORY_SEPARATOR.'DirWithSubDir'
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File1.php',
            __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Support'
            .DIRECTORY_SEPARATOR.'DirWithSubDir'
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File2.php',
        );

        // when
        $this->fileCopy->copy($sourceDir, sys_get_temp_dir(), $exclude);

        // then
        $this->assertFalse(
            file_exists(
                sys_get_temp_dir()
                .DIRECTORY_SEPARATOR.'SubDir'
                .DIRECTORY_SEPARATOR.'File1.php'
            )
        );
        $this->assertFalse(
            file_exists(
                sys_get_temp_dir()
                .DIRECTORY_SEPARATOR.'SubDir'
                .DIRECTORY_SEPARATOR.'File2.php'
            )
        );

        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File1.php'
        );
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File2.php'
        );
        $this->removeDir(sys_get_temp_dir().DIRECTORY_SEPARATOR.'SubDir');
    }

    /**
     * @test
     */
    public function possibleToExcludeDirectory()
    {
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File1.php'
        );
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File2.php'
        );
        $this->removeDir(sys_get_temp_dir().DIRECTORY_SEPARATOR.'SubDir');

        // given
        $sourceDir = __DIR__
        .DIRECTORY_SEPARATOR.'..'
        .DIRECTORY_SEPARATOR.'Support'
        .DIRECTORY_SEPARATOR.'DirWithSubDir';
        $exclude = array(
            __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Support'
            .DIRECTORY_SEPARATOR.'DirWithSubDir'
            .DIRECTORY_SEPARATOR.'SubDir',
        );

        // when
        $this->fileCopy->copy($sourceDir, sys_get_temp_dir(), $exclude);

        // then
        $this->assertFalse(
            is_dir(sys_get_temp_dir().DIRECTORY_SEPARATOR.'SubDir')
        );

        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File1.php'
        );
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File2.php'
        );
        $this->removeDir(sys_get_temp_dir().DIRECTORY_SEPARATOR.'SubDir');
    }

    /**
     * @test
     */
    public function possibleToExcludeDirectoryGivingAnException()
    {
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File1.php'
        );
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File2.php'
        );
        $this->removeDir(sys_get_temp_dir().DIRECTORY_SEPARATOR.'SubDir');

        // given
        $sourceDir = __DIR__
        .DIRECTORY_SEPARATOR.'..'
        .DIRECTORY_SEPARATOR.'Support'
        .DIRECTORY_SEPARATOR.'DirWithSubDir';
        $exclude = __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Support'
            .DIRECTORY_SEPARATOR.'DirWithSubDir'
            .DIRECTORY_SEPARATOR.'SubDir';
        $include = __DIR__
            .DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Support'
            .DIRECTORY_SEPARATOR.'DirWithSubDir'
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File1.php';

        // when
        $this->fileCopy->copy($sourceDir, sys_get_temp_dir(), $exclude, $include);

        // then
        $this->assertTrue(
            file_exists(
                sys_get_temp_dir()
                .DIRECTORY_SEPARATOR.'SubDir'
                .DIRECTORY_SEPARATOR.'File1.php'
            )
        );
        $this->assertFalse(
            file_exists(
                sys_get_temp_dir()
                .DIRECTORY_SEPARATOR.'SubDir'
                .DIRECTORY_SEPARATOR.'File2.php'
            )
        );

        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File1.php'
        );
        $this->removeFile(
            sys_get_temp_dir()
            .DIRECTORY_SEPARATOR.'SubDir'
            .DIRECTORY_SEPARATOR.'File2.php'
        );
        $this->removeDir(sys_get_temp_dir().DIRECTORY_SEPARATOR.'SubDir');
    }
}
