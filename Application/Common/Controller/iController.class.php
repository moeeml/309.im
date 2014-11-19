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
     * @desc 返回json格式结果集
     * @param string $type 结果集类型
     * @param mix $data 结果集
     * @version 1 2014-11-19 RGray
     */
    public function json_back()
    {
        $result['flag'] = $this->type;

        switch ($this->type) {
            case ERROR:
            $result['message'] = $this->message;
            break;
        default:
            $result['data'] = $this->data;
            break;
        }

        exit(json_encode($result));
    }

}