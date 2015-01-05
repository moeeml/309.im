<?php
namespace Common\Behavior;
use Home\Model\UserModel;

/**
 * @desc 处理Http响应头行为类
 */
class LoginVerifyBehavior{

    public function run()
    {
    	$uid = new UserModel()->check_login();
    }
}

