<?php
namespace Common\Behavior;

/**
 * @desc 处理Http响应头行为类
 */
class BuildHeaderBehavior{

    public function run()
    {
        header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Credentials:true');
        header('Access-Control-Allow-Methods:POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers:XML-Requested-With');
    }
}

