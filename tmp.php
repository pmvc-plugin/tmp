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
        foreach($tempFiles as $item=>$prefix){
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

    public function file($prefix=null)
    {
        $file = tempnam($this['parent'], $prefix);
        if (is_file($file)) {
            $this->temp[$file] = $prefix;
        }
        return $file;
    }

    public function dir($prefix=null)
    {
        $tmp = $this->file($prefix);
        $tmp_dir = $tmp.$this->tmpdir_append_str;
        if (!is_dir($tmp_dir)) {
            $is_success = mkdir($tmp_dir,-1,true);
            if ($is_success) {
                $this->temp[$tmp_dir] = $prefix;
                return $tmp_dir;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
