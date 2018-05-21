<?php

namespace App\Admin\Controllers;

use App\Models\Version;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class VersionController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('版本');
            $content->description('列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('版本');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('版本');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Version::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->app_type('App类型')->display(function ($id){
                return Version::appTypeMap()[$id] ?? '未知';
            });
            $grid->version('大版本号');
            $grid->version_mini('小版本号');
            $grid->is_force('更新状态')->display(function ($id){
                return Version::isForceMap()[$id] ?? '未知';
            });
            $grid->url('链接地址');
            $grid->status('状态')->switch([
                'on'=>['text'=>'上架'],
                'off'=>['text'=>'下架']
            ]);

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableExport();

            $grid->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->equal('app_type','App类型')->radio([''=>'全部'] + Version::appTypeMap());
                $filter->equal('is_force','更新状态')->radio([''=>'全部'] + Version::isForceMap());
                $filter->equal('status','状态')->radio([''=>'全部'] + Version::statusMap());
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Version::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['version'] ?? 0;

            $form->radio('app_type','app类型')->options(Version::appTypeMap())->rules('required');
            $form->text('version','大版本号')->rules('required');
            $form->text('version_mini','小版本号')->rules('required');
            $form->radio('is_force','更新状态')->options(Version::isForceMap())->rules('required');
            $form->text('url','链接地址')->rules('required');

            if($id){
                $form->switch('status','状态');
            }

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
