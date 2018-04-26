<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

/**
 * 指定专业老师
 */
class ProfesserTeacher extends AbstractTool
{

    private $schoolID;
    private $isMobile;

    public function __construct($schoolID, $isMobile = false)
    {
        $this->schoolID = $schoolID;
        $this->isMobile = $isMobile;
    }

    /**
     * 专业老师
     * @return array
     */
    private function majorTeacher(): array
    {
        $teachers = (new \Encore\Admin\Auth\Database\Administrator())
                ->allTeachers($this->schoolID, config('admin.role')['major']);
        return is_array($teachers) ? $teachers : [];
    }

    /**
     * 教师专业
     * @param array $teacherIDs
     * @return array
     */
    protected function major($teacherIDs): array
    {
        $teacherMajorMap = (new \App\Models\UserMajorCates())->majorCateMap($teacherIDs);
        $htmlArray = [];
        $isMobile = $this->isMobile;
        foreach ($teacherMajorMap as $teacherID => $majors) {
            $htmlArray[$teacherID] = array_map(function($val) use($isMobile) {
                if ($isMobile) {
                    return $val;
                }
                return sprintf('<span class="label label-success">%s</span>', $val);
            }, $majors);
        }
        return $htmlArray;
    }

    protected function script(): string
    {
        return $this->isMobile ? $this->mobileScript() : $this->pcScript();
    }

    private function mobileScript(): string
    {
        return '';
    }

    private function pcScript(): string
    {
        return <<<EOT
$('#btn_allot').click(function(){
    var teacherID = $("input[name='teacher_id']:checked").val();
    if (!teacherID) {
         var tips = {
            title:'<small>请选择</small>教师!',
            text:'', 
            html:true,
            timer:2500
        };
        swal(tips);
        return;
    }

    swal({ 
            title: "确定选择吗？", 
            text: "", 
            type: "warning",
            showCancelButton: true, 
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定选择！", 
            closeOnConfirm: true
        },
        function() {
            $("#consult_major_teacher_id").val(teacherID);
            //$("#teacher_name").html();
            $(".js-allot-close").click();
            $('#myModal').modal('hide');
        }
    );
});
EOT;
    }

    private function html(): string
    {
        $teachers = $this->majorTeacher();
        $teacherIDs = array_map(function($object) {
            return $object->id;
        }, $teachers);
        $majorHtml = $this->major($teacherIDs);
        if ($this->isMobile) {
            return $this->mobileHtml($teachers, $majorHtml);
        }
        return $this->pcHtml($teachers, $majorHtml);
    }

    protected function mobileHtml($teachers, $majorHtml): string
    {
        $html = '';
        $tpl = <<<EOF
<li>
    <span class="teacher_id am-hide">%d</span>
    <span class="name">%s</span>
    <span class="major">%s</span>
</li>
EOF;
        foreach ($teachers as $val) {
            $majorStr = isset($majorHtml[$val->id]) ? implode(' ', $majorHtml[$val->id]) : '';
            $html .= preg_replace('/>\s*</', '><', sprintf($tpl, $val->id, $val->name, $majorStr));
        }

        return <<<EOF
<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">请选择专业老师</div>
        <div class="am-modal-bd">
            <ul>
               $html
            </ul>
        </div>
    </div>
</div>
EOF;
    }

    protected function pcHtml($teachers, $majorHtml): string
    {
        $html = '';
        $tpl = <<<EOF
<tr style="">
    <td>
        <input type="radio" class="grid-row-checkbox" name="teacher_id" value="%d"  dataname="%s">
    </td>
    <td>%d</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
</tr>
EOF;
        foreach ($teachers as $val) {
            $majorStr = isset($majorHtml[$val->id]) ? implode(' ', $majorHtml[$val->id]) : '';
            $html .= sprintf($tpl, $val->id, $val->name, $val->id, $val->name, $val->role_name, $majorStr);
        }

        return <<<EOT
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					专业教师选择
				</h4>
			</div>
			<div class="modal-body">
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
				<!-- 在这里添加一些文本 -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default js-allot-close" data-dismiss="modal">关闭
				</button>
				<button id="btn_allot" type="button" class="btn btn-primary">
					确认选择
				</button>
			</div>
		</div>
	</div>
</div>
EOT;
    }

    public function render(): string
    {
        $html = $this->html();
        Admin::script($this->script());
        return $html;
    }

}
