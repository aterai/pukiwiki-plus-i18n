<?php

$auth_api = array(
        // Basic or Digest
        'plus'                  => array(
                'use'           => 1,
                'displayname'   => 'Normal',
        ),
        // TypeKey
        'typekey'               => array(
                'use'           => 0,
                'site_token'    => '',
                'need_email'    => 0,
        ),
        // Hatena
        'hatena'                => array(
                'use'           => 0,
                'api_key'       => '',
                'sec_key'       => '',
        ),
        // JugemKey
        'jugemkey'              => array(
                'use'           => 0,
                'api_key'       => '',
                'sec_key'       => '',
        ),
	// RemoteIP
        'remoteip'              => array(
                'use'           => 0,
                'hidden'        => 1,
        ),
        // livedoor Auth
        'livedoor'              => array(
                'use'           => 0,
                'app_key'       => '',
                'sec_key'       => '',
        ),
	// OpenID
	'openid'		=> array(
		'use'		=> 1,
		'mixi'  => array(
			'my_id'		=> array(''), // 'userid1','userid2', ...
			'community_id'	=> array(''), // 'community1', 'community2', ...
		),
	),
	// QueryStringAuth
	'querystringauth'	=> array(
		'use'		=> 0,
		'hidden'	=> 1,
	),

);

?>
