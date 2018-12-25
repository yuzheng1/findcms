<?php
namespace App\Models;

class Area extends Models{

    /**
     * 关联到模型的数据表
     * 默认规则是模型类名的复数作为与其对应的表名
     * @var  string
     * @author  朱旭
     */
    protected $table = 'area';

    /**
     * 设置主键
     * @author  朱旭
     * @var string
     */
    public $primaryKey = 'id';
}