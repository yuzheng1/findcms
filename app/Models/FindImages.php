<?php
namespace App\Models;

use App\Models\Traits\FindImagesTrait;

class FindImages extends Models{
    use FindImagesTrait;
    /**
     * 关联到模型的数据表
     * 默认规则是模型类名的复数作为与其对应的表名
     * @var  string
     * @author  朱旭
     */
    protected $table = 'find_images';

    /**
     * 设置主键
     * @author  朱旭
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * 批量赋值白名单
     * @var array
     */
    protected $fillable = [ "url" , "index" , "out_id" , "cover" ];

    /**
     * 定义updated_at,用于忽略updated_at字段
     * @var null
     */
    const UPDATED_AT = null;
}