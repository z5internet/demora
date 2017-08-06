<?php

namespace z5internet\ReactUserFramework\installationFiles\scripts;

class controllers
{

    public function install($dir) {

		$this->rcopy($dir.'/../../../installationFiles/controllers/',base_path('app/Http/Controllers/'));

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
