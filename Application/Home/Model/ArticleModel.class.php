<?php
namespace Home\Model;
use Think\Model;
class ArticleModel extends Model {

    /**
     * @var array 字段合法性限制
     */
    protected $insertFields = array('cate_id', 'tag', 'type', 'keyword', 'summary', 'title');

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
            array('title', 'require', '{%article_title_empty}'),
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
    	$media = $this->mediaModel->where(array('art_id'=>$id))->select();

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
     * @version 1 2014-11-18 RGray
     */
    public function insert_article()
    {
        //创建数据
        if(!$this->create()){
            $article_error = $this->getError();
        }

        if(!$this->mediaModel->create()){
            $media_error = $this->mediaModel->getError();
        }

        $this->message = array_filter(array_merge((array)$article_error, (array)$media_error));

        if(!empty($this->message)){    
            return false;
        }

        //插入文章信息
        $this->user_id = 1;
        $res = $this->add();

        if(!$res){
            return false;
        }

        //插入文章媒体
        if(!$this->mediaModel->add()){
            return false;
        }

        return $res;
    }

    //Test
    public function test()
    {
        $this->display();
    }
}