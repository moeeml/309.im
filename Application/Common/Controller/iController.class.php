<?php
namespace Common\Controller;
use Think\Controller;
class iController extends Controller {

    /**
     * @var 返回类型
     */
    public $type;

    /**
     * @var 返回消息
     */
    public $message;

    /**
     * @var 返回结果集
     */
    public $data;

	public function __construct()
	{
		parent::__construct();

        $this->type = DATA;
	}

    /**
     * @desc 判断客户端类型，返回不同格式数据
     * @version 2 2014-12-05 RGray
     */
    public function play()
    {
        if(I('param.respon') == 'json'){
            $this->json_back();
        }

        if(strpos($_SERVER["HTTP_USER_AGENT"], 'Windows NT') || I('param.respon') == 'html'){
            $this->html_back();
        }

        $this->json_back();
    }

    /**
     * @desc 返回json格式结果集
     * @version 2 2014-12-05 RGray
     */
    public function json_back()
    {
        $result['flag'] = $this->type;
        $result['data'] = $this->data;
        exit(json_encode($result));
    }

    /**
     * @desc 返回html格式结果集
     * @version 1 2014-12-05 RGray
     */
    public function html_back()
    {
        $this->assign('flag', $this->type);
        $this->assign('data', $this->data);
        $this->display();
        exit();
    }

}