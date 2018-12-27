<?php
namespace App\Models;

use App\Models\Traits\UsersTrait;

class Users extends Models
{
	use UsersTrait;

	/**
	 * 关联到模型的数据表
	 * 默认规则是模型类名的复数作为与其对应的表名
	 * @var  string
	 * @author  朱旭 
	 */
	protected $table = 'users';

	/**
	 * 设置主键
	 * @author  朱旭
	 * @var string
	 */
	public $primaryKey = 'id';

	/**
	 * 表名模型是否应该被打上时间戳
	 * @var  bool
	 * @author 朱旭
	 */
	// public $timestamps = false;

	/**
	 * 模型日期列的存储格式
	 * @author  朱旭
	 * @var  string
	 */
	// protected $dateFormat = 'U';
	
	/**
	 * 避免转换时间戳为时间字符串
	 * @param  dateTime $value int
	 * @return dateTime     int
	 */
//	public function fromDateTime($value){
//		return strtotime(parent::fromDateTime($value));
//	}

	//自定义用于存储时间戳的字段名称,可以在模型中设置CREATED_AT 和 UPDATED_AT 常量
	//const CREATED_AT = 'regtime';
	//const UPDATED_AT = '';
	
	/**
	 * 从数据库获取的时间为时间戳格式
	 * @return string
	 */
//	public function getDateFormat()
//	{
//		return 'U';
//	}

	/**
	 * 批量赋值黑名单
	 * @var array
	 */
	protected $guarded = [];
	
	/**
	 * 批量赋值白名单
	 * @var array
	 */
	protected $fillable = [];

}