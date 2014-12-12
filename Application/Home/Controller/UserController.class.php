<?php
/**
 * @desc 用户模块
*/
namespace Home\Controller;
use Common\Controller\iController;
class UserController extends iController
{
    /**
     * @var UserModel 用户模型
     */	
	private $userModel = NULL;

    /**
     * @var UserInfoModel 用户信息模型
     */	
	private $userInfoModel = NULL;

	/**
     * @var mix message
     */
    public $message;

	public function __construct()
	{
		parent::__construct();

		$this->userModel = D('user');
		$this->userInfoModel = D('userinfo');
	}

	/**
	 * @desc 用户登录
	 * @version 1 2014-12-03 RGray
	*/
	public function login()
	{
		$res = $this->userModel->check_login();

		if(!$res){
			$this->type = ERROR;
			$this->data = $this->userModel->message;
		}else{
			$this->data = L('login_success');
			$this->userModel->log_userinfo(array('user_id'=>$res['id'], 'avatar'=>$res['avatar']));
		}

		$this->play();
	}

	/**
	 * @desc 检查用户名是否唯一
	 * @version 1 2014-12-03 RGray
	*/
	public function check_username_unique()
	{
		$res = $this->userModel->check_name();

		if(!$res){
			$this->type = ERROR;
			$this->data = L('name_exist');
		}else{
			$this->data = L('name_enable');
		}

		$this->play();
	}

	/**
	 * @desc 显示用户注册页
	 * @version 1 2014-12-12 RGray
	*/
	public function show_resgister()
	{
		$this->play();
	}

	/**
	 * @desc 用户注册
	 * @version 1 2014-12-03 RGray
	*/
	public function register()
	{
		$res = $this->userModel->insert_user();

		if(!$res){
			$this->type = ERROR;
			$this->data = $this->userModel->getError();
		}else{
			$this->data = L('register_success');
		}

		$this->play();
	}

	/**
	 * @desc 修改用户信息
	 * @version 1 2014-12-03 RGray
	*/
	public function view_userinfo()
	{
		$this->type = DATA;
		$this->data = $this->userModel->get_userinfo_detail();

		$this->play();
	}

	/**
	 * @desc 修改用户信息
	 * @version 1 2014-12-03 RGray
	*/
	public function edit_userinfo()
	{
		$res = $this->userModel->update_userinfo();

		if(!$res){
			$this->type = ERROR;
			$this->data = $this->userModel->message;
		}else{
			$this->data = L('userinfo_update_success');
		}

		$this->play();
	}

	/**
	 * @desc 添加用户头像
	 * @version 1 2014-12-03 RGray
	*/
	public function add_avatar()
	{
		$res = $this->userModel->update_avatar();

		if(!$res){
			$this->type = ERROR;
			$this->data = $this->userModel->getError();
		}else{
			$this->data = L('avatar_upload_success');
			$this->userModel->fresh_userinfo();
		}

		$this->play();
	}

	/**
	 * @desc 裁剪用户头像
	 * @version 1 2014-12-12 RGray
	*/
	public function fix_avatar()
	{
		$res = $this->userModel->cutdown_avatar();

		if(!$res){
			$this->type = ERROR;
			$this->data = $this->userModel->getError();
		}else{
			$this->data = L('avatar_cutdown_success');
			$this->userModel->fresh_userinfo();
		}

		$this->play();		
	}
}