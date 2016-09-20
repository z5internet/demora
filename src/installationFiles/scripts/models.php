<?php

namespace darrenmerrett\ReactUserFramework\installationFiles\scripts;

class models
{

    public function install()
    {

		copy(__DIR__.'/../models/User.php',app_path('User.php'));

    }

}
