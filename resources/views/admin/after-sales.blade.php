
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">
            @if($info->opinion==1)
                退换货申请
            @else
                售后详情
            @endif
        </h3>
        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 10px">
                <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    
    <div class="box-body"  style="">
        <div id="form-save-student" class="form-horizontal">
            <div class="nav-tabs-custom" id="info">
                <div class="tab-pane" name="tab-form-1" id="tab-form-1">
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">申请人</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                {{$info->order->name}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">订单号</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->order->order_sn}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">订单商品</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->goods->goods_name}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">评论图片</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin" style="box-shadow:none;">
                                <div class="box-body">
                                    <img src="{{config('oss.images').$info->pic}}" alt="" style="max-width:500px;max-height:200px;min-width:200px;min-height:100px;border: 1px solid #e5e5e5;">&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">退换货原因</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin" style="box-shadow:none;">
                                <div class="box-body" style="height: 300px;border: 1px solid #ddd;overflow-y: scroll;">
                                    {{strip_tags($info->content)}}
                                    <!-- <textarea name="memo" class="form-control" rows="10" placeholder="请输入备注" disabled style="background-color:white;"></textarea> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">申请时间</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                {{$info->created_at}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($info->opinion==1)
                        <div class="box-footer">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <div class="btn-group pull-right">
                                    <button id="agree" class="btn btn-info pull-right" data-id="{{$info->id}}" data-loading-text="<i class='fa fa-spinner fa-spin '></i> 同意">同意</button>
                                </div>
                                <div class="btn-group pull-left">
                                    <button id="disagree" class="btn btn-warning" data-id="{{$info->id}}">不同意</button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="form-group ">
                            <label class="col-sm-2 control-label">处理意见</label>
                            <div class="col-sm-8">
                                <div class="box box-solid no-margin">
                                    <div class="box-body">
                                        @if($info->opinion==2)
                                            不同意&nbsp;
                                        @else
                                            同意&nbsp;
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-2 control-label">处理时间</label>
                            <div class="col-sm-8">
                                <div class="box box-solid no-margin">
                                    <div class="box-body">
                                        {{$info->updated_at}}&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.form-history-back').on('click', function (event) {
        event.preventDefault();
        history.back(1);
    });

    $(function () {
        $('#agree').on('click', function () {
            var id = $(this).data('id');
            swal({
                    title: "确定同意吗？",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "取消",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        method: 'post',
                        url: '/admin/after-sales-service',
                        data: {
                            id: id,
                            type:1,
                            _token: LA.token
                        },
                        success: function (data) {
                            $.pjax.reload('#pjax-container');

                            if (typeof data === 'object') {
                                if (data.status) {
                                    swal(data.message, '', 'success');
                                } else {
                                    swal(data.message, '', 'error');
                                }
                                setTimeout(function () {
                                    swal.close();
                                }, 2000)
                            }
                        }
                    });
                });
        });

        $('#disagree').on('click', function () {
            var id = $(this).data('id');
            swal({
                    title: "确定不同意吗？",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "取消",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        method: 'post',
                        url: '/admin/after-sales-service',
                        data: {
                            id: id,
                            type:2,
                            _token: LA.token
                        },
                        success: function (data) {
                            $.pjax.reload('#pjax-container');

                            if (typeof data === 'object') {
                                if (data.status) {
                                    swal(data.message, '', 'success');
                                } else {
                                    swal(data.message, '', 'error');
                                }
                                setTimeout(function () {
                                    swal.close();
                                }, 2000)
                            }
                        }
                    });
                });
        })
    })
</script>