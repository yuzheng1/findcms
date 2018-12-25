<?php
namespace App\Models\Traits;

use App\Models\Users;

/**
 * 模型需求Trait类
 * @author zx
 * @date 2018-12-23
 * @package App\Models\Trait
 */
Trait FindNoticeTrait{

	public function users()
	{
		return $this->belongsTo(Users::class, "release_user");
	}

}