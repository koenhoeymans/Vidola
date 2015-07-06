<?php

require_once 'TextModPlugin.php';

return array(
    'source'            => __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Plugin.txt',
    'target-dir'        => sys_get_temp_dir(),
    'template'            => __DIR__
                            .DIRECTORY_SEPARATOR.'..'
                            .DIRECTORY_SEPARATOR.'MiniTemplate'
                            .DIRECTORY_SEPARATOR.'MiniTemplate.php',
    'plugins'            => array('Company\\Vidola\\TextModPlugin'),
);
