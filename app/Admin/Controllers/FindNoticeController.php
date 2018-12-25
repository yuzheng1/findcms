<?php

namespace App\Admin\Controllers;

use App\Models\FindNotice;
use App\Models\Users;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;

class FindNoticeController extends Controller
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
            ->header('列表')
            ->description('通知列表')
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
            ->header('详情')
            ->description('通知详情')
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
            ->header('编辑')
            ->description('通知编辑')
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
            ->header('添加')
            ->description('通知添加')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new FindNotice);

        $grid->id('ID');
        $grid->release_user('Release user');
        $grid->province('省份');
        $grid->city('城市');
        $grid->column('title','标题'); //title方法冲突
        $grid->name('姓名');
        $grid->sex('性别')->display(function($sex){
            return $sex == 1?'女':'男';
        });
        $grid->age('年龄')->display(function ($age){
            return $age."岁";
        });
        $grid->contact_name('联系人姓名');
        $grid->contact_mobile('联系人电话');
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
        $show = new Show(FindNotice::findOrFail($id));

        $show->id('Id');
        $show->release_user('Release user');
        $show->province('省份');
        $show->city('城市');
        $show->title('标题');
        $show->name('姓名');
        $show->sex('性别')->using([
            "男",
            "女"
        ]);
        $show->age('年龄');
        $show->desc('详情');
        $show->contact_name('联系人姓名');
        $show->contact_mobile('联系电话');
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
        $grid = Admin::form(FindNotice::class, function(Form $form){
            $form->display("users.nickname", "昵称");
            $form->text('province', '省份');
            $form->text('city', '城市');
            $form->text('title', '标题');
            $form->text('name', '姓名');
            $sexArr = [
                "男",
                "女"
            ];
            $form->select('sex', '性别')->options($sexArr);
            $form->number('age', '年龄');
            $form->textarea('desc', '详情');
            $form->text('contact_name', '联系人姓名');
            $form->text('contact_mobile', '联系人手机号码');
            $statusArr = [
                "审核中",
                "审核通过",
                "审核不通过",
            ];
            $form->radio('status', 'Status')->options($statusArr);
            $is_delete_arr = [
                'on' => ['value' => 1, 'text' => '删除', 'color'=>'danger'],
                'off' => ['value' => 0, 'text' => '不删除', 'color' => 'success']
            ];
            $form->switch('is_delete', 'Is delete')->states($is_delete_arr);
        });
        dd($grid);
        return $grid;
    }
}
