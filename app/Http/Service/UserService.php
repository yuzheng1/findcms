<?php
namespace App\Http\Service;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Validation\Rule;

/**
 * 用户管理服务层
 * @author zx
 * @date 2018-12-29
 * @package App\Http\Service
 */
class UserService extends CommonService {

    /**
     * 性别区分
     * @var array
     */
    const SEX_ARR = ['男', '女'];

    /**
     * 用户类型
     * @var array
     */
    const MEMBER_TYPE_ARR = ['寻找者', '求助者'];

    /**
     * 表单页面控制
     * @param Form $form
     * @author zx
     * @date 2018-12-29
     * @return Form
     */
    public function form(Form $form)
    {
        $id = request()->route()->parameters()['user']; //获取当前路由参数
        $form->display('id','ID');
        $form->text('nickname','昵称')->rules(
            $this->nickname_rules(),
            $this->nickname_rules_message()
        );
        $form->number('mobile','手机号')->rules(
            $this->mobile_rules($id),
            $this->mobile_rules_message()
        );
        $form->password("password", "密码")->rules(
            $this->password_rules($id),
            $this->password_rules_message($id)
        );
        $form->text('realname','姓名')->rules(
            $this->realname_rules(),
            $this->realname_rules_message()
        );
        $form->text('id_card', '身份证号码')->rules(
            $this->id_card_rules(),
            $this->id_card_rules_message()
        );
        $form->select('sex','性别')->options(self::SEX_ARR)->rules(
            $this->sex_rules(),
            $this->sex_rules_message()
        );
        $form->text('email','邮箱')->rules(
            $this->email_rules($id),
            $this->email_rules_message()
        );
        $form->select('member_type', '类型')->options(self::MEMBER_TYPE_ARR)->rules(
            $this->member_type_rules(),
            $this->member_type_rules_message()
        );
        $form->switch("is_delete", "状态")->states(parent::IS_DELETE_RULES_ARR)->rules([
            parent::is_delete_rules(),
            parent::IS_DELETE_MESSAGE
        ]);
        $form->saving(function(Form $form){
            if($form->password && $form->model()->password != bcrypt($form->password)){
                $form->password = bcrypt($form->password);
            } else if (!$form->password){
                $form->password = $form->model()->password;
            }
        });
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
        $grid->nickname('昵称');
        $grid->mobile('手机号');
        $grid->realname('姓名');
        $grid->id_card('身份证号码');
        $grid->sex('性别')->display(function ($sex){
            return self::SEX_ARR[$sex] ;
        });
        $grid->email("邮箱");
        $grid->member_type("类型")->display( function ($member_type) {
            return self::MEMBER_TYPE_ARR[$member_type] ;
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
        $show->id('ID');
        $show->nickname('昵称');
        $show->mobile('手机号');
        $show->realname('姓名');
        $show->id_card('身份证号码');
        $show->sex('性别')->using(self::SEX_ARR);
        $show->email('邮箱');
        $show->member_type('类型')->using(self::MEMBER_TYPE_ARR);
        $show->created_at('Created at');
        $show->updated_at('Updated at');
        return $show;
    }

    private function nickname_rules()
    {
        return "required|min:2|max:10";
    }

    private function nickname_rules_message()
    {
        return [
            'required' => '昵称必须',
            'min' => '昵称不得小于两个字符',
            'max' => '昵称不得大于十个字符'
        ];
    }

    private function mobile_rules($id='')
    {
        return [
            "regex:/^1[3456789]\d{9}$/",
            "required_without_all:email",
            Rule::unique("users", 'mobile')->ignore($id)
        ];
    }

    private function mobile_rules_message()
    {
        return [
            'required_without_all' => '手机号或邮箱必须',
            'regex' => '手机号码不合法',
            "unique" => "手机号码已存在"
        ];
    }

    private function password_rules($id='')
    {
        if($id){
            return "nullable|between:6,18|alpha_dash";
        }
        return "required|between:6,18|alpha_dash";
    }

    private function password_rules_message($id='')
    {
        $rulesArr = [
            "between" => "密码不少于6个字符且不超过18位字符",
            "alpha_dash" => "密码只能有数字字母下划线"
        ];
        if($id) return $rulesArr;
        $rulesArr['required'] = "请输入密码";
        return $rulesArr;
    }

    private function realname_rules()
    {
        return "min:2";
    }

    private function realname_rules_message()
    {
        return [
            "min" => "姓名不得少于两个字符",
        ];
    }

    private function id_card_rules()
    {
        return [
            "regex:/^([\d]{17}[xX\d]|[\d]{15})$/",
        ];
    }

    private function id_card_rules_message()
    {
        return [
            "regex" => '身份证号码格式错误'
        ];
    }

    private function sex_rules()
    {
        return [
            Rule::in([0,1])
        ];
    }

    private function sex_rules_message()
    {
        return [
            "in" => '性别范围错误'
        ];
    }

    private function email_rules($id='')
    {
        return [
            "email",
            "required_without_all:mobile",
            Rule::unique("users",'email')->ignore($id)
        ];
    }

    private function email_rules_message()
    {
        return [
            "email" => '邮箱格式错误',
            "required_without_all" => '手机号码或邮箱必须',
            "unique" => '邮箱已存在'
        ];
    }

    private function member_type_rules()
    {
        return [
            "required",
            Rule::in([0,1])
        ];
    }

    private function member_type_rules_message()
    {
        return [
            "required" => "用户类型必须",
            "in" => "用户类型范围错误"
        ];
    }

}