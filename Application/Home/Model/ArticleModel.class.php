<?php
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

    public function __construct()
    {
        parent::__construct();

        $this->mediaModel = D('Media');
    }

    /**
     * @desc 获取文章列表
     * @param int $id 文章ID
     * @return array
     * @version 1 2014-11-11 RGray
     */
    public function get_list()
    {
    	return $this->where(array('status'=>NORMAL))->select();
    }

    /**
     * @desc 获取文章的详细内容
     * @param int $id 文章ID
     * @return array
     * @version 1 2014-11-11 RGray
     */
    public function get_detail($id)
    {
    	$info = $this->where(array('id'=>$id))->find();

        $mfield = 'id,description,link,type,status,limit,width,height,size';
    	$media = $this->mediaModel->where(array('art_id'=>$id))->field($mfield)->select();

    	foreach ($media as $m) {

    		switch ($m['type']) {
    			case TEXT:
    				$info['content'] = $m['description'];
    				break;

    			case IMAGE:
    				$info['image'][] = $m;
    				break;

    			case MUSIC:
    				$info['music'][] = $m;
    				break;

    			case VIDEO:
    				$info['video'][] = $m;
    				break;

    			case ANNEX:
    				$info['annex'][] = $m;
    				break;

    			case CODE:
    				$info['code'][] = $m;	
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


    //Test
    public function test()
    {
        $this->display();
    }
}