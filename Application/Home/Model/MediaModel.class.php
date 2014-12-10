<?php
/**
 * @desc 媒体模型
*/
namespace Home\Model;
use Think\Model;
class MediaModel extends Model {

    /**
     * @var array 数据过滤以及自动填充
     */
    protected $_auto = array(
            array('comm_id', 0, self::MODEL_INSERT),
            array('status', NORMAL, self::MODEL_INSERT),
            array('create_time', 'time', self::MODEL_INSERT, 'function'),
        );

    /**
     * @var array 自动验证
     */
    protected $_validate = array(
            //array('description', 'require', '{%article_content_empty}'),
        );

    /**
     * @var bool 批量验证
     */
    protected $patchValidate = true;

    /**
     * @var object 媒体对象
     */
    protected $item = NULL;

    /**
     * @var type 媒体类型
     */
    protected $type = 'image';

    public function __construct(){
        parent::__construct();

        $fkey = array_keys($_FILES);
        $this->type = strtolower($fkey[0]);
    }

    /**
     * @desc 处理文件上传
     * @return mix
     * @version 1 2014-11-29 RGray
     */
    public function is_upload()
    {   
        $art_id = I('post.art_id');
        $desc = I('post.description');
        $link = array_filter(I('post.link'));
        $limit = I('post.limit');
        $width = I('post.width');
        $height = I('post.height');

        //不是外链则上传文件
        if(empty($link)){
            $up_res = $this->upload_file();

            if(!$up_res){return false;}

            foreach ($up_res as $u) {
                $tmp_size = $u['size']  / 1024;
                $size[] = $tmp_size > 1024  ? round($tmp_size / 1024).'M' : round($tmp_size).'K';
                $link[] = UPLOAD_PATH.str_replace('./', '', $u['savepath']).$u['savename'];
            }
        }

        //录入多媒体信息
        $res = true;
        $add_res = true;
        $this->item->art_id = $art_id;
        $this->item->type = strtoupper($this->type);

        foreach ($link as $k=>$r) {
            $this->item->link = $r;
            $this->item->description = $desc[$k];

            !empty($limit[$k]) && $this->item->limit = $limit[$k];
            !empty($width[$k]) && $this->item->width = $width[$k];
            !empty($height[$k]) && $this->item->height = $height[$k];
            !empty($size[$k]) && $this->item->size = $size[$k];

            if(!$this->create($this->item)){
                return false;
            }

            //录入媒体表
            $add_res = $this->add();

            $res = $add_res && $res;
            $up_res[$k]['savepath'] = $this->item->link;

            //更新文章表
            D('article')->update_cover($this->item);
        }

        if(!$res){
            return false;
        }

        return $up_res;  
    }

    /**
     * @desc 上传文件
     * @return mix
     * @version 1 2014-12-2 RGray
     */
    public function upload_file()
    {
        //上传多媒体
        $config = $this->trans_upload_config();
        $upload = new \Think\Upload($config);
        $up_res = $upload->upload();

        //部分上传失败，清除文件
        $this->error = $upload->getError();
        if(!empty($this->error)) {
            $this->unlink_uploadfile($up_res);
            return false;
        }

        return $up_res;
    }

    /**
     * @desc 根据类型切换上传配置
     * @param string $type 多媒体类型
     * @return array
     * @version 1 2014-12-2 RGray
     */
    public function trans_upload_config($type = null)
    {
        $type = empty($type) ? $this->type : $type;

        //设置php上传限制
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('memory_limit', '256M');

        //上传配置项
        $config['maxSize'] = 10485760;
        $config['rootPath'] = '.'.UPLOAD_PATH;
        $config['savePath'] = './'.ARTICLE_PATH.$type.'/';
        $config['saveName'] = array('uniqid','');
        $config['exts'] = C('UPLOAD_EXT.'.$type);
        $config['autoSub'] = true;
        $config['subName'] = array('date','Ymd');

        $this->item->type = strtoupper($type);
        return $config;
    }

    /**
     * @desc 删除上传文件
     * @param array $list 文件里列表
     * @version 1 2014-12-2 RGray
     */
    public function unlink_uploadfile($list)
    {
        foreach ($list as $l) {

             if(!empty($l['savepath'])){
                $savepath = str_replace('./', '', $l['savepath']);
                $file = '.'.UPLOAD_PATH.$savepath.$l['savename'];
                @unlink($file);
             }
        }
    }
}