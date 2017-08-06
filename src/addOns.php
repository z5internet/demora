<?php namespace z5internet\ReactUserFramework;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

class addOns extends Controller
{

	public static function getAddons() {

		$addOns = self::$addons;

		$out = [];

        foreach($addOns as $key => $value) {

            if (self::isAddOnLoaded($key)) {

            	$out[$key] = $value;

            }

		}

		return $out;

	}

    private static $addons = [
        'social' => [
            'directory' => 'z5internet/react-social/src/',
            'providerClass' => \z5internet\ReactSocial\ReactSocialServiceProvider::class,
            'providerUrl' => 'ReactSocialServiceProvider.php',
        ],
        'messages' => [
            'directory' => 'z5internet/react-messages/src/',
            'providerClass' => \z5internet\ReactMessages\ReactMessagesServiceProvider::class,
            'providerUrl' => 'ReactMessagesServiceProvider.php',
        ],
        'common' => [
            'directory' => 'z5internet/react-common/src/',
            'providerClass' => \z5internet\ReactCommon\ReactCommonServiceProvider::class,
            'providerUrl' => 'ReactCommonServiceProvider.php',
        ],
        'email' => [
            'directory' => 'z5internet/ruf-email/src/',
            'providerClass' => \z5internet\ReactEmail\ReactEmailServiceProvider::class,
            'providerUrl' => 'ReactEmailServiceProvider.php',
            'install' => 'z5internet\ReactEmail\App\Http\Controllers\InstallController',
        ],
        'tag' => [
            'directory' => 'z5internet/ruf-tag/src/',
            'providerClass' => \z5internet\ReactTag\ReactTagServiceProvider::class,
            'providerUrl' => 'ReactTagServiceProvider.php',
        ],
    ];

    private static function isAddOnLoaded($addon) {

        return file_exists(base_path('vendor/'.self::$addons[$addon]['directory'].self::$addons[$addon]['providerUrl']));

	}

}

