<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HotWords;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;

class HotWordsController extends Controller
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

            $content->header('热词');
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

            $content->header('热词');
            $content->description('编辑');

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

            $content->header('热词');
            $content->description('添加');

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
        return Admin::grid(HotWords::class, function (Grid $grid) {

            $grid->id('编号')->sortable();
            $grid->hot_word('热词名称');
            $grid->click_total('点击量');

            $grid->status('状态')->switch([
                'on'=>['text'=>'展示'],
                'off'=>['text'=>'下架']
            ]);

            $grid->filter(function($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();
                // 在这里添加字段过滤器
                $filter->like('hot_word', '热词名称')->placeholder('请输入热词名称');
            });

            $grid->created_at('创建时间');
            //$grid->updated_at('更新时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(HotWords::class, function (Form $form) {

            //$form->display('id', '编号');

            $id=request()->route()->parameters()['hot_word'] ?? 0;
            if($id){
                $form->text('hot_word','热词名称')->rules('required|max:10|unique:hot_words,hot_word,'.$id.',id');
            }else{
                $form->text('hot_word','热词名称')->rules('required|max:10|unique:hot_words');
            }

            if($id){
                $form->switch('status','状态');
            }

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }

}
