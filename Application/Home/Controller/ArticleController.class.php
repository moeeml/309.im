<?php
/**
 * @desc 博文模块
*/
namespace Home\Controller;
use Common\Controller\iController;
class ArticleController extends iController
{
    /**
     * @var ArticleModel 文章模型
     */
    private $articleModel = NULL;
    
    /**
     * @var MediaModel 媒体模型
     */
    private $mediaModel = NULL;

	public function __construct()
	{
		parent::__construct();
        $this->articleModel = D('Article');
		$this->mediaModel = D('Media');
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
    	$this->data = $this->articleModel->get_list();
    	
    	$this->json_back();
    }

    /**
     * @desc 文章详细内容
     * @version 1 2014-11-11 RGray
     */
    public function article_detail()
    {
    	$art_id = I('post.art_id');
    	$this->data = $this->articleModel->get_detail($art_id);

        $this->json_back();
    }

    /**
     * @desc 发布文章
     * @version 1 2014-11-11 RGray
     */
    public function publish_article()
    {
        $act_id = $this->articleModel->insert_article();

        if(!$act_id){
            $this->type = ERROR;
            $this->message = $this->articleModel->message;
        }else{
            $this->data = L('publish_articleModel_success', array('insert_id'=>$act_id));
        }

        $this->json_back();
    }


    /**
     * @desc 上传多媒体
     * @version 1 2014-12-1 RGray
     */
    public function upload_media()
    {
        $res = $this->mediaModel->is_upload();
        
        if(!$res){
            $this->type = ERROR;
            $this->message = $this->mediaModel->getError();
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