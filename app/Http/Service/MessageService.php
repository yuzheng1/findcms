<?php
namespace App\Http\Service;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Validation\Rule;

/**
 * 消息管理服务层
 * @author zx
 * @date 2018-12-29
 * @package App\Http\Service
 */
class MessageService extends CommonService {

    /**
     * 消息状态
     * @var array
     */
    const STATUS_ARR = ["已发送","已查看"];

    /**
     * 表单页面控制
     * @param Form $form
     * @author zx
     * @date 2018-12-29
     * @return Form
     */
    public function form(Form $form)
    {
        $form->display("users.id","被发送人ID");
        $form->display("users.nickname", "被发送人昵称");
        $form->text('title', '标题')->rules(
            $this->title_rules(),
            $this->title_rules_message()
        );
        $form->textarea('content', '内容')->rules(
            $this->content_rules(),
            $this->content_rules_message()
        );
        $form->switch('status', 'Status')->states($this->status_arr())->default("off")->rules(
            $this->status_rules(),
            $this->status_rules_message()
        );
        $form->switch('is_delete', 'Is delete')->states(parent::IS_DELETE_RULES_ARR)->rules(
            parent::is_delete_rules(),
            parent::IS_DELETE_MESSAGE
        );

        return $form;
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
        $grid->id('ID');
        $grid->column('users.nickname', "被发送人昵称");
        $grid->column('users.id', "被发送人ID");
        $grid->column('title', "标题");
        $grid->status('Status')->display(function($status){
            return self::STATUS_ARR[$status];
        });
        $grid->is_delete('Is delete')->display(function($is_delete){
            return MessageService::IS_DELETE_ARR[$is_delete];
        });
        $grid->created_at('Created at');
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
        $show->users('被发送人信息',function($users){
            $users->id("ID");
            $users->nickname("昵称");
            return $users;
        });
        $show->title('标题');
        $show->content('内容');
        $show->created_at('Created at');
        return $show;
    }

    private function title_rules()
    {
        return "required";
    }

    private function title_rules_message()
    {
        return [
            "required" => "请输入标题"
        ];
    }

    private function content_rules()
    {
        return "required";
    }

    private function content_rules_message()
    {
        return [
            "required" => "请输入标题"
        ];
    }

    private function status_arr()
    {
        return [
            'on' => ['value' => 1, 'text' => '已查看', 'color'=>'danger'],
            'off' => ['value' => 0, 'text' => '已发送', 'color' => 'success']
        ];
    }

    private function status_rules()
    {
        return [
            "required",
            Rule::in(['on','off'])
        ];
    }

    private function status_rules_message()
    {
        return [
            "required" => "请选择发送状态",
            "in" => "发送状态范围错误"
        ];
    }

}