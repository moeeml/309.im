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

    /**
     * @var MediaModel 媒体模型
     */
    public $mediaModel = NULL;

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

		$linfo = $this->field('id, name, password, avatar')->where(array('name'=>$name))->find();

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

		return $linfo;
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
	 * @version 2 2014-12-12 RGray
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
		$uid = $this->userInfoModel->add(array('id'=>$ares));

		//初始化userinfo session
		if(!empty($uid)){
			session('user_info', array('user_id'=>$uid));
		}

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

		if(empty($id)){return array();}

		$base_info = $this->where(array('id'=>$id))->find();
		$extra_info = $this->userInfoModel->where(array('id'=>$id))->find();

		unset($base_info['password']);
		return array_merge($base_info, $extra_info);
	}

	/**
	 * @desc 更新用户信息
	 * @return bool
	 * @version 2 2014-12-11 RGray
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
		!empty($this->password) && $this->password = EncryptModel::enpass($this->password);
		!empty($this->userInfoModel->birthday) && $this->userInfoModel->birthday = strtotime($this->userInfoModel->birthday);
		$this->where(array('id'=>$id))->save();
		$this->userInfoModel->where(array('id'=>$id))->save();
		return true;
	}

	/**
	 * @desc 上传用户头像
	 * @return mix
	 * @version 1 2014-12-04 RGray
	*/
	public function update_avatar()
	{
		$user_id = $this->get_uid();

		//上传图片
		$this->mediaModel = D('media');
		$config = $this->mediaModel->trans_upload_config();
		$config['savePath'] = './'.AVATAR_PATH;
		$upload = new \Think\Upload($config);
		$up_res = $upload->upload();

		if(!$up_res){
			$this->error = $upload->getError();
			return false;
		}

		//录入数据
		$data['avatar'] = UPLOAD_PATH.str_replace('./', '', $up_res[0]['savepath']).$up_res[0]['savename'];

		if(!$this->create($data)){
			return false;
		}

		return $this->where(array('id'=>$user_id))->save();
	}

	/**
	 * @desc 刷新用户信息
	 * @version 1 2014-12-12 RGray
	*/
	public function fresh_userinfo()
	{
		$user_info = $this->get_userinfo_detail();
		$this->log_userinfo($user_info);
	}

	/**
	 * @desc 裁剪用户头像
	 * @return bool
	 * @version 1 2014-12-12 RGray
	*/
	public function cutdown_avatar()
	{
		$user_info = $this->get_userinfo_detail();
		$avatar = $user_info['avatar'];

		if(empty($avatar)){
			$this->error = L('avatar_empty');
			return false;
		}

		$image = new \Think\Image();
		$image->open('.'.$avatar);

		$radio =  number_format($image->width() / I('param.width'), 1);
		$crop_width = I('param.x2') * $radio;
		$crop_height = I('param.y2') * $radio;
		$origin_width = I('param.x') * $radio;
		$origin_height = I('param.y') * $radio;

		$image->crop($crop_width, $crop_height, $origin_width, $origin_height)->save('.'.$avatar);

		return true;
	}
}