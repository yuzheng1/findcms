<?php
namespace App\Http\Service;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Illuminate\Validation\Rule;
use App\Models\Qualification;

/**
 * 用户认证服务层
 * @author zx
 * @date 2018-12-29
 * @package App\Http\Service
 */
class QualifiedService extends CommonService {

    /**
     * 状态区分
     * @var array
     */
    const STATUS_ARR = ["未审核", "审核通过", "审核未通过"];

    /**
     * 表单页面控制
     * @author zx
     * @date 2018-12-29
     * @return Form
     */
    public function form()
    {
        $gird = Admin::form(Qualification::class, function(Form $form){
            $form->display("users.id", "用户ID");
            $form->display("users.nickname", "用户昵称");
            $form->text('realname', '真实姓名')->rules(
                $this->realname_rules(),
                $this->realname_rules_message()
            );
            $form->text('id_card', '身份证号码')->rules(
                $this->id_card_rules(),
                $this->id_card_rules_message()
            );
            $form->image("card_image")->uniqueName();
            $form->radio('status', 'Status')->options(self::STATUS_ARR)->rules(
                $this->status_rules(),
                $this->status_rules_message()
            );
            $form->switch('is_delete', 'Is delete')->states(parent::IS_DELETE_RULES_ARR)->rules(
                parent::is_delete_rules(),
                parent::IS_DELETE_MESSAGE
            );
            return $form;
        });

        return $gird;
    }

    /**
     * 列表页面控制
     * @param Grid $grid
     * @author zx
     * @date 2018-12-29
     * @return Grid
     */
    public function grid(Grid $grid)
    {
        $grid->id('Id');
        $grid->column('users.nickname', "认证人昵称");
        $grid->realname('真实姓名');
        $grid->id_card('身份证号码');
        $grid->card_image('认证图片')->image(config("filesystems.disks.qiniu.url"),50,50);
        $grid->status('Status')->display(function($status){
            return self::STATUS_ARR[$status];
        });
        $grid->is_delete('Is delete')->display(function($is_delete){
            return self::IS_DELETE_ARR[$is_delete];
        });
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');
        return $grid;
    }

    /**
     * 详情页面控制
     * @param Show $show
     * @author zx
     * @date 2018-12-29
     * @return Show
     */
    public function detail(Show $show)
    {
        $show->id('Id');
        $show->users('认证人信息', function($users){
            $users->id("用户ID");
            $users->nickname("用户昵称");
            return $users;
        });
        $show->realname('真是姓名');
        $show->id_card('身份证号码');
        $show->card_image('认证图片')->image();
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    private function realname_rules()
    {
        return "required|min:2";
    }

    private function realname_rules_message()
    {
        return [
            "required" => "请输入",
            "min" => "不可少于两个字符"
        ];
    }

    private function id_card_rules()
    {
        return [
            "required",
            "regex:/^([\d]{17}[xX\d]|[\d]{15})$/"
        ];
    }

    private function id_card_rules_message()
    {
        return [
            "required" => "请输入",
            "regex" => "格式有误"
        ];
    }

    private function status_rules()
    {
        return [
            "required",
            Rule::in([0,1,2])
        ];
    }

    private function status_rules_message()
    {
        return [
            "required" => "请选择",
            "in" => "范围有误"
        ];
    }

}