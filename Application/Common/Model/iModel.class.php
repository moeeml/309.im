<?php
/**
 * @desc 基础模型
*/
namespace Common\Model;
use Think\Model;
class iModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

    /**
     * @desc 判断参数是否为空
     * @version 1 2014-12-12 RGray
     */
	public function check_param($param)
	{
		$param = (array)$param;

		foreach ($param as $p) {
			if (empty($p)) {
				$this->error = L('param_error');
				return false;
			}
		}

		return true;
	}
}