
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{--订单详情--}}</h3>
        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 10px">
                @if($info->order_status==2)
                    <button type="button" class="btn btn-sm btn-success"
                            style="background: goldenrod;margin-right: 10px;"
                            data-id="{{$info->id}}"
                            data-type="1"
                            data-container="body">
                        发货
                    </button>
                @elseif($info->order_status==8)
                    <a href="/admin/after-sales/{{$info->id}}" class="btn btn-sm btn-default" style="background: darkorchid;color: ghostwhite;margin-right: 10px;">
                        <i class="fa fa-arrow-left"></i>&nbsp;售后
                    </a>
                @endif
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
                        <label class="col-sm-2 control-label">订单用户</label>
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
                                    {{$info->order_sn}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">订单总价</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->order_price}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">订单状态</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->status->status_name}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">订单商品</label>
                        <div class="col-sm-3">
                            @foreach($info->orderGoods as $item)
                                <div class="box box-solid no-margin">
                                    <div class="box-body">
                                        {{$item->goods_name}}&nbsp;
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-sm-2">
                            @foreach($info->orderGoods as $item)
                                <div class="box box-solid no-margin">
                                    <div class="box-body">
                                        {{$item->type_name}}&nbsp;&nbsp;
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-sm-3">
                            @foreach($info->orderGoods as $item)
                                <div class="box box-solid no-margin">
                                    <div class="box-body">
                                        ￥：{{$item->buy_price}}&nbsp;&nbsp;*&nbsp;&nbsp;{{$item->buy_number}}&nbsp;{{$item->goods_brief}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">快递方式</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->express->express_name}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">收货人</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->consignee}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">收货电话</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->consignee_phone}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">收货地址</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->address}}{{$info->area}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">备注信息</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->order_msg}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">订单来源</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->source->source_name}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(in_array($info->order_status,[2,3,4,5,6,7,8]))
                        <div class="form-group ">
                            <label class="col-sm-2 control-label">支付方式</label>
                            <div class="col-sm-8">
                                <div class="box box-solid no-margin">
                                    <div class="box-body">
                                        {{$info->pay->pay_name}}&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group ">
                        <label class="col-sm-2 control-label">创建时间</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                {{$info->created_at}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($info->order_status==3)
                        <div class="form-group ">
                            <label class="col-sm-2 control-label">发货时间</label>
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
                    url: '/admin/order/shipments',
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
</script>