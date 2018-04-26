<?php

namespace App\Admin\Controllers;

use App\Models\Comment;
use App\Models\CommentReply;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Illuminate\Http\Request;

class CommentController extends Controller
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

            $content->header('评论');
            $content->description('列表');

            //$content->body($this->treeView());
            $content->body($this->grid());
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return Comment::tree(function (Tree $tree) {

            $tree->disableCreate();
            $tree->disableSave();

            $tree->branch(function ($branch) {

                $info=Comment::with(['user','order','goods','commentStatus','integralOrder'])
                    ->where('id',$branch['id'])
                    ->first();
                if($info->source==1){
                    $info['order_sn']=$info['order']['order_sn'];
                }else{
                    $info['order_sn']=$info['integralOrder']['order_sn'];
                }

                $payload = "<span><strong style='color: orangered'>{$branch['id']}.</strong>&nbsp;&nbsp;&nbsp;&nbsp;
                            <strong style='color: blueviolet'>评论者：{$info['user']['name']}</strong>&nbsp;&nbsp;&nbsp;&nbsp;
                            <strong style='color: purple'>订单号：{$info['order_sn']}</strong>&nbsp;&nbsp;&nbsp;&nbsp;
                            <strong style='color: green'>订单商品：{$info['goods']['goods_name']}</strong>&nbsp;&nbsp;&nbsp;&nbsp;
                            <strong style='color: red'>好评度：{$info['commentStatus']['status_name']}</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <strong>内容：</strong>".str_limit(strip_tags($info->content),50)."
                            </span>";

                return $payload;
            });

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

            $content->header('评论');
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

            $content->header('评论');
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
        return Admin::grid(Comment::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->column('user.name','评论人');

            $grid->column('content','评论内容')->display(function ($content){
                return str_limit(strip_tags($content),100);
            });

            //$grid->source('订单');

            /*if($this->source==1){
                $grid->column('order.order_sn','订单号');
            }elseif ($this->source==2){
                $grid->column('integralOrder.order_sn','订单号');
            }*/

            $grid->column('goods.goods_name','评论商品');
            $grid->column('commentStatus.status_name','好评度')->label();

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableCreateButton();
            $grid->actions(function ($actions){
                $actions->disableEdit();
                $actions->prepend('<a href="comments/'.$actions->getKey().'"><i class="fa fa-eye"></i></a>');
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
        return Admin::form(Comment::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }


    /**
     * 查看评论详情
     * @param int $id
     * @return Content
     * @author totti_zgl
     * @date 2018/4/20 13:26
     */
    public function examine(int $id)
    {
        $info=Comment::with('reply')->where('id',$id)->first();

        return Admin::content(function (Content $content) use ($info){

            $content->header('评论详情');

            $content->row(function (Row $row) use ($info){

                $row->column(6, function (Column $column) use ($info){
                    $column->append(view('admin.comment',compact('info')));
                });

                $row->column(6, function (Column $column) use ($info){
                    $column->append(view('admin.reply',compact('info')));
                });
            });
        });
    }


    /**
     * 回复
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author totti_zgl
     * @date 2018/4/20 16:49
     */
    public function reply(Request $request)
    {
        $id=intval($request->input('id'));
        $content=htmlspecialchars($request->input('content'));
        $reply= new CommentReply();
        $reply->comment_id=$id;
        $reply->content=$content;
        $reply->save();
        return redirect()->back();
    }
}
