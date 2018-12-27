<?php
namespace App\Models\Traits;

use App\Models\FindNotice;

/**
 * 模型需求Trait类
 * @author zx
 * @date 2018-12-23
 * @package App\Models\Trait
 */
Trait FindImagesTrait{

	public function findnotice()
	{
		return $this->belongsTo(FindNotice::class, "out_id");
	}

}