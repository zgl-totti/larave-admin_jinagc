<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Keywords" content="金手臂健康商城" />
    <meta name="Description" content="金手臂健康商城" />
    <title>登录</title>
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" type="text/css" href="{{asset('index/css/base.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('index/css/style.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('index/css/iconfont.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('index/css/purebox.css')}}" />
    <script type="text/javascript" src="{{asset('index/js/jquery-1.9.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('index/js/jquery.json.js')}}"></script>
    <script type="text/javascript" src="{{asset('index/js/transport_jquery.js')}}"></script>
</head>

<body class="bg-ligtGary">
<div class="login">
    <div class="loginRegister-header">
        <div class="w w1200">
            <div class="logo">
                <div class="logoImg"><a href="./index.php" class="logo"><img src="{{asset('index/images/common/logo.png')}}" /></a></div>
                <div class="logo-span">
                    <b style="background:url({{asset('index/images/common/logo.png')}}) no-repeat;"></b>
                </div>
            </div>
            <div class="header-href">
                <span>还没有账号，<a href="{{route('register')}}" class="jump">免费注册</a></span>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="login-wrap">
            <div class="w w1200">
                <div class="login-form">
                    <div class="coagent">
                        <div class="tit"><h3>用第三方账号直接登录</h3><span></span></div>
                        <div class="coagent-warp">
                            <a href="{{url('auth/login_wechat')}}" class="wechat"><b class="third-party-icon wechat-icon"></b></a>
                            <a href="{{url('auth/login_qq')}}" class="qq"><b class="third-party-icon qq-icon"></b></a>
                        </div>
                    </div>
                    <div class="login-box">
                        <div class="tit"><h3>账号登录</h3><span></span></div>
                        <div class="msg-wrap">
                            <div class="msg-error" style="display:none"></div>
                        </div>
                        <div class="form">
                            <form class="form-horizontal" action="{{route('login')}}" method="POST">
                                <div class="item">
                                    <div class="item-info">
                                        <i class="iconfont icon-name"></i>
                                        <input type="text" id="username" name="email" class="text" value="" placeholder="用户名/邮箱/手机" autocomplete="off" />
                                    </div>
                                </div>
                                @if($errors->has('email'))
                                    @foreach($errors->get('email') as $message)
                                        <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label></br>
                                    @endforeach
                                @endif
                                <div class="item">
                                    <div class="item-info">
                                        <i class="iconfont icon-password"></i>
                                        <input type="password" id="nloginpwd" name="password" class="text" value="" placeholder="密码" autocomplete="off" />
                                    </div>
                                </div>
                                @if($errors->has('password'))
                                    @foreach($errors->get('password') as $message)
                                        <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label></br>
                                    @endforeach
                                @endif

                                <div class="item">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 请保存我这次的登录信息
                                    </label>
                                </div>

                                <div class="item item-button">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                    <button type="submit" class="btn sc-redBg-btn">
                                        登录
                                    </button>
                                    <a class="notpwd gary" href="{{ route('password.request') }}">
                                        忘记密码?
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="login-banner" style="background:url(http://www.jinagc.com/data/afficheimg/1501807731629193863.jpg) center center no-repeat;">
                <div class="w w1200">
                    <div class="banner-bg"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer user-footer">
    <div class="dsc-copyright">
        <div class="w w1200">
            <p class="footer-ecscinfo pb10">
                <a href="http://www.jinagc.com/index.php" >首页</a>
                |
                <a href="http://www.jinagc.com/article.php?id=2" >隐私保护</a>
                |
                <a href="http://www.jinagc.com/message.php" >意见反馈</a>
            </p>
            <p class="copyright_info">ICP备案证书号:<a href="" target="_blank">GTJA00000123</a> <a href="http://jsb.zhongguanjiaoshi.com/" target="_blank">金手臂健康商城</a></p>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/scroll_city.js"></script>
<script src="https://g.alicdn.com/aliww/h5.openim.log/0.0.3/scripts/wlog.js" charset="utf-8"></script>

<script type="text/javascript">
    WLOG.init({
        uid: 'uid6975e2c6464ed3fca9ebab97f4b40a8f4cf703dc', // 当前页面用户名
        appkey:'23847497', // 应用appkey
    });

    WLOG.init({
        uid: 'uid6975e2c6464ed3fca9ebab97f4b40a8f4cf703dc', // 当前页面用户名
        appkey:'', // 应用appkey
    });
    //IM
    function openWin(obj) {
        if($(obj).attr('IM_type') != 'dsc'){
            var goods_id = '&goods_id='+$(obj).attr('goods_id');
        }else{
            var goods_id = '';
        }
        var url='online.php?act=service'+goods_id                   //转向网页的地址;
        var name='webcall';                         //网页名称，可为空;
        var iWidth=700;                          //弹出窗口的宽度;
        var iHeight=500;                         //弹出窗口的高度;
        //获得窗口的垂直位置
        var iTop = (window.screen.availHeight - 30 - iHeight) / 2;
        //获得窗口的水平位置
        var iLeft = (window.screen.availWidth - 10 - iWidth) / 2;
        window.open(url, name, 'height=' + iHeight + ',,innerHeight=' + iHeight + ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft + ',status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=0,titlebar=no');
    }
</script><script type="text/javascript" src="js/user.js"></script>
<script type="text/javascript" src="js/user_register.js"></script>
<script type="text/javascript" src="js/utils.js"></script>
<script type="text/javascript" src="js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="./sms/sms.js"></script>
<script type="text/javascript" src="js/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="themes/ecmoban_dsc2017/js/dsc-common.js"></script>
{{--<script type="text/javascript">


    $(function(){
        if(document.getElementById("seccode")){
            $("#seccode").val(0);
        }

        $("form[name='formUser'] :input[name='register_type']").val(1);

        //验证码切换
        $(".changeNextone").click(function(){
            $("#captcha_img").attr('src', 'captcha.php?'+Math.random());
        });
        $(".changeNexttwo").click(function(){
            $("#authcode_img").attr('src', 'captcha.php?'+Math.random());
        });

        var is_passwd_questions = $("form[name='getPassword'] :input[name='is_passwd_questions']").val();

        if(typeof(is_passwd_questions) == 'undefined'){
            $("#form_getPassword1").hide();
            $("#form_getPassword2").hide();
            $("#form_getPassword1").siblings().css({'width':'50%'});
        }

        $(".email_open").click(function(){

            var email = $("#regName").val();

            if(email){
                checkEmail(email);
            }else{
                $("#phone_notice").html('');
                $("#code_notice").html('');
                $("#phone_verification").val(0);
            }

            $("#mobile_phone").val("");
            $("#email_yz").show();
            $("#email_yz").find(".tx_rm").show();

            $("#phone_yz").hide();
            $("#code_mobile").hide();

            $("form[name='formUser'] :input[name='register_type']").val(0);
            $("#registsubmit").attr("disabled", false);
        });

        $(".email_off").click(function(){

            var mobile_phone = $("#mobile_phone").val();

            if(mobile_phone){
                checkPhone(mobile_phone);
            }else{
                $("#email_notice").html('');
                $("#email_verification").val(0);
            }

            $("#regName").val("");
            $("#email_yz").hide();
            $("#phone_yz").find(".tx_rm").show();

            $("#phone_yz").show();
            $("#code_mobile").show();

            $("form[name='formUser'] :input[name='register_type']").val(1);
            $("#registsubmit").attr("disabled", false);
        });
        $.divselect("#divselect","#passwd_quesetion");
        $.divselect("#divselect2","#passwd_quesetion2");
    });
</script>--}}
</body>
</html>


