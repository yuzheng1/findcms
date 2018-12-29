<?php
namespace App\Http\Service;

use Illuminate\Validation\Rule;

/**
 * 公共逻辑服务层
 * @author zx
 * @date 2018-12-29
 * @package App\Http\Service
 */
class CommonService{

    /**
     * 删除开关配置
     * @var zx
     */
    const IS_DELETE_RULES_ARR = [
        'on' => ['value' => 1, 'text' => '已删除', 'color'=>'danger'],
        'off' => ['value' => 0, 'text' => '未删除', 'color' => 'success']
    ];

    /**
     * 删除区分
     * @var array
     */
    const IS_DELETE_ARR = ["未删除", "已删除"];

    /**
     * 表单验证提示信息
     * @var array
     */
    const IS_DELETE_MESSAGE = [
        "required" => "请选择删除状态",
        "in" => "删除状态范围错误"
    ];

    /**
     * 表单验证
     * @author zx
     * @date 2018-12-29
     * @return array
     */
    public static function is_delete_rules():array
    {
        return [
            "required",
            Rule::in(['on','off'])
        ];
    }

}