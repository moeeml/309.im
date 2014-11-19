<?php
namespace Home\Model;
use Think\Model;
class MediaModel extends Model {

    /**
     * @var array 字段合法性限制
     */
    protected $insertFields = array('art_id', 'comm_id', 'type', 'description', 'link', 'width', 'height');

    /**
     * @var array 数据过滤以及自动填充
     */
    protected $_auto = array(
            array('comm_id', 0, self::MODEL_INSERT),
            array('type', TEXT, self::MODEL_INSERT),
            array('status', NORMAL, self::MODEL_INSERT),
            array('create_time', 'time', self::MODEL_INSERT, 'function'),
        );

    /**
     * @var array 自动验证
     */
    protected $_validate = array(
            array('description', 'require', '{%article_content_empty}'),
        );

    /**
     * @var bool 批量验证
     */
    protected $patchValidate = true;

    public function __construct(){
        parent::__construct();
    }
}