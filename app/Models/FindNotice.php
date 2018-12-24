<?php
namespace App\Models;

class FindNotice extends Models{

    /**
     * 关联到模型的数据表
     * 默认规则是模型类名的复数作为与其对应的表名
     * @var  string
     * @author  朱旭
     */
    protected $table = 'find_notice';

    /**
     * 设置主键
     * @author  朱旭
     * @var string
     */
    public $primaryKey = 'id';
}