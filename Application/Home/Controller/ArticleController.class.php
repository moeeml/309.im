<?php
namespace Home\Controller;
use Common\Controller\iController;
class ArticleController extends iController {
	public $article = NULL;
    public $media = NULL;

	public function __construct()
	{
		parent::__construct();
        $this->article = D('Article');
		$this->media = D('Media');
	}

    public function index()
    {
        $this->display();
    }

    /**
     * @desc 文章列表
     * @version 1 2014-11-11 RGray
     */
    public function article_list()
    {
    	$this->data = $this->article->get_list();
    	
    	$this->json_back();
    }

    /**
     * @desc 文章详细内容
     * @version 1 2014-11-11 RGray
     */
    public function article_detail()
    {
    	$art_id = I('post.art_id');
    	$this->data = $this->article->get_detail($art_id);

        $this->json_back();
    }

    /**
     * @desc 发布文章
     * @version 1 2014-11-11 RGray
     */
    public function publish_article()
    {
        $act_id = $this->article->insert_article();

        if(!$act_id){
            $this->type = ERROR;
            $this->message = $this->article->message;
        }else{
            $this->data = L('publish_article_success', array('insert_id'=>$act_id));
        }

        $this->json_back();
    }


    /**
     * @desc 上传多媒体
     * @version 1 2014-12-1 RGray
     */
    public function upload_media()
    {
        $res = $this->media->is_upload();
        
        if(!$res){
            $this->type = ERROR;
            $this->message = $this->media->getError();
        }else{
            $this->data = $res;
        }

        $this->json_back();
    }

    public function test()
    {
        $this->display();
    }
}