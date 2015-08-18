<?php
namespace PMVC\PlugIn\tmp;

// \PMVC\l(__DIR__.'/xxx.php');

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\tmp';

register_shutdown_function(array(${_INIT_CONFIG}[_CLASS],'clean_temps'));

class tmp extends \PMVC\PlugIn
{
    private $temp = array();
    private $tmpdir_append_str = '_dir/';

    public static function clean_temps()
    {
        $tempFiles =& \PMVC\getOption('TemporaryFiles');
        if (empty($tempFiles)) {
            return;
        }
        $fl = \PMVC\plug('file_list');
        foreach($tempFiles as $item){
            if (is_file($item)) {
                unlink($item);
            } elseif (is_dir($item)) {
                $fl->rmdir($item);
            }
        }
    }

    public function init()
    {
        if (!empty($this['parent'])) {
            $this['parent'] = sys_get_temp_dir();
        }
        $this->temp =& \PMVC\getOption('TemporaryFiles');
    }

    public function create_file($prefix=null)
    {
        $file = tempnam($this['parent'], 'zip_');
        $this->temp[$file] = $file;
    }

    public function create_folder($prefix=null)
    {
        $tmp = $this->create_file($prefix);
        $tmp_dir = $tmp.$this->tmpdir_append_str;
        mkdir($tmp_dir,-1,true);
        $this->temp[$tmp_dir] = $tmp_dir;
    }

}
