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

    public function get_article_list()
    {
    	$article_list = $this->article->select();
    	
    	$this->ajaxReturn($article_list);
    }

    public function get_article_detail()
    {
    	$art_id = I('art_id');
    	//$condition['art_id'] = $art_id; 
    	$article = $this->article->where(array('id'=>$art_id))->find();
    	$article['content'] = $this->media->where(array('art_id'=>$art_id,'type'=>1))->getField('description');

    	$this->ajaxReturn($article);
    }
}