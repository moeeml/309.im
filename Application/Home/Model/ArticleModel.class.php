<?php
/**
 * @desc 文章模型
*/
namespace Home\Model;
use Think\Model;
class ArticleModel extends Model {

    /**
     * @var array 数据过滤以及自动填充
     */
    protected $_auto = array(
            array('cate_id', 0, self::MODEL_INSERT),
            array('type', TEXT, self::MODEL_INSERT),
            array('status', NORMAL, self::MODEL_INSERT),
            array('create_time', 'time', self::MODEL_INSERT, 'function'),
            array('update_time', 'time', self::MODEL_UPDATE, 'function'),
        );

    /**
     * @var array 自动验证
     */
    protected $_validate = array(
            array('title', 'require', '{%article_title_empty}', self::MUST_VALIDATE),
        );

    /**
     * @var bool 批量验证
     */
    protected $patchValidate = true;

    /**
     * @var mix message
     */
    public $message;

    /**
     * @var MediaModel Media模型对象
     */
    public $mediaModel = NULL;

    /**
     * @var UserModel User模型对象
     */
    public $userModel = NULL;

    public function __construct()
    {
        parent::__construct();

        $this->mediaModel = D('Media');
        $this->userModel = D('User');
    }

    /**
     * @desc 获取文章列表
     * @param int $id 文章ID
     * @return array
     * @version 2 2014-12-10 RGray
     */
    public function get_list()
    {
        $page = I('param.page', 1, 'intval');
        $uid = I('param.uid');
        $sdate = I('param.start_date');
        $edate = I('param.end_date');
        $type = I('param.type');

        //分页
        $limit = PAGESIZE12;
        $offset = ($page - 1) * $limit;
        $this->limit($offset, $limit);

        // //条件过滤
        $where['status'] = NORMAL;

        if(!empty($uid)){
            $where['user_id'] = $uid;
        }

        if(!empty($type)){
             $where['type'] = strtoupper($type);
        }

        if(!empty($sdate)){
            $where['create_time'] = array('EGT', strtotime($sdate));
        }

        if(!empty($edate)){
            $where['create_time'] = array('ELT', strtotime($edate));
        }

        //查询
        return $this->alias('a')
                    ->field('a.*, u.real_name, u.avatar')
                    ->join('LEFT JOIN user AS u ON u.id = a.user_id')
                    ->where($where)->select();
    }

    /**
     * @desc 获取文章的详细内容
     * @param int $id 文章ID
     * @return array
     * @version 2 2014-12-11 RGray
     */
    public function get_detail($id)
    {
    	$info = $this->alias('a')
                     ->field('a.*, u.real_name, u.avatar, u.honor, u.tag as utag, u.sign, u.description as user_desc')
                     ->join('LEFT JOIN user AS u ON u.id = a.user_id')
                     ->where(array('a.id'=>$id))->find();

        //拆开标签                     
        $tag = explode(',', $info['tag']);unset($info['tag']);$info['art_tag'] = $tag;
        $utag = explode(',', $info['utag']);unset($info['utag']);$info['user_tag'] = $utag;                     

        //查找对应媒体
        $mfield = 'id,description,link,type,status,limit,width,height,size';
    	$media = $this->mediaModel->where(array('art_id'=>$id))->field($mfield)->select();

    	foreach ($media as $m) {

    		switch ($m['type']) {
    			case TEXT:
    				$info['content'] = $m['description'];
    				break;

    			default:
    				$info['media'][] = $m;
    				break;
    		}
    	}

    	return $info;
    }

    /**
     * @desc 写入文章内容
     * @return mix
     * @version 3 2014-12-1 RGray
     */
    public function insert_article()
    {
        //创建数据
        $ares = $this->create();
        $bres = $this->mediaModel->create();

        if(!$ares || !$bres){
            $article_error = $this->getError();
            $media_error = $this->mediaModel->getError();
            $this->message = array_filter(array_merge((array)$article_error, (array)$media_error));
            return false;
        }

        //插入文章信息
        $this->user_id = 1;
        $art_id = $this->add();

        if(!$art_id){
            return false;
        }

        //插入文章媒体信息
        $this->mediaModel->art_id = $art_id;

        if(!$this->mediaModel->add()){
            return false;
        }

        return $art_id;
    }

    /**
     * @desc 更新文章封面
     * @version 1 2014-12-8 RGray
     */
    public function update_cover($item)
    {
        if(!in_array($item->type, array(TEXT, IMAGE, CODE, MUSIC, VIDEO))){
            return false;
        }

        $type = strtolower($item->type);
        $cover = $this->where(array('id'=>$item->art_id))->getField($type);

        if(!empty($cover)){
            return false;
        }

        $data = NULL;
        $data[$type] = $item->link;
        $data['media_width'] = $item->width;
        $data['media_height'] = $item->height;
        $this->where(array('id'=>$item->art_id))->save($data);
    }


    //Test
    public function test()
    {
        $this->display();
    }
}