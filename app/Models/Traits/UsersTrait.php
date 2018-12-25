<?php
namespace App\Models\Traits;

use App\Models\Qualification;
use App\Models\FindNotice;

/**
 * 模型Trait类
 * @author zx
 * @date 2018-12-23
 * @package App\Models\Trait
 */
Trait UsersTrait{

	public function qualification()
	{
		return $this->belongsToMany(Qualification::class);
	}

	public function findnotice()
    {
        return $this->hasOne(FindNotice::class);
    }

}