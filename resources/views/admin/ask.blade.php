
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">详情</h3>
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
                        <label class="col-sm-2 control-label">提问者</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                {{$info->user->name}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">电话</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->phone}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">来源</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->source->source_name}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">科室</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin">
                                <div class="box-body">
                                    {{$info->departments->cate_name}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">专家</label>
                        <div class="col-sm-8">
                            <div class="box box-solid no-margin" style="box-shadow:none;">
                                <div class="box-body">
                                    {{$info->expert->username}}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">提问内容</label>
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
                        <label class="col-sm-2 control-label">提问时间</label>
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