
<script type="text/javascript" src="{{asset('vendor/wangEditor-3.1.1/release/wangEditor.js')}}"></script>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">回复</h3>
        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 10px">
                @if(!empty($info['reply']))
                    <a href="/comments/{{$info->id}}/edit" class="btn btn-sm btn-default form-history-backs"><i class="fa fa-edit"></i>&nbsp;修改</a>
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
                    @if(empty($info['reply']))
                        <form action="{{url('admin/reply')}}" method="post">

                            <div class="form-group ">
                                <label class="col-sm-2 control-label">内容</label>
                                <div class="col-sm-8">
                                    <div class="box box-solid no-margin" style="box-shadow:none;">
                                        <div id="editor"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="id" value="{{$info->id}}">
                                <input type="hidden" name="content" id="content">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <div class="btn-group pull-right">
                                        <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">提交</button>
                                    </div>
                                    <div class="btn-group pull-left">
                                        <button type="reset" class="btn btn-warning">撤销</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    @else
                        <div class="form-group ">
                            <label class="col-sm-2 control-label">回复内容</label>
                            <div class="col-sm-8">
                                <div class="box box-solid no-margin" style="box-shadow:none;">
                                    <div class="box-body" style="height: 300px;border: 1px solid #ddd;overflow-y: scroll;">
                                    {{strip_tags(htmlspecialchars_decode($info->reply->content))}}
                                    <!-- <textarea name="memo" class="form-control" rows="10" placeholder="请输入备注" disabled style="background-color:white;"></textarea> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-2 control-label">回复时间</label>
                            <div class="col-sm-8">
                                <div class="box box-solid no-margin">
                                    <div class="box-body">
                                        {{$info->reply->created_at}}&nbsp;
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
    <div style="clear: both;height: 20px;"></div>
</div>
{{--</div>
</div>--}}
<script>
    $('.form-history-back').on('click', function (event) {
        event.preventDefault();
        history.back(1);
    });
</script>
<script type="text/javascript">
    var E = window.wangEditor;
    var editor = new E('#editor');
    editor.create();

    $('.pull-right').click(function () {
        $('#content').attr('value',editor.txt.html());
    })
</script>