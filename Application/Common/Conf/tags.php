<?php

include_once('define.php');

return array(
	'app_begin' => array('Behavior\CheckLangBehavior'),
	'action_begin'=>array('Common\Behavior\BuildHeaderBehavior'),
);