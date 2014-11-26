<?php
return array(

	//数据库配置信息
	'DB_TYPE'   => 'mysql', 
	'DB_HOST'   => 'localhost', 
	'DB_NAME'   => '309im', 
	'DB_USER'   => 'root', 
	'DB_PWD'    => 'root', 
	'DB_PORT'   => 3306, 
	'DB_PREFIX' => '', 
	'DB_CHARSET'=> 'utf8', // 字符集

	//输入过滤
	'DEFAULT_FILTER'  =>  'strip_tags,stripslashes,htmlspecialchars',

	//多语言
	'LANG_SWITCH_ON' 	=> true,
	'LANG_AUTO_DETECT' 	=> true,
	'LANG_LIST'        	=> 'zh-cn',
	'VAR_LANGUAGE'     	=> 'l',

	//路由
	'URL_MODEL' 		=> 2,
	'URL_ROUTER_ON'   	=> true,
);