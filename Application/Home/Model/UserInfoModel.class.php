<?php
/**
 * @desc 用户信息模型
*/
namespace Home\Model;
use Think\Model;
class UserInfoModel extends Model{

	/**
     * @var array 自动验证
     */
    protected $_validate = array(
            array('id', 'require', '{%id_empty}'),
        );

	public function __construct()
	{
		parent::__construct();
	}
}