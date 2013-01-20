<?php

return array(
	'source'			=> __DIR__ . DIRECTORY_SEPARATOR . 'BuildFile.txt',
	'target-dir'		=> sys_get_temp_dir(),
	'template'			=> __DIR__
							. DIRECTORY_SEPARATOR . 'BuildFileTemplate'
							. DIRECTORY_SEPARATOR . 'MiniTemplate.php',
	'copy'				=> array(__DIR__
							. DIRECTORY_SEPARATOR . 'BuildFileTemplate'
							. DIRECTORY_SEPARATOR . 'BuildSub'
							. DIRECTORY_SEPARATOR . 'BuildFile.js'),
	'copy-exclude'		=> __DIR__
							. DIRECTORY_SEPARATOR . 'BuildFileTemplate'
							. DIRECTORY_SEPARATOR . 'BuildSub'
);