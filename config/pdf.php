<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4-L',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('../temp/'),
	'pdf_a'                 => false,
	'pdf_a_auto'            => false,
	'icc_profile_path'      => '',
	'font_path' => public_path('fonts/'),
	'font_data' => [
		'examplefont' => [
			'Foco'=>'test'
		]
		// ...add as many as you want.
	]
];
