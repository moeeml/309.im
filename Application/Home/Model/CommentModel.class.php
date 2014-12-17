<?php
/**
 * @desc 评论模型
*/
namespace Home\Model;
use Common\Model\iModel;
class CommentModel extends iModel 
{

    /**
     * @var array 数据过滤以及自动填充
     */
    protected $_auto = array(
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
     * @var MediaModel 媒体模型
     */
    public $mediaModel;

    public function __construct()
    {
        parent::__construct();

        $this->mediaModel = D('media');
    }

    /**
     * @desc 插入评论
     * @return mix 
     * @version 1 2014-12-16 RGray
     */
    public function insert_comment()
    {
        $art_id = I('post.art_id');

        if(!$this->check_param($art_id)){
            return false;
        }

        if(!$this->create()){
            return false;
        }

        //组装索引链
        if($this->parent_id > 0 && $this->level > 0){
            $parent_path = $this->where(array('id'=>$this->parent_id))->getField('path');
            $this->path = $parent_path.'-' .$this->parent_id; 
        }

        $this->user_id = D('user')->get_uid();
        $comm_id = $this->add();

        if(!$comm_id){
            return false;
        }

        //插入评论内容
        if(!$this->mediaModel->create()){
            $this->error = $this->mediaModel->getError();
            return false;
        }

        $this->mediaModel->comm_id = $comm_id;
        $this->mediaModel->art_id = $art_id;

        $this->mediaModel->type = TEXT;
        $replycont_id = $this->mediaModel->add();
        if(!$replycont_id){
            $this->error = $this->mediaModel->getError();
            return false;
        }

        //媒体上传
        $this->mediaModel->item->comm_id = $comm_id;
        $this->mediaModel->item->art_id = $art_id;
        $up_res = $this->mediaModel->is_upload();

        return $comm_id;
    }

    /**
     * @desc 获取评论
     * @return mix 
     * @version 1 2014-12-17 RGray
     */
    public function get_reply()
    {
        $art_id = I('param.art_id');
        $page = I('param.page', 1);

        if(!$this->check_param($art_id)){
            return false;
        }

        $data = $this->field('c.*, m.*, u.*, c.create_time as create_time, u.description as user_desc, m.description as content')
                     ->alias('c')
                     ->join('LEFT JOIN media AS m ON c.id = m.comm_id')
                     ->join('LEFT JOIN user AS u ON c.user_id = u.id')
                     ->where(array('c.art_id'=>$art_id, 'c.status'=>NORMAL, 'm.type'=>TEXT))
                     ->order('path, c.create_time')
                     ->select();                   

        foreach ($data as $d) {
            $item[] = array(
                    'comm_id'=>$d['comm_id'],
                    'name'=>$d['name'],
                    'real_name'=>$d['real_name'],
                    'avatar'=>$d['avatar'],
                    'honor'=>$d['honor'],
                    'tag'=>$d['tag'],
                    'sign'=>$d['sign'],
                    'user_desc'=>$d['user_desc'],
                    'content'=>$d['content'],
                    'level'=>$d['level'],
                    'create_time'=>$d['create_time'],
                );
        }

       return $item;                   
    }

}