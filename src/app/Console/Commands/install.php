<?php

namespace z5internet\ReactUserFramework\app\Console\Commands;

use Illuminate\Console\Command;

use z5internet\ReactUserFramework\addOns;

use Illuminate\Filesystem\Filesystem;

use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\MountManager;

class install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'react-user-framework:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install react-user-framework resources, setup npm dependancies';

    protected $files;

    public function __construct(Filesystem $files)
    {

        parent::__construct();
        $this->files = $files;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $scripts = collect([
            \z5internet\ReactUserFramework\installationFiles\scripts\config::class,
            \z5internet\ReactUserFramework\installationFiles\scripts\migrations::class,
            \z5internet\ReactUserFramework\installationFiles\scripts\models::class,
            \z5internet\ReactUserFramework\installationFiles\scripts\controllers::class,
            \z5internet\ReactUserFramework\installationFiles\scripts\npm::class,
        ]);

        $scripts->each(function ($installer) { (new $installer($this))->install(__DIR__); });

        foreach (addOns::getAddons() as $key => $value) {

            if (array_get($value, 'install')) {

                $v = new $value['install']();

                $v->install();

            }

        }

        $root = __DIR__.'/../../../';

        $this->publishDirectory($root.'resources/assets/react-app/', base_path('resources/assets/react-app/'));

        $this->publishDirectory($root.'resources/views/', base_path('resources/views/vendor/ruf/'));

        $this->call('jwt:secret');

        $this->comment('Installation complete');

    }

    protected function publishFile($from, $to)
    {
        if ($this->files->exists($to)) {
            return;
        }
        $this->createParentDirectory(dirname($to));
        $this->files->copy($from, $to);
        $this->status($from, $to, 'File');
    }

    protected function publishDirectory($from, $to)
    {
        $manager = new MountManager([
            'from' => new Flysystem(new LocalAdapter($from)),
            'to' => new Flysystem(new LocalAdapter($to)),
        ]);
        foreach ($manager->listContents('from://', true) as $file) {
            if ($file['type'] === 'file' && (!$manager->has('to://' . $file['path']))) {
                $manager->put('to://' . $file['path'], $manager->read('from://' . $file['path']));
            }
        }
        $this->status($from, $to, 'Directory');
    }

    protected function createParentDirectory($directory)
    {
        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }
    }

    protected function status($from, $to, $type)
    {
        $from = str_replace(base_path(), '', realpath($from));
        $to = str_replace(base_path(), '', realpath($to));
        $this->line('<info>Copied ' . $type . '</info> <comment>[' . $from . ']</comment> <info>To</info> <comment>[' . $to . ']</comment>');
    }

}
