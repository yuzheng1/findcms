<?php
namespace App\Models\Trait;

use App\Models\Qualification;

/**
 * 模型Trait类
 * @author zx
 * @date 2018-12-23
 * @package App\Models\Trait
 */
class UsersTrait{

	public function qualification()
	{
		return $this->belongsToMany(Qualification::class);
	}

}