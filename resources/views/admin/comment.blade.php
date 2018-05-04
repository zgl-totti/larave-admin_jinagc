
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">评论详情</h3>
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
                        <label class="col-sm-2 control-label">评论人</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                {{$info->user->name}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">订单号</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    @if($info->source==1)
                                        {{$info->order->order_sn}}&nbsp;
                                    @else
                                        {{$info->integralOrder->order_sn}}&nbsp;
                                    @endif
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
                        <label class="col-sm-2 control-label">评论内容</label>
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
                        <label class="col-sm-2 control-label">评论时间</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                {{$info->created_at}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    {{--<div style="clear: both;height: 20px;"></div>--}}
</div>
{{--</div>
</div>--}}
<script type="text/javascript">
    $('.form-history-back').on('click', function (event) {
        event.preventDefault();
        history.back(1);
    });
</script>