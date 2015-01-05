<?php
/**
 * @desc 首页
*/
namespace Home\Controller;
use Common\Controller\iController;
class IndexController extends iController
{

	public function __construct()
	{
		parent::__construct();
	}

    public function index()
    {
        $this->display();
    }

}