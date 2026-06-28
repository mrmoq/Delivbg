<!doctype html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول | BASMA GRADS</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <link href="{{ asset('assets/css/bootstrap.rtl.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.rtl.css') }}" rel="stylesheet">
</head>
<body class="auth-body-bg">
<div class="auth-page-wrapper pt-5">
    <div class="auth-one-bg-position auth-one-bg" style="background: linear-gradient(135deg, #182e65, #405189);"></div>
    <div class="auth-page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">BASMA GRADS</h5>
                                <p class="text-muted">تسجيل الدخول إلى لوحة التحكم</p>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">{{ $errors->first() }}</div>
                            @endif
                            <form method="POST" action="{{ route('admin.login.submit') }}" class="p-2">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">كلمة المرور</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">تذكرني</label>
                                </div>
                                <button class="btn btn-primary w-100" type="submit">دخول</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/bootstrap.min.js') }}"></script>
</body>
</html>
