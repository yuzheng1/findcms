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
use Illuminate\Validation\Rule;

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
            ->header('首页')
            ->description('用户首页')
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
            ->description('用户详情')
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
            ->description('用户编辑')
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
            ->description('用户添加')
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
        $show->sex('性别')->using(['男', '女']);
        $show->email('邮箱');
        $show->member_type('类型')->using(['寻找者', '求助者']);
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

        $form->display('id','ID');
        $form->text('nickname','昵称')->rules(
            "required|min:2|max:10",
            [
                'required' => '昵称必须',
                'min' => '昵称不得小于两个字符',
                'max' => '昵称不得大于十个字符'
            ]
        );
        $form->number('mobile','手机号')->rules(
            [
                "regex:/^1[3456789]\d{9}$/",
                "required_without_all:email",
                "unique:users,mobile"
            ],
            [
                'required_without_all' => '手机号或邮箱必须',
                'regex' => '手机号码不合法',
                "unique" => "手机号码已存在"
            ]
        );
        $form->password("password", "密码")->rules(
            "required|digits_between:6,18|alpha_dash",
            [
                "required" => "请输入密码",
                "between" => "密码不少于6个字符且不超过18位字符",
                "alpha_dash" => "密码只能有数字字母下划线"
            ]
        );
        $form->text('realname','姓名')->rules(
            "min:2",
            [
                "min" => "姓名不得少于两个字符",
            ]
        );
        $form->text('id_card', '身份证号码')->rules(
            [
                "regex:/^([\d]{17}[xX\d]|[\d]{15})$/",
            ],
            [
                "regex" => '身份证号码格式错误'
            ]
        );
        $sexArr = [
            '男',
            '女'
        ];
        $form->select('sex','性别')->options($sexArr)->rules(
            [
                Rule::in([0,1])
            ],
            [
                "in" => '性别范围错误'
            ]
        );
        $form->text('email','邮箱')->rules(
            'email|required_without_all:mobile|unique:users,email',
            [
                "email" => '邮箱格式错误',
                "required_without_all" => '手机号码或邮箱必须',
                "unique" => '邮箱已存在'
            ]
        );
        $member_type_arr = [
            '寻找者',
            '求助者'
        ];
        $form->select('member_type', '类型')->options($member_type_arr)->rules(
            [
                "required",
                Rule::in([0,1])
            ],
            [
                "required" => "用户类型必须",
                "in" => "用户类型范围错误"
            ]
        );
        $is_delete_arr = [
            '启用',
            '禁用'
        ];
        $form->select("is_delete", "状态")->options($is_delete_arr)->rules([
            [
                "required",
                Rule::in([0,1])
            ],
            [
                "required" => "状态必须",
                "in" => "状态范围错误"
            ]
        ]);

        return $form;
    }
}
