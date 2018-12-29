<?php
namespace App\Models;

use App\Models\Traits\MessageTrait;

class Message extends Models
{
    use MessageTrait;
	/**
	 * 关联到模型的数据表
	 * 默认规则是模型类名的复数作为与其对应的表名
	 * @var  string
	 * @author  朱旭 
	 */
	protected $table = 'message';

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
	protected $fillable = ["title", "content"];

    /**
     * 定义updated_at,用于忽略updated_at字段
     * @var null
     */
    const UPDATED_AT = null;

}