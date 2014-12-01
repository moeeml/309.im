<?php
namespace Home\Model;
use Think\Model;
class MediaModel extends Model {

    /**
     * @var array 数据过滤以及自动填充
     */
    protected $_auto = array(
            array('comm_id', 0, self::MODEL_INSERT),
            //array('type', TEXT, self::MODEL_INSERT),
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
        $desc = I('post.description');
        $art_id = I('post.art_id');

        $config = array(
                'maxSize'    =>    3145728,
                'rootPath'   =>    './'.UPLOAD_PATH,
                'savePath'   =>    './'.ARTICLE_PATH.IMAGE_PATH,
                'saveName'   =>    array('uniqid',''),
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub'    =>    true,
                'subName'    =>    array('date','Ymd'),
            );

        $upload = new \Think\Upload($config);
        $up_res = $upload->upload();

        if(!$up_res) {
            $this->error = $upload->getError();
            return false;
        }
        
        $i = 0;
        $res = true;
        $add_res = true;
        $add_info = array();
        $item = NULL;
        $item->art_id = $art_id;

        foreach ($up_res as $r) {
            $savepath = str_replace('./', '', $r['savepath']);
            $item->link = UPLOAD_PATH.$savepath.$r['savename'];
            $item->description = $desc[$i];
            $item->type = IMAGE;

            if(!$this->create($item)){
                return false;
            }

            $up_res[$i]['savepath'] = $item->link;
            $add_info[] = $add_res = $this->add();
            $res = $add_res && $res;

            $i++;
        }

        if($res){
            return $up_res;
        }

        return false;  
    }
}