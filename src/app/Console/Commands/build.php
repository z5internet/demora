<?php

namespace z5internet\ReactUserFramework\app\Console\Commands;

use Illuminate\Console\Command;

class build extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'react-user-framework:build {--dev}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build react-user-framework';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $node_env = $this->option('dev')?'':'NODE_ENV=production';

        $dirs = [base_path('public/assets'), base_path('assets')];

        if(!file_exists($dirs[0])) {

            foreach($dirs as $dir) {

                mkdir($dir);

                foreach (['admin', 'auth'] as $ta) {

                    mkdir($dir.'/'.$ta);

                }

            }

        }

        system("$node_env webpack --config ./vendor/z5internet/ruf/src/webpack.config.js");

        $assets_dir = base_path('public/assets');

        $assets = scandir($assets_dir);

        foreach ($assets as $ta) {

            $old = $assets_dir.'/'.$ta;

            if (is_dir($old) && !preg_match("/\./", $ta)) {

                $new = base_path('assets/'.$ta);

                $this->recurse_copy($old, $new);

                $this->deleteDir($old);

            }

        }

        print "\n";

    }

    public function recurse_copy($src,$dst) {

        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);

    }

    public static function deleteDir($dirPath) {

        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);

    }

}
