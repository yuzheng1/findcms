<?php

namespace App\Admin\Controllers;

use App\Models\Area;
use App\Models\FindNotice;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\NestedForm;

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
        $grid->column('users.nickname', '发布者昵称');
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
//        $show = new Show(FindNotice::findOrFail($id));
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
        });
        return $shows;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $grid = Admin::form(FindNotice::class, function(Form $form){
            $model = new Area();
            $form->display("users.id", "发布人ID");
            $form->display("users.nickname", "发布人昵称");
            $form->hasMany("findimages", function (NestedForm $nestedForm){
                $nestedForm->image("url",'幻灯片')->uniqueName();
                $cover_arr = [
                    'on' => ['value' => 1, 'text' => '封面', 'color'=>'danger'],
                    'off' => ['value' => 0, 'text' => '非封面', 'color' => 'success']
                ];
                $nestedForm->switch("cover", "是否为封面")->options($cover_arr)->rules(
                    [
                        Rule::in("on", "off")
                    ],
                    [
                        "in" => "封面选择范围错误",
                    ]
                );
                $nestedForm->number("index", "排序")->default(1)->rules(
                    "numeric",
                    [
                        "numeric" => '排序格式错误'
                    ]
                );
                return $nestedForm;
            });
            $form->select('province', '省份')->options($model->getProvinceList())->load("city", "/admin/api/area")->rules(
                "required|numeric",
                [
                    "required" => "省份必须",
                    "numeric" => '省份格式错误'
                ]
            );
            $form->select('city', '城市')->options(function($id)use($model){
                return $model->getSameLevelCityList($id);
            })->rules(
                "required|numeric",
                [
                    "required" => "城市必须",
                    "numeric" => "城市格式错误"
                ]
            );
            $form->text('title', '标题')->rules(
                "required",
                [
                    "required" => "标题必须"
                ]
            );
            $form->text('name', '姓名')->rules(
                "required|min:2",
                [
                    "required" => "姓名必须",
                    "min" => "姓名不可少于两个字符"
                ]
            );
            $sexArr = [
                "男",
                "女"
            ];
            $form->select('sex', '性别')->options($sexArr)->rules(
                [
                    Rule::in([0,1])
                ],
                [
                    "in" => "性别范围错误"
                ]
            );
            $form->number('age', '年龄')->rules(
                "numeric",
                [
                    "numeric" => "年龄必须是数字"
                ]
            );
            $form->textarea('desc', '详情')->rules(
                "required",
                [
                    "required" => "详情必须"
                ]
            );
            $form->text('contact_name', '联系人姓名')->rules(
                "min:2",
                [
                    "min" => "联系人姓名不得少于两位字符"
                ]
            );
            $form->text('contact_mobile', '联系人手机号码')->rules(
                [
                    "required",
                    "regex:/^1[3456789]\d{9}$/",
                ],
                [
                    "required" => "联系人手机号码必须",
                    "regex" => "联系人手机号码格式错误"
                ]
            );
            $statusArr = [
                "审核中",
                "审核通过",
                "审核不通过",
            ];
            $form->radio('status', 'Status')->options($statusArr)->rules(
                [
                    Rule::in([0,1,2])
                ],
                [
                    "in" => "审核状态范围错误"
                ]
            );
            $is_delete_arr = [
                'on' => ['value' => 1, 'text' => '删除', 'color'=>'danger'],
                'off' => ['value' => 0, 'text' => '不删除', 'color' => 'success']
            ];
            $form->switch('is_delete', 'Is delete')->states($is_delete_arr)->rules(
                [
                    Rule::in(['on','off'])
                ],
                [
                    "in" => "需求状态范围错误"
                ]
            );
        });
        return $grid;
    }
}
