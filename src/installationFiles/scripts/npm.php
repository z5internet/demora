<?php

namespace z5internet\ReactUserFramework\installationFiles\scripts;

class npm
{

    public function install($dir)
    {

        $package_file = base_path('package.json');

        if (!file_exists($package_file)) {

            file_put_contents($package_file, '{}');

        }

        $package = file_get_contents($package_file);

        $package = json_decode($package,true);

        $deps = json_decode(file_get_contents($dir.'/../../../../package.json'),true)['dependencies'];

        foreach($deps as $key => $val) {

            $package['dependencies'][$key] = $val;

        }

        file_put_contents($package_file, json_encode($package,JSON_PRETTY_PRINT));

        system('npm install --prefix '.base_path());

    }

}
