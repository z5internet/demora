<?php

namespace darrenmerrett\ReactUserFramework\installationFiles\scripts;

use Illuminate\Filesystem\Filesystem;

class migrations
{

    public function install()
    {

        $migrationFiles = [
            'create_users_table',
            'create_password_resets_table',
            'create_joined_table',
            'create_change_email_table',
            'create_admins_table',
        ];

        if (!file_exists(database_path('migrations/2016_01_01_000000_'.$migrationFiles[0].'.php'))) {

            (new Filesystem)->cleanDirectory(database_path('migrations'));

        }

        foreach ($migrationFiles as $key => $value) {

            $time = '2016_01_01_0000'.str_pad($key, 2, 0, STR_PAD_LEFT);

            copy(
                __DIR__.'/../database/migrations/'.$value.'.php',
                database_path('migrations/'.$time.'_'.$value.'.php')
            );
        }
    }

}