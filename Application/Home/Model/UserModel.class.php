<?php
/**
 * @desc 用户模型
*/
namespace Home\Model;
use Think\Model;
use Common\Model\encryptModel;
class UserModel extends Model{
    /**
     * @var array 自动验证
     */
    protected $_validate = array(
            array('name', 'require', '{%name_empty}'),
        );

    /**
     * @var bool 批量验证
     */
    protected $patchValidate = true;

    /**
     * @var bool 批量验证
     */
    protected $userInfoModel = NULL;

	public function __construct()
	{
		parent::__construct();

		$this->userInfoModel = D('userInfo');
	}

	/**
	 * @desc 记录用户信息
	 * @version 1 2014-12-03 RGray
	*/
	public function log_userinfo($info = array())
	{
		$user_info = $this->get_userinfo();
		session('user_info', array_merge($user_info, $info));
	}

	/**
	 * @desc 获取用户ID
	 * @return int
	 * @version 1 2014-12-03 RGray
	*/
	public function get_uid()
	{
		$user_info = $this->get_userinfo();
		return $user_info['user_id'];
	}

	/**
	 * @desc 获取用户信息
	 * @return array
	 * @version 1 2014-12-03 RGray
	*/
	public function get_userinfo()
	{
		$user_info = session('user_info');
		return !empty($user_info) ? $user_info : array();
	}

	/**
	 * @desc 获取用户信息
	 * @return mix
	 * @version 1 2014-12-03 RGray
	*/
	public function check_login()
	{
		$name = I('post.name');
		$password = I('post.password');

		$linfo = $this->field('id, name, password')->where(array('name'=>$name))->find();

		//用户名不存在
		if(empty($linfo)){
			$this->message = L('no_user_name');
			return false;
		}

		//密码错误
		if($linfo['password'] !== EncryptModel::enpass($password)){
			$this->message = L('password_wrong');
			return false;
		}

		return $linfo['id'];
	}

	/**
	 * @desc 检查用户名是否存在
	 * @return bool
	 * @version 1 2014-12-03 RGray
	*/
	public function check_name()
	{
		$name = I('post.name');
		$id = $this->get_uid();

		if($id){
			$where['id'] = array('neq', $id);
		}

		$where['name'] = array('eq', $name);
		$res = $this->field('id')->where($where)->find();

		return (bool)!$res;
	}

	/**
	 * @desc 录入用户注册信息
	 * @return mix
	 * @version 1 2014-12-03 RGray
	*/
	public function insert_user()
	{
		//检查用户名唯一
		if(!$this->check_name()){
			$this->error = L('name_exist');
			return false;
		}

		//创建数据
		if(!$this->create()){
			return false;
		}

		//密码加密
		$this->password = EncryptModel::enpass($this->password);
		$this->create_time = time();
		$ares = $this->add();
		
		if(!$ares){
			return false;
		}

		//录入数据
		$this->userInfoModel->add(array('id'=>$ares));

		return true;
	}

	/**
	 * @desc 获取用户信息详细
	 * @return array
	 * @version 1 2014-12-03 RGray
	*/
	public function get_userinfo_detail()
	{
		$id = $this->get_uid();

		$base_info = $this->where(array('id'=>$id))->find();
		$extra_info = $this->userInfoModel->where(array('id'=>$id))->find();

		unset($base_info['password']);
		return array_merge($base_info, $extra_info);
	}

	/**
	 * @desc 更新用户信息
	 * @return bool
	 * @version 1 2014-12-03 RGray
	*/
	public function update_userinfo()
	{
		$id = $this->get_uid();

		//检查用户名唯一
		if(!$this->check_name()){
			$this->message = L('name_exist');
			return false;
		}

		//创建数据
		$ucreate = $this->create();
		$uicreate = $this->userInfoModel->create();

		if(!$ucreate || !$uicreate){
			$user_error = $this->getError();
			$userinfo_error = $this->userInfoModel->getError();
			$this->message = array_filter(array_merge((array)$user_error, (array)$userinfo_error));
			return false;
		}

		//更新数据
		$this->password = EncryptModel::enpass($this->password);
		$this->where(array('id'=>$id))->save();
		$this->userInfoModel->where(array('id'=>$id))->save();
		return true;
	}
}