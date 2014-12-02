<?php
namespace Common\Behavior;

/**
 * @desc 处理Http响应头行为类
 */
class BuildHeaderBehavior{

    public function run()
    {
    	$referer = parse_url($_SERVER['HTTP_REFERER']);
    	$origin = $referer['host'];

    	if(!empty($referer['port'])){
    		$origin .= ':' . $referer['port'];
    	}

        header('Access-Control-Allow-Origin:'.$origin);
        header('Access-Control-Allow-Credentials:true');
        header('Access-Control-Allow-Methods:POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers:XML-Requested-With');
    }
}

