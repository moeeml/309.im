<?php
namespace Home\Controller;
use Common\Controller\iController;
class IndexController extends iController {
	public $article = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->article = D('Article');
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
        if(!$this->article->insert_article()){
            $this->type = ERROR;
            $this->message = $this->article->message;
        }else{
            $this->data = L('publish_article_success', array('insert_id'=>$res));
        }

        $this->json_back();
    }

    public function test()
    {
        $this->display();
    }
}