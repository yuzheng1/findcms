<?php

namespace App\Admin\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Models\Users;

class UserController extends Controller
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
        $grid = new Grid(new Users);

        $grid->id('ID');
        $grid->nickname('昵称');
        $grid->mobile('手机号');
        $grid->realname('姓名');
        $grid->id_card('身份证号码');
        $grid->sex('性别')->display(function ($sex){
            return $sex == 1? '女' : '男' ;
        });
        $grid->email("邮箱");
        $grid->member_type("类型")->display( function ($member_type) {
            return $member_type == 1? '求助者' : '寻找者' ;
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
        $show = new Show(Users::findOrFail($id));

        $show->id('ID');
        $show->nickname('昵称');
        $show->mobile('手机号');
        $show->realname('姓名');
        $show->id_card('身份证号码');
        $show->sex('性别')->display(function($sex){
            return $sex == 1? '女' : '男' ;
        });
        $show->email('邮箱');
        $show->member_type('类型')->display(function($member_type){
            return $member_type == 1? '求助者' : '寻找者' ;
        });
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
        $form = new Form(new User);

        $form->id('ID');
        $form->nickname('昵称');
        $form->mobile('手机号');
        $form->realname('姓名');
        $form->id_card('身份证号码');
        $form->sex('性别');
        $form->email('邮箱');
        $form->member_type('类型');
        $form->text('remember_token', 'Remember token');

        return $form;
    }
}
