<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	public $article = NULL;
	public $media = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->article = D('Article');
		$this->media = D('Media');
	}

    public function index(){
        $this->display();
    }

    /**
     * @desc 文章列表
     * @version 1 2014-11-11 RGray
     */
    public function article_list()
    {
    	$article_list = $this->article->get_list();
    	
    	$this->ajaxReturn($article_list);
    }

    /**
     * @desc 文章详细内容
     * @version 1 2014-11-11 RGray
     */
    public function article_detail()
    {
    	$art_id = I('art_id');

    	$article = $this->article->get_detail($art_id);

    	$this->ajaxReturn($article);
    }

    /**
     * @desc 发布文章
     * @version 1 2014-11-11 RGray
     */
    public function publish_article()
    {
        $res = $this->article->insert_article();

        echo $this->article->getError();

        $this->ajaxReturn($res);
    }
}