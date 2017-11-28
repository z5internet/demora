<?php

namespace z5internet\ReactUserFramework\installationFiles\scripts;

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
            'create_app_managers_table',
            'create_teams_table',
            'create_team_users_table',
            'create_invoices_table',
            'create_payment_details_table',
            'create_errorlogs_table',
            'create_products_table',
            'create_ui_notifications_table',
            'create_subscribed_plans_table',
            'create_invoice_detail_table',
            'create_push_table',
            'add_foreign_key_to_password_resets_table',
            'alter_notifications_id',
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