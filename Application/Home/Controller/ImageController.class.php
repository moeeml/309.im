<?php
/**
 * @desc 图片模块
*/
namespace Home\Controller;
use Common\Controller\iController;
class ImageController extends iController
{

	/**
	 * @var 图片宽度
	*/
	public $width;

	/**
	 * @var 图片路径
	*/
	public $path;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @desc 生成指定宽度的缩略图
	 * @return string
	 * @version 1 2014-12-04 RGray
	*/
	public function resize_image()
	{
		$width = $this->width;
		$file = $this->path;

		//组装目标图片路径
		$thumb_path = $_SERVER['DOCUMENT_ROOT'].dirname($file).'/'.$width;
		$thumb_file = $thumb_path.'/'.basename($file);

		//文件已存在，返回图片路径
		if(file_exists($thumb_file)){
			$this->savepath = $thumb_file;
			return true;
		}

		$image = new \Think\Image();
		$image->open('.'.$file);

		//计算缩略图大小
		$size = $image->size();
		$thumb_width = $width;
		$thumb_height = $width / $size[0] * $size[1];

		//创建缩略图
		if(!is_dir($thumb_path)){
			@mkdir($thumb_path);
		}

		$image->thumb($thumb_width, $thumb_height)->save($thumb_file);
		$this->savepath = $thumb_file;
		return true;
	}

	/**
	 * @desc 获取指定宽度的缩略图
	 * @version 1 2014-12-04 RGray
	*/
	public function get_image()
	{
		$uri = $_SERVER['REQUEST_URI'];
		list($this->path, $this->width) = explode('?w=', $uri);
		$this->resize_image();

		header('Content-type:image/jpeg');
		echo file_get_contents($this->savepath);
	}
}