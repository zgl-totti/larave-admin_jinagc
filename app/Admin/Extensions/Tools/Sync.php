<?php

namespace App\Admin\Extensions\Tools;

use App\Models\RepositoryCate;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

class Sync extends AbstractTool
{

    private $schoolID;

    public function __construct($schoolID)
    {
        $this->schoolID = $schoolID;
    }

    protected function script()
    {
        $url = $this->grid->resource();
        return <<<EOT

$('.grid-status').click(function () {
    $('#myModal').modal({
        keyboard: true
    })
});

$('#btn_allot').click(function(){
    var school_id= $('#school').val();
    var cateIDs = new Array();
    $("input[name='cate_id[]']:checked").each(function(){
        cateIDs.push($(this).val())
    });

    if (cateIDs.length == 0) {
         var tips = {
            title:'<small>请选择</small>分类名称!',
            text:'在行首的<span style="color:#F8BB86">方框中打勾<span>即可', 
            html:true,
            timer:2500
        };
        swal(tips);
        return;
    }

    swal({ 
            title: "确定共享分类吗？", 
            text: "", 
            type: "warning",
            showCancelButton: true, 
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定共享！", 
            closeOnConfirm: false
        },
        function() {
            sync(cateIDs,school_id)
        }
    );
});

function sync(cateIDs, school_id) {
    $.ajax({
        method: 'post',
        url: '$url/sync',
        data: {
            cate_id:cateIDs.toString(),
            school_id:school_id,
            _token:LA.token,
        },
        success: function (data) {
            if (typeof data === 'object') {
                if (data.status) {
                    $(".js-allot-close").click();
                    $('#myModal').modal('hide');
                    $.pjax({ url: '$url?_pjax=#pjax-container', container: '#pjax-container' });
                    alterSuccess(data.message);
                } else {
                    swal(data.message, '', 'error');
                }
            }
        }
    });
}

function alterSuccess(message) {
    var tips = {
        title: message,
        text:'', 
        html:true,
        type:'success',
        timer:2000
    };
    swal(tips);
}

EOT;
    }

    protected function setHtml(): string
    {
        $cates=RepositoryCate::where('school_id',0)->orderBy('parent_id')->get();
        $html = '';
        $tpl = <<<EOF
<tr style="">
    <td>
        <input type="checkbox" class="grid-row-checkbox" name="cate_id[]"
                style="position: absolute; opacity: 0;" value="%d">
    </td>
    <td>%d</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
</tr>
EOF;
        foreach ($cates as $val){
            if(empty($val['parent_id'])){
                $parent='ROOT';
            }else{
                $info=RepositoryCate::find($val['parent_id']);
                $parent=$info->title;
            }
            $html .= sprintf($tpl, $val->id, $val->id, $val->title, $val->desc, $parent);
        }
        return <<<EOF
<div class="box-body table-responsive no-padding">
    <table class="table table-hover">
    <tbody>
        <tr>
            <th></th>
            <th>ID</th>
            <th>标题</th>
            <th>内容</th>
            <th>父级分类</th>
        </tr>
        $html
    </tbody>
    </table>
</div>
EOF;
    }

    public function render()
    {
        $html = $this->setHtml();
        Admin::script($this->script());
        return <<<EOT
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					分类选择
				</h4>
			</div>
			<div class="modal-body">
			    <input name="school_id" value="{$this->schoolID}" id="school" type="hidden">
                    $html
				<!-- 在这里添加一些文本 -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default js-allot-close" data-dismiss="modal">关闭
				</button>
				<button id="btn_allot" type="button" class="btn btn-primary">
					确认共享
				</button>
			</div>
		</div>
	</div>
</div>

<div class="btn-group" data-target="#myModal">
    <label class="btn btn-twitter btn-sm grid-status">
        <i class="fa fa-trash"></i> 分类模板
    </label>
</div>

EOT;
    }

}
