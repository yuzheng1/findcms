<?php
namespace App\Http\Service;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\NestedForm;
use Illuminate\Validation\Rule;
use App\Models\FindNotice;
use App\Models\Area;

class FindNoticeService extends CommonService {

    /**
     * 封面类型
     * @var array
     */
    const COVER_ARR = [
        'on' => ['value' => 1, 'text' => '封面', 'color'=>'danger'],
        'off' => ['value' => 0, 'text' => '非封面', 'color' => 'success']
    ];

    /**
     * 性别区分
     * @var array
     */
    const SEX_ARR = ["男", '女'];

    /**
     * 审核状态
     * @var array
     */
    const STATUS_ARR = ["审核中", "审核通过", "审核不通过"];

    /**
     * 表单页面控制
     * @author zx
     * @date 2018-12-29
     * @return Form
     */
    public function form()
    {
        $grid = Admin::form(FindNotice::class, function(Form $form){
            $model = new Area();
            $form->display("users.id", "发布人ID");
            $form->display("users.nickname", "发布人昵称");
            $form->hasMany("findimages", function (NestedForm $nestedForm){
                $nestedForm->image("url",'幻灯片')->uniqueName();
                $nestedForm->switch("cover", "是否为封面")->options(self::COVER_ARR)->rules(
                    $this->findimages_cover_rules(),
                    $this->findimages_cover_rules_message()
                );
                $nestedForm->number("index", "排序")->default(1)->rules(
                    $this->findimages_index_rules(),
                    $this->findimages_index_rules_message()
                );
                return $nestedForm;
            });
            $form->select('province', '省份')->options($model->getProvinceList())->load("city", "/admin/api/area")->rules(
                $this->province_rules(),
                $this->province_rules_message()
            );
            $form->select('city', '城市')->options(function($id)use($model){
                return $model->getSameLevelCityList($id);
            })->rules(
                $this->city_rules(),
                $this->city_rules_message()
            );
            $form->text('title', '标题')->rules(
                $this->title_rules(),
                $this->title_rules_message()
            );
            $form->text('name', '姓名')->rules(
                $this->name_rules(),
                $this->name_rules_message()
            );
            $form->select('sex', '性别')->options(self::SEX_ARR)->rules(
                $this->sex_rules(),
                $this->sex_rules_message()
            );
            $form->number('age', '年龄')->rules(
                $this->age_rules(),
                $this->age_rules_message()
            );
            $form->editor('desc', '详情')->rules(
                $this->desc_rules(),
                $this->desc_rules_message()
            );
            $form->text('contact_name', '联系人姓名')->rules(
                $this->contact_name_rules(),
                $this->contact_name_rules_message()
            );
            $form->text('contact_mobile', '联系人手机号码')->rules(
                $this->contact_mobile_rules(),
                $this->contact_mobile_rules_message()
            );
            $form->radio('status', 'Status')->options(self::STATUS_ARR)->rules(
                $this->status_rules(),
                $this->status_rules_message()
            );
            $form->switch('is_delete', 'Is delete')->states(parent::IS_DELETE_RULES_ARR)->rules(
                parent::is_delete_rules(),
                parent::IS_DELETE_MESSAGE
            );
        });

        return $grid;
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
        $grid->column('users.nickname', '发布者昵称');
        $grid->province('省份');
        $grid->city('城市');
        $grid->column('title','标题'); //title方法冲突
        $grid->name('姓名');
        $grid->sex('性别')->display(function($sex){
            return self::SEX_ARR[$sex];
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
     * 详情页面控制
     * @param Show $show
     * @author zx
     * @date 2018-12-29
     * @return Show
     */
    public function detail($id)
    {
        $shows = Admin::show(FindNotice::findOrFail($id), function(Show $show){
            $model = new Area();
            $show->id('ID');
            $show->users('发布人信息',function($user){
                $user->id("ID");
                $user->nickname("昵称");
                return $user;
            });
            $show->province('省份')->as(function($province)use($model){
                return $model->getAddressData($province)->name;
            });
            $show->city('城市')->as(function($city)use($model){
                return $model->getAddressData($city)->name;
            });
            $show->title('标题');
            $show->name('姓名');
            $show->sex('性别')->using(self::SEX_ARR);
            $show->age('年龄');
            $show->desc('详情');
            $show->contact_name('联系人姓名');
            $show->contact_mobile('联系电话');
            $show->created_at('Created at');
            $show->updated_at('Updated at');
        });
        return $shows;
    }

    private function findimages_cover_rules()
    {
        return [
            Rule::in("on", "off")
        ];
    }

    private function findimages_cover_rules_message()
    {
        return [
            "in" => "封面选择范围错误",
        ];
    }

    private function findimages_index_rules()
    {
        return "numeric";
    }

    private function findimages_index_rules_message()
    {
        return [
            "numeric" => "排序必须为数字"
        ];
    }

    private function province_rules()
    {
        return "required|numeric";
    }

    private function province_rules_message()
    {
        return [
            "required" => "省份必须",
            "numeric" => '省份格式错误'
        ];
    }

    private function city_rules()
    {
        return "required|numeric";
    }

    private function city_rules_message()
    {
        return [
            "required" => "城市必须",
            "numeric" => "城市格式错误"
        ];
    }

    private function title_rules()
    {
        return "required";
    }

    private function title_rules_message()
    {
        return [
            "required" => "标题必须"
        ];
    }

    private function name_rules()
    {
        return "required|min:2";
    }

    private function name_rules_message()
    {
        return [
            "required" => "姓名必须",
            "min" => "姓名不可少于两个字符"
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
            "in" => "性别范围错误"
        ];
    }

    private function age_rules()
    {
        return "numeric";
    }

    private function age_rules_message()
    {
        return [
            "numeric" => "年龄必须是数字"
        ];
    }

    private function desc_rules()
    {
        return "required";
    }

    private function desc_rules_message()
    {
        return [
            "required" => "详情必须"
        ];
    }

    private function contact_name_rules()
    {
        return "min:2";
    }

    private function contact_name_rules_message()
    {
        return [
            "min" => "联系人姓名不得少于两位字符"
        ];
    }

    private function contact_mobile_rules()
    {
        return [
            "required",
            "regex:/^1[3456789]\d{9}$/",
        ];
    }

    private function contact_mobile_rules_message()
    {
        return [
            "required" => "联系人手机号码必须",
            "regex" => "联系人手机号码格式错误"
        ];
    }

    private function status_rules()
    {
        return [
            Rule::in([0,1,2])
        ];
    }

    private function status_rules_message()
    {
        return [
            "in" => "审核状态范围错误"
        ];
    }

}