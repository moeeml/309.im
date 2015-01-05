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

    /**
     * @var CommentModel 评论模型
     */
    private $commentModel = NULL;    

	public function __construct()
	{
		parent::__construct();

        $this->articleModel = D('article');
        $this->mediaModel = D('media');
		$this->commentModel = D('comment');
	}

    /**
     * @desc 文章列表
     * @version 1 2014-11-11 RGray
     */
    public function article_list()
    {
        $page = I('param.page', 1, 'intval');
        $this->assign('page', $page + 1);

        $this->data = $this->articleModel->get_list();

    	$this->play();
    }

    /**
     * @desc 文章详细内容
     * @version 1 2014-11-11 RGray
     */
    public function article_detail()
    {
    	$art_id = I('param.art_id');

    	$res = $this->articleModel->get_detail($art_id);

        if($res){
            $this->data = $res;
        }else{
            $this->type = ERROR;
            $this->data = $this->articleModel->getError();
        }

        $this->play();
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
            $this->data = $this->articleModel->message;
        }else{
            $this->data = L('publish_article_success', array('insert_id'=>$act_id));
        }

        $this->play();
    }

    /**
     * @desc 发布文章页
     * @version 1 2014-12-12 RGray
     */
    public function show_publish_article()
    {
        $this->play();
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
            $this->data = $this->mediaModel->getError();
        }else{
            $this->data = $res;
        }

        $this->play();
    }

    /**
     * @desc 获取文章评论列表
     * @version 1 2014-12-17 RGray
     */
    public function replay_list()
    {
        $res = $this->commentModel->get_reply();

        if(!$res){
            $this->type = ERROR;
            $this->data = $this->commentModel->getError();
        }else{
            $this->data = $res;
        }

        $this->play();
    }

    /**
     * @desc 评论文章
     * @version 1 2014-12-16 RGray
     */
    public function reply_article()
    {
        $res = $this->commentModel->insert_comment();

        if(!$res){
            $this->type = ERROR;
            $this->data = $this->commentModel->getError();
        }else{
            $this->data = L('reply_success');
        }

        $this->play();
    }

    public function test()
    {
        $this->display();
    }
}