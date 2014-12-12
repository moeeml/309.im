<?php
return array(
	//输入过滤
	'DEFAULT_FILTER'  =>  'urldecode,strip_tags,stripslashes,htmlspecialchars,trim',

	//路由规则
	'URL_ROUTE_RULES'=>array(
		'test'			=>	'Article/test',
		'art-list'		=>	'Article/article_list',
		'art'			=>	'Article/article_detail',
		'art-pub'		=>	'Article/publish_article',
		'media-up'		=>	'Article/upload_media',
		'login'			=>	'User/login',
		'name-check'	=>	'User/check_username_unique',
		'register'		=>	'User/register',
		'reg'			=>	'User/show_register',
		'uinfo-view'	=>	'User/view_userinfo',
		'uinfo-edit'	=>	'User/edit_userinfo',
		'avatar-up'		=>	'User/add_avatar',
		'avatar-fix'	=>	'User/fix_avatar',
		'img-resize'	=>	'Image/resize_image',
	),

	//上传允许类型
	'UPLOAD_EXT'		=> array(
		'image'	=>	array('jpg', 'jpeg', 'gif', 'png'),
		'video'	=>	array('mp4', 'mkv', 'avi', 'mov', 'flv'),
		'music'	=>	array('mp3', 'wav', 'wma', 'ogg', 'ape', 'acc'),
		'code'	=>	array('txt', 'ini', 'code'),
		'annex'	=>	array('rar', 'zip', '7z', 'tar', 'gz', 'bz2', 'torrent'),
	),
);