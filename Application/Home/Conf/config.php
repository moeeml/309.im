<?php
return array(
	//输入过滤
	'DEFAULT_FILTER'  =>  'urldecode,strip_tags,stripslashes,htmlspecialchars',

	//路由规则
	'URL_ROUTE_RULES'=>array(
		'art-list'		=>	'Article/article_list',
		'art'			=>	'Article/article_detail',
		'art-pub'		=>	'Article/publish_article',
		'media-up'		=>	'Article/upload_media',
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