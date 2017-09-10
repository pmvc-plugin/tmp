<?php

namespace PMVC\PlugIn\tmp;

use PMVC\HashMap;
use PMVC\PlugIn;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\tmp';

const ALL_TEMP_FILES = '__AllTemporaryFiles__';
\PMVC\option(
    'set',
    ALL_TEMP_FILES,
    new HashMap() 
);

register_shutdown_function([
    ${_INIT_CONFIG}[_CLASS],
    'cleanTemps'
]);

class tmp extends PlugIn
{
    private $temp = [];
    const TMP_DIR_APPEND_STR = '_dir/';

    public static function cleanTemps()
    {
        $tempFiles = \PMVC\getOption(
            ALL_TEMP_FILES
        );
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
        if (empty($this['parent'])) {
            $this['parent'] = sys_get_temp_dir();
        }
        $this->temp = \PMVC\getOption(
            ALL_TEMP_FILES
        );
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
        $tmp_dir = $tmp.self::TMP_DIR_APPEND_STR;
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
