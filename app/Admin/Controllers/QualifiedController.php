<?php

namespace App\Admin\Controllers;

use App\Models\Qualification;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Illuminate\Validation\Rule;

class QualifiedController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Qualification);

        $grid->id('Id');
        $grid->column('users.nickname', "认证人昵称");
        $grid->realname('真实姓名');
        $grid->id_card('身份证号码');
        $grid->card_image('认证图片')->image(config("filesystems.disks.qiniu.url"),50,50);
        $grid->status('Status')->display(function($status){
            $status_arr = ["待审核", "审核通过", "审核失败"];
            return $status_arr[$status]??"审核状态有误";
        });
        $grid->is_delete('Is delete')->display(function($is_delete){
            return $is_delete==1?"已删除":"未删除";
        });
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Qualification::findOrFail($id));

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

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $gird = Admin::form(Qualification::class, function(Form $form){
            $form->display("users.id", "用户ID");
            $form->display("users.nickname", "用户昵称");
            $form->text('realname', '真实姓名')->rules(
                "required|min:2",
                [
                    "required" => "请输入",
                    "min" => "不可少于两个字符"
                ]
            );
            $form->text('id_card', '身份证号码')->rules(
                [
                    "required",
                    "regex:/^([\d]{17}[xX\d]|[\d]{15})$/"
                ],
                [
                    "required" => "请输入",
                    "regex" => "格式有误"
                ]
            );
            $form->image("card_image")->uniqueName();
            $status_arr = [
                "未审核",
                "审核通过",
                "审核未通过"
            ];
            $form->radio('status', 'Status')->options($status_arr)->rules(
                [
                    "required",
                    Rule::in([0,1,2])
                ],
                [
                    "required" => "请选择",
                    "in" => "范围有误"
                ]
            );
            $is_delete_arr = [
                'on' => ['value' => 1, 'text' => '删除', 'color'=>'danger'],
                'off' => ['value' => 0, 'text' => '不删除', 'color' => 'success']
            ];
            $form->switch('is_delete', 'Is delete')->states($is_delete_arr)->rules(
                [
                    "required",
                    Rule::in(["on","off"])
                ],
                [
                    "required" => "请选择",
                    "in" => "范围错误"
                ]
            );
            return $form;
        });

        return $gird;
    }
}
