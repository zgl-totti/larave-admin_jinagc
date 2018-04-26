<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

/**
 * 学生分配按钮
 */
class StudentAllot extends AbstractTool
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
    var studentIDs = new Array();
    $('div.box > div.box-body .grid-row-checkbox:checked').each(function(){
        studentIDs.push($(this).attr("data-id"))
    });
    if (studentIDs.length == 0) {
         var tips = {
            title:'<small>请选择</small>被分配的学生!',
            text:'在行首的<span style="color:#F8BB86">方框中打勾<span>即可', 
            html:true,
            timer:2500
        };
        swal(tips);
        return;
    }
    var teacherIDs = new Array();
    $("input[name='teacher_id[]']:checked").each(function(){
        teacherIDs.push($(this).val())
    });

    if (teacherIDs.length == 0) {
         var tips = {
            title:'<small>请选择</small>教师!',
            text:'在行首的<span style="color:#F8BB86">方框中打勾<span>即可', 
            html:true,
            timer:2500
        };
        swal(tips);
        return;
    }

    swal({ 
            title: "确定分配吗？", 
            text: "", 
            type: "warning",
            showCancelButton: true, 
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定分配！", 
            closeOnConfirm: false
        },
        function() {
            doAllot(teacherIDs, studentIDs)
        }
    );
});
function doAllot(teacherIDs, studentIDs) {
    $.ajax({
        method: 'post',
        url: '$url/allot',
        data: {
            teacher_id:teacherIDs.toString(),
            student_id:studentIDs.toString(),
            _token:LA.token,
        },
        success: function (data) {
            if (typeof data === 'object') {
                if (data.status) {
                    $(".js-allot-close").click();
                    $('#myModal').modal('hide');
                    //swal(data.message, '', 'success');
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

    protected function majorHtml($teacherIDs)
    {
         $teacherMajorMap = (new \App\Models\UserMajorCates())->majorCateMap($teacherIDs);
         $htmlArray = [];
         foreach ($teacherMajorMap as $teacherID => $majors) {
             $htmlArray[$teacherID] =
                     array_map(function($val){
                         return sprintf('<span class="label label-success">%s</span>', $val);
                     }, $majors);
         }
         return $htmlArray;
    }

    protected function setHtml(): string
    {
        $teachers = (new \Encore\Admin\Auth\Database\Administrator())
                ->allTeachers($this->schoolID, config('admin.role')['consult']);
        $teacherIDs = array_map(function($object) {
            return $object->id;
        }, $teachers);
        $majorHtml = $this->majorHtml($teacherIDs);
        $html = '';
        $tpl = <<<EOF
<tr style="">
    <td>
        <input type="checkbox" class="grid-row-checkbox" name="teacher_id[]"
                style="position: absolute; opacity: 0;" value="%d">
    </td>
    <td>%d</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
</tr>
EOF;
        foreach ($teachers as $val) {
            $majorStr = isset($majorHtml[$val->id]) ? implode(',', $majorHtml[$val->id]) : '';
            $html .= sprintf($tpl, $val->id, $val->id, $val->name, $val->role_name, $majorStr);
        }
        return <<<EOF
<div class="box-body table-responsive no-padding">
    <table class="table table-hover">
    <tbody>
        <tr>
            <th></th>
            <th>ID</th>
            <th>名字</th>
            <th>教师角色</th>
            <th>专业</th>
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
					教师选择
				</h4>
			</div>
			<div class="modal-body">
                    $html
				<!-- 在这里添加一些文本 -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default js-allot-close" data-dismiss="modal">关闭
				</button>
				<button id="btn_allot" type="button" class="btn btn-primary">
					确认分配
				</button>
			</div>
		</div>
	</div>
</div>

<div class="btn-group" data-target="#myModal">
    <label class="btn btn-twitter btn-sm grid-status">
        <i class="fa fa-trash"></i> 分配
    </label>
</div>

EOT;
    }

}
