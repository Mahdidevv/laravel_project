@extends('home.layouts.home')
@section('title')
    صفحه ورود
@endsection
@section('script')
    <script>
        let loginToken;
        $('#checkOTPForm').hide();
        $('#resendOTPButton').hide();
        $('#loginForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                type : "POST",
                url : '{{ url(route('login')) }}',
                data : {'_token': '{{csrf_token()}}','cellphone': $('#userCellphone').val()},
                datatype:   "json",
                success:function (data) {
                    loginToken = data.login_token;
                    $('#loginForm').fadeOut();
                    $('#checkOTPForm').fadeIn();
                    $('#checkOTPForm').submit(function (event) {
                        event.preventDefault();
                        $.ajax({
                            type : "POST",
                            url : '{{ url(route('check-otp')) }}',
                            data : {'_token': '{{csrf_token()}}','otp': $('#OTPInput').val(),'login_token': loginToken},
                            datatype:   "json",
                            success:function (data) {
                                $(location).attr('href',"{{route('index')}}")
                            },error:function (data) {
                                console.log(data)
                            }
                        });
                    });
                    timer();
                },error:function (data) {
                    console.log(data)
                }
            });
        });
        function timer() {
            let time = "0:30";
            let interval = setInterval(function() {
                let countdown = time.split(':');
                let minutes = parseInt(countdown[0], 10);
                let seconds = parseInt(countdown[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0) {
                    clearInterval(interval);
                    $('#resendOTPTime').hide();
                    $('#resendOTPButton').fadeIn();
                };
                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                //minutes = (minutes < 10) ?  minutes : minutes;
                $('#resendOTPTime').html(minutes + ':' + seconds);
                time = minutes + ':' + seconds;
            }, 1000);
        }
        function resendOTPCode()
        {
            $.ajax({
                type : "POST",
                url : '{{ url(route('resend-otp')) }}',
                data : {'_token': '{{csrf_token()}}','login_token': loginToken,'otp':$('#OTPInput').val()},
                datatype:"json",
                success:function (data) {
                    loginToken = data.login_token;
                    $('#resendOTPButton').fadeOut();
                    $('#resendOTPTime').fadeIn();
                    $('#checkOTPForm').submit(function (event) {
                        event.preventDefault();
                        $.ajax({
                            type : "POST",
                            url : '{{ url(route('check-otp')) }}',
                            data : {'_token': '{{csrf_token()}}','otp': $('#OTPInput').val(),'login_token': loginToken},
                            datatype:   "json",
                            success:function (data) {
                                $(location).attr('href',"{{route('index')}}")
                            },error:function (data) {
                                console.log(data)
                            }
                        });
                    });
                    timer();
                },error:function (data) {
                    console.log(data)
                }
            });
        }

    </script>
@endsection
@section('content')
    <div class="breadcrumb-area pt-35 pb-35 bg-gray" style="direction: rtl;">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="{{route('index')}}">صفحه ای اصلی</a>
                    </li>
                    <li class="active"> ورود</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="login-register-area pt-100 pb-100" style="direction: rtl;">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                    <div class="login-register-wrapper">
                        <div class="login-register-tab-list nav">
                            <a class="active" data-toggle="tab" href="#lg1">
                                <h4> ورود </h4>
                            </a>
                        </div>
                        <div class="tab-content">

                            <div id="lg1" class="tab-pane active">
                                <div class="login-form-container">
                                    <div class="login-register-form">
                                        {{--Send OTP--}}
                                        <form id="loginForm">
                                            <input id="userCellphone" type="text" placeholder="شماره همراه">
                                            <div class="input-error-validation">
                                                <strong>
                                                </strong>
                                            </div>
                                            <div class="button-box d-flex justify-content-center">
                                                <button type="submit">ارسال</button>
                                            </div>
                                        </form>
                                        {{--Check OTP--}}
                                        <form id="checkOTPForm">
                                            <input id="OTPInput" type="text" placeholder="رمز یکبار مصرف">
                                            <div class="input-error-validation">
                                                <strong>
                                                </strong>
                                            </div>
                                            <div class="button-box d-flex justify-content-center">
                                                <button type="submit">ورود</button>
                                                <div>
                                                    <button onclick="resendOTPCode()" id="resendOTPButton" type="submit">ارسال مجدد</button>
                                                    <span id="resendOTPTime"></span>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
