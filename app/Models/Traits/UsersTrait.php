<?php
namespace App\Models\Traits;

use App\Models\Qualification;
use App\Models\FindNotice;
use App\Models\Message;

/**
 * 模型Trait类
 * @author zx
 * @date 2018-12-23
 * @package App\Models\Trait
 */
Trait UsersTrait{

	public function qualification()
	{
		return $this->hasOne(Qualification::class, "user_id");
	}

	public function findnotice()
    {
        return $this->hasOne(FindNotice::class, "release_user");
    }

    public function message()
    {
        return $this->hasMany(Message::class, "uid");
    }

}