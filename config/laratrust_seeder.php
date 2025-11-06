<?php

return [
	'role_structure'      => [
		'superadministrator' => [
			'users'             => 'c,r,u,d',
			'acl'               => 'c,r,u,d',
			'conteudo'          => 'c,r,u,d',
			'profile'           => 'r,u'
		],
		'administrator' => [
			'users'        => 'c,r,u,d',
			'profile'      => 'r,u',
			'conteudo'     => 'c,r,u,d',
		],
		'user'     => [
			'profile' => 'r,u'
		],
	],
	'permission_structure' => [

	],
	'permissions_map' => [
		'c'              => 'create',
		'r'              => 'read',
		'u'              => 'update',
		'd'              => 'delete'
	]
];
