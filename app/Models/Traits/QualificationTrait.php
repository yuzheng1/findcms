<?php
namespace App\Models\Traits;

use App\Models\Users;

/**
 * 认证Trait类
 * @author zx
 * @date 2018-12-23
 * @package App\Models\Trait
 */
Trait QualificationTrait{

    public function users()
    {
        return $this->belongsTo(Users::class, "user_id");
    }

}