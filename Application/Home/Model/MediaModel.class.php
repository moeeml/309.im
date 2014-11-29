<?php
namespace Home\Model;
use Think\Model;
class MediaModel extends Model {

    /**
     * @var array 数据过滤以及自动填充
     */
    protected $_auto = array(
            array('comm_id', 0, self::MODEL_INSERT),
            array('type', TEXT, self::MODEL_INSERT),
            array('status', NORMAL, self::MODEL_INSERT),
            array('create_time', 'time', self::MODEL_INSERT, 'function'),
        );

    /**
     * @var array 自动验证
     */
    protected $_validate = array(
            array('description', 'require', '{%article_content_empty}'),
        );

    /**
     * @var bool 批量验证
     */
    protected $patchValidate = true;

    public function __construct(){
        parent::__construct();
    }

    /**
     * @desc 处理文件上传
     * @return mix
     * @version 1 2014-11-29 RGray
     */
    public function is_upload()
    {
        if(empty($_FILES)){return true;}

        $config = array(
                'maxSize'    =>    3145728,
                'savePath'   =>    UPLOAD_PATH.ARTICLE_PATH.IMAGES_PATH,
                'saveName'   =>    array('uniqid',''),
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub'    =>    true,
                'subName'    =>    array('date','Ymd'),
            );

        $upload = new \Think\Upload($config);
        return $upload->upload();
    }
}