<?php

//媒体类型
define('TEXT', 'TEXT');		//文本
define('IMAGE', 'IMAGE');	//图片
define('MUSIC', 'MUSIC');	//音乐
define('VIDEO', 'VIDEO');	//视频
define('ANNEX', 'ANNEX');	//附件
define('CODE', 'CODE');		//代码

//状态码
define('NORMAL', 'NORMAL');		//正常
define('CLOSED', 'CLOSED');		//关闭
define('HIDDEN', 'HIDDEN');		//隐藏
define('DELETED', 'DELETED');	//删除

//错误码
define('SUCCESS', 'SUCCESS');		//成功
define('ERROR', 'ERROR');			//错误

//返回类型
define('DATA', 'DATA');		//结果集	

//页码
define('PAGESIZE8', 8);
define('PAGESIZE12', 12);
define('PAGESIZE24', 24);

//存储路径
define('UPLOAD_PATH', '/Public/upload/');	//公共存放位置
define('ARTICLE_PATH', 'article/');			//文章模块存放位置
define('IMAGE_PATH', 'images/');			//图片存放位置
define('VIDEO_PATH', 'video/');				//视频存放位置
define('MUSIC_PATH', 'music/');				//音频存放位置
define('CODE_PATH', 'code/');				//代码存放位置
define('ANNEX_PATH', 'annex/');				//附件存放位置