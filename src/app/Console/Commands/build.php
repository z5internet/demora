<?php

namespace z5internet\ReactUserFramework\App\Console\Commands;

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

        $rufJSONFile = base_path('storage/ruf.json');

        $jsonConfig = [];

        $jsonConfig['title'] = config('app.name');

        file_put_contents($rufJSONFile, json_encode($jsonConfig));

        $node_env = $this->option('dev')?'':'NODE_ENV=production';

        $dirs = [base_path('assets'), base_path('public/assets')];

        if(!file_exists($dirs[0])) {

            foreach($dirs as $dir) {

                mkdir($dir);

            }

        }

        system("$node_env webpack --config ".base_path('vendor/z5internet/ruf/src/webpack.config.js'));

        $assets_dir = base_path('public/assets');

        $assets = scandir($assets_dir);

        foreach ($assets as $ta) {

            $old = $assets_dir.'/'.$ta;

            if (preg_match("/^prot\-/", $ta)) {

                $new = base_path('assets/'.$ta);

                rename($old, $new);

                unset($old);

            }

        }

        unlink($rufJSONFile);

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
