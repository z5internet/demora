<?php

namespace z5internet\ReactUserFramework\installationFiles\scripts;

use Illuminate\Filesystem\Filesystem;

class config
{

    public function install($dir)
    {

    	$configDir = base_path('config/');

    	$filesystem = new Filesystem;

        $files = $filesystem->files($dir.'/../../../installationFiles/config/');

        foreach ($files as $file) {

        	$filename = explode('/', $file);

        	$filename = end($filename);

        	$configFile = $configDir.$filename;

        	if (!$filesystem->exists($configFile)) {

	            copy(
	                $file,
	                $configFile
	            );

        	}

        }

    }

}