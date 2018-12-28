<?php

return [

	'caching' => false,

	'website' => [

		'disallow_public_signups' => false,
		'cookie' => [
			'secure' => env('cookie-secure', false),
			'time' => env('cookie-time', (1 * 24 * 60 * 60)),
			'domain' => env('cookie-domain', ''),
		],
/**
		'multiAccounts' => [
			'label' => 'Company',
			'pluralLabel' => 'Companies',
			'allowMultiUsers' => true,
		],
**/

	],

	'setup' => [

		'username_required' => true,
		'upload_profile_pic' => true,

	],

	'email' => [
		'contact_us' => '',
	],

	'images' => [

		'profile_image_public_path' => '/i/',
		'sizes' => [
			50,100,200
		],

	],

	'pay' => [

		'stripe' => [

			'secret_key' => 'sk_test_XXX',
			'publishable_key' => 'pk_test_XXX',
			'description' => 'Text description',

		],

		'roles_allowed_to_purchase' => [

			255, // account owner

		],

		'create_invoice_detail_cron' => true,

	],

];