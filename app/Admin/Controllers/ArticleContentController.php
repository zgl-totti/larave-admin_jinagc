<?php

namespace App\Admin\Controllers;

use App\Models\ArticleCates;
use App\Models\ArticleContents;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ArticleContentController extends Controller
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

            $content->header('文章');
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

            $content->header('文章');
            $content->description('列表');

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

            $content->header('文章');
            $content->description('列表');

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
        return Admin::grid(ArticleContents::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->column('cate.cate_name','分类');
            $grid->title('标题')->label();
            $grid->column('content','内容')->display(function ($content){
                return str_limit(strip_tags($content),30);
            });

            $grid->status('状态')->switch([
                'on'=>['text'=>'展示'],
                'off'=>['text'=>'下架']
            ]);

            /*$grid->status('状态')->display(function ($status){
                return ArticleContents::statusMap()[$status] ?? '未知';
            })->label();*/

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();
                // 在这里添加字段过滤器
                $filter->like('title', '标题')->placeholder('请输入标题');

                $filter->in('cate_id','分类')->select(ArticleCates::selectOptions());

                $filter->between('created_at','创建时间')->datetime();

                $filter->equal('status', '状态')->radio(['' => '全部'] + ArticleContents::statusMap());
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
        return Admin::form(ArticleContents::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['article_content'] ?? 0;
            $form->text('title','标题')->rules('required');

            $form->select('cate_id','分类')->options(ArticleCates::selectOptions())->rules('required');

            if($id){
                $form->switch('status','状态');
                //$form->select('status','状态')->options(ArticleContents::statusMap());
            }

            $form->editor('content','内容')->rules('required');

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
