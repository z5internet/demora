<?php

namespace darrenmerrett\ReactUserFramework\installationFiles\scripts;

class controllers
{

    public function install()
    {

		$this->rcopy(__DIR__.'/../controllers/',app_path('/Http/Controllers/'));

    }

	private function rcopy($src, $dst) {
	  if (is_dir($src)) {
	  	if (!is_dir($dst)) {
	    	mkdir($dst);
	    }
	    $files = scandir($src);

	    foreach ($files as $file)
	    if ($file != "." && $file != "..") $this->rcopy("$src/$file", "$dst/$file");
	  }
	  else if (file_exists($src) && !file_exists($dst)) copy($src, $dst);
	}

}
