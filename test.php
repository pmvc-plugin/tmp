<?php
PMVC\Load::plug();
PMVC\addPlugInFolders(['../']);
class TmpTest extends PHPUnit_Framework_TestCase
{
    private $_plug = 'tmp';
    function testPlugin()
    {
        ob_start();
        print_r(PMVC\plug($this->_plug));
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains($this->_plug,$output);
    }

    function testGetTempFile()
    {
        $prefix = 'unit_test_file';
        $plug = PMVC\plug($this->_plug);
        $file = $plug->file($prefix); 
        $this->assertContains($prefix,$file);
        $this->assertTrue(is_file($file));
    }

    function testGetTempFolder()
    {
        $prefix = 'unit_test_dir';
        $plug = PMVC\plug($this->_plug);
        $dir = $plug->dir($prefix); 
        $this->assertContains($prefix,$dir);
        $this->assertTrue(is_dir($dir));
    }
}
