<?php

namespace z5internet\ReactUserFramework\installationFiles\scripts;

class models
{

    public function install()
    {

    	if (preg_match('/Lumen/', app()->version())) {

			copy(__DIR__.'/../models/User-lumen.php',base_path('app/User.php'));

			return;

		}

		copy(__DIR__.'/../models/User-laravel.php',base_path('app/User.php'));

    }

}
