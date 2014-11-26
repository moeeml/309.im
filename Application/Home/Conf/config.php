<?php
return array(
	//路由规则
	'URL_ROUTE_RULES'=>array(
		'article-list'		=>	'Article/article_list',
		'article/:id'		=>	array('Article/article_detail',array('art_id'=>':1')),
		'article-publish'	=>	'Article/publish_article',
	),
);