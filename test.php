<?php
namespace PMVC\PlugIn\tmp;

use PHPUnit_Framework_TestCase;

\PMVC\Load::plug();
\PMVC\addPlugInFolders(['../']);
class TmpTest extends PHPUnit_Framework_TestCase
{
    private $_plug = 'tmp';

    function setup()
    {
        \PMVC\unplug($this->_plug);
        \PMVC\plug($this->_plug, [
            'parent'=> __DIR__.'/.tmp'
        ]);
    }

    function testPlugin()
    {
        ob_start();
        print_r(\PMVC\plug($this->_plug));
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains($this->_plug,$output);
    }

    function testGetTempFile()
    {
        $prefix = 'unit_test_file';
        $plug = \PMVC\plug($this->_plug);
        $file = $plug->file($prefix); 
        $this->assertContains($prefix,$file);
        $this->assertTrue(is_file($file));
    }

    function testGetTempFolder()
    {
        $prefix = 'unit_test_dir';
        $plug = \PMVC\plug($this->_plug);
        $dir = $plug->dir($prefix); 
        $this->assertContains($prefix,$dir);
        $this->assertTrue(is_dir($dir));
    }

    function testCleanTempWithFile()
    {
        $plug = \PMVC\plug($this->_plug);
        $file = $plug->file(); 
        $this->assertTrue(is_file($file));
        tmp::cleanTemps();
        $this->assertFalse(is_file($file));
    }

    function testCleanTempWithFolder()
    {
        $plug = \PMVC\plug($this->_plug);
        $dir = $plug->dir(); 
        $this->assertTrue(is_dir($dir));
        tmp::cleanTemps();
        $this->assertFalse(is_dir($dir));
    }
}
