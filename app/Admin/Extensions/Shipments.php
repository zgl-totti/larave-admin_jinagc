<?php

namespace App\Admin\Extensions;

use App\Models\NewsContent;
use App\Models\Order;
use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class Shipments
{
    protected $id;

    protected $type;

    public function __construct($type,$id)
    {
        $this->id = $id;
        $this->type = $type;
    }

    protected function script()
    {

        return <<<SCRIPT

$('.btn-success').on('click', function () {

    var id=$(this).data('id');
    var type=$(this).data('type');
    
    swal({ 
            title: "确定发货吗？", 
            text: "", 
            type: "warning",
            showCancelButton: true, 
            cancelButtonText: "取消", 
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定", 
            closeOnConfirm: false
        },
    function() {
        $.ajax({
            method: 'post',
            url: 'order/shipments',
            data: {
                id:id,
                type:type,
                _token:LA.token,
            },
            success: function (data) {
                $.pjax.reload('#pjax-container');
        
                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                    setTimeout(function(){
                        swal.close();
                    },2000)
                }
            }
        });
    });
});

SCRIPT;

    }


    protected function render()
    {

        Admin::script($this->script());

        return <<<EOT

<button type="button"
    class="btn btn-sm btn-success"
    style="background: goldenrod"
    data-id="{$this->id}"
    data-type="{$this->type}"
    data-container="body"
    >
    发货
</button>

EOT;

    }

    public function __toString()
    {
        return $this->render();
    }
}
