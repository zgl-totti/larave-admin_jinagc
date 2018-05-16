<?php

namespace App\Admin\Controllers;

use App\Models\InquiryAsk;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;

class InquiryAskController extends Controller
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

            $content->header('提问');
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

            $content->header('提问');
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

            $content->header('提问');
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
        return Admin::grid(InquiryAsk::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->column('departments.cate_name','科室');
            $grid->column('expert.username','专家');
            $grid->column('source.source_name','来源');
            $grid->column('user.name','提问者');
            $grid->phone('电话');

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableExport();
            $grid->disableCreateButton();

            $grid->actions(function ($actions){
                $actions->disableEdit();
                $actions->prepend('<a href="ask/'.$actions->getKey().'"><i class="fa fa-eye"></i></a>');
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
        return Admin::form(InquiryAsk::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }


    public function ask(int $id)
    {
        $info=InquiryAsk::with(['departments','expert','user','source'])
            ->where('id',$id)
            ->first();

        return Admin::content(function (Content $content) use ($info){

            $content->header('提问');

            $content->row(function (Row $row) use ($info){

                $row->column(6, function (Column $column) use ($info){
                    $column->append(view('admin.ask',compact('info')));
                });

                $row->column(6, function (Column $column) use ($info){
                    $column->append(view('admin.reply',compact('info')));
                });
            });
        });
    }
}
