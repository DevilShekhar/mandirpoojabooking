<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Temple Puja Booking</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: Georgia, "Times New Roman", serif;
        background: #f6ecdc;
        color: #4b2417;
    }
    .login-page {
        min-height: 100vh;
        padding: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(circle at 10% 20%,
                rgba(211, 139, 40, 0.12),
                transparent 30%),
            #f6ecdc;
    }
    .login-container {
        width: 100%;
        max-width: 1450px;
        min-height: 850px;
        display: grid;
        grid-template-columns: 1fr 0.78fr;
        overflow: hidden;
        border-radius: 28px;
        background: #f8f0e1;
        box-shadow:0 30px 80px rgba(70, 35, 15, 0.20);
    }
    .temple-section {
        position: relative;
        min-height: 850px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-image:
            linear-gradient(rgba(39, 24, 18, 0.20),
                rgba(39, 24, 18, 0.25)),
            url("/assets/img/temple-login-left-bg.png");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .temple-content {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 650px;
        text-align: center;
        color: #fff;
        padding: 50px;
    }
    .temple-logo {
        font-size: 42px;
        color: #f5bd4d;
        margin-bottom: 10px;
    }
    .temple-small-title {
        font-size: 15px;
        letter-spacing: 6px;
        text-transform: uppercase;
        color: #f7d17b;
        margin-bottom: 28px;
    }

    .temple-content h1 {
        font-size: 62px;
        line-height: 1.05;
        font-weight: 700;
        margin-bottom: 25px;
        text-shadow:0 4px 15px rgba(0, 0, 0, 0.3);
    }
    .temple-content h1 span {
        display: block;
        color: #f4bd52;
    }
    .gold-divider {
        width: 250px;
        height: 1px;
        margin: 0 auto 25px;
        background: linear-gradient(to right,
                transparent,
                #f3bd54,
                transparent);
    }
    .temple-description {
        max-width: 570px;
        margin: auto;
        color: rgba(255, 255, 255, 0.9);
        font-size: 18px;
        line-height: 1.7;
    }
    .features {
        display: flex;
        justify-content: center;
        gap: 45px;
        margin-top: 45px;
    }
    .feature {
        width: 120px;
        text-align: center;
    }
    .feature-circle {
        width: 75px;
        height: 75px;
        margin: auto auto 12px;
        border: 2px solid #f3c35f;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #f8d47c;
        background: rgba(0, 0, 0, 0.08);
    }
    .feature-title {
        color: #fff;
        font-size: 16px;
    }
    .login-section {
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(circle at top right,
                rgba(204, 125, 23, 0.08),
                transparent 35%),
            #fffaf1;
    }
    .login-box {
        width: 100%;
        max-width: 500px;
        padding: 15px 0px 0px 0px;
    }
    .brand {
        text-align: center;
        margin-bottom: 30px;
    }
    .brand-icon {
        font-size: 58px;
        color: #d66a08;
        line-height: 1;
        margin-bottom: 12px;
    }
    .brand h2 {
        font-size: 22px;
        color: #8f301b;
        margin-bottom: 8px;
    }
    .brand p {
        font-size: 13px;
        color: #b25432;
        letter-spacing: 1px;
    }
    .brand-divider {
        width: 190px;
        height: 1px;
        margin: 22px auto;
        background: linear-gradient(to right,
                transparent,
                #d98324,
                transparent);
    }
    .welcome {
        text-align: center;
        margin-bottom: 32px;
    }
    .welcome h1 {
        font-size: 42px;
        color: #422015;
        margin-bottom: 10px;
    }
    .welcome p {
        color: #75635a;
        font-size: 15px;
        line-height: 1.6;
    }
    .form-group {
        margin-bottom: 22px;
    }
    .form-group label {
        display: block;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #4a2b1e;
    }
    .input-wrapper {
        position: relative;
    }
    .input-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 19px;
    }
    .form-input {
        width: 100%;
        height: 62px;
        border: 1px solid #ddd3c7;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.65);
        padding: 0 20px 0 55px;
        font-family: Georgia, serif;
        font-size: 15px;
        color: #422015;
        outline: none;
        transition: 0.25s;
    }
    .form-input:focus {
        border-color: #c95b20;
        background: #fff;
        box-shadow:0 0 0 4px rgba(201, 91, 32, 0.08);
    }
    .form-input::placeholder {
        color: #aaa;
    }
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 15px 0 25px;
    }
    .remember {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 14px;
        color: #66554d;
    }
    .remember input {
        width: 20px;
        height: 20px;
        accent-color: #c9341d;
    }
    .forgot {
        color: #c9321d;
        font-size: 14px;
        text-decoration: none;
        font-weight: 600;
    }
    .forgot:hover {
        text-decoration: underline;
    }
    .login-button {
        width: 100%;
        height: 64px;
        border: none;
        border-radius: 14px;
        background:
            linear-gradient(135deg,
                #c52c1a,
                #d83b20);
        color: white;
        font-family: Georgia, serif;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(190, 50, 25, 0.22);
        transition: 0.3s;
    }
    .login-button:hover {
        transform: translateY(-2px);
        box-shadow:0 15px 30px rgba(190, 50, 25, 0.30);
    }
    .register-divider {
        display: flex;
        align-items: center;
        gap: 15px;
        margin: 32px 0 25px;
        color: #89776b;
        font-size: 14px;
    }
    .register-divider::before,
    .register-divider::after {
        content: "";
        height: 1px;
        flex: 1;
        background: #ddd2c5;
    }
    .register-button {
        height: 58px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #d73520;
        border-radius: 14px;
        color: #c63120;
        font-size: 16px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.3s;
        background: transparent;
    }
    .register-button:hover {
        background: #fff2e8;
    }
    .blessing {
        text-align: center;
        margin-top: 32px;
        color: #806f62;
        font-size: 13px;
        line-height: 1.6;
    }
    .blessing-icon {
        display: block;
        color: #e4a132;
        font-size: 25px;
        margin-bottom: 8px;
    }
    .error {
        color: #c62828;
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin-top: 6px;
    }
    .session-status {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 10px;
        background: #edf8ee;
        color: #39853e;
        font-family: Arial, sans-serif;
        font-size: 13px;
    }
    @media (max-width: 1100px) {
        .login-container {
            grid-template-columns: 1fr 0.9fr;
        }
        .login-section {
            padding: 40px;
        }
        .login-box {
            padding: 15px;
        }
        .temple-content h1 {
            font-size: 48px;
        }
        .features {
            gap: 20px;
        }
    }
    @media (max-width: 850px) {
        .login-page {
            padding: 0;
        }
        .login-container {
            min-height: 100vh;
            border-radius: 0;
            grid-template-columns: 1fr;
        }
        .temple-section {
            display: none;
        }
        .login-section {
            min-height: 100vh;
            padding: 30px 20px;
        }
        .login-box {
            max-width: 520px;
        }
    }
    @media (max-width: 480px) {
        .login-section {
            padding: 25px 15px;
        }
        .login-box {
            padding: 10px;
        }
        .brand h2 {
            font-size: 25px;
        }
        .welcome h1 {
            font-size: 32px;
        }
        .form-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        .login-button {
            height: 58px;
        }
    }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            <section class="temple-section">
                <div class="temple-content">
                    <div class="temple-logo">
                        ॐ
                    </div>
                    <div class="temple-small-title">
                        Temple Puja Booking
                    </div>
                    <h1>
                        Begin Your
                        <span>Sacred Journey</span>
                    </h1>
                    <div class="gold-divider"></div>
                    <p class="temple-description">
                        Book sacred pujas, participate in divine rituals
                        and receive blessings from your chosen temple.
                    </p>
                    <div class="features">
                        <div class="feature">
                            <div class="feature-circle">
                                🪔
                            </div>
                            <div class="feature-title">
                                Sacred<br>Pujas
                            </div>
                        </div>
                        <div class="feature">
                            <div class="feature-circle">
                                🙏
                            </div>
                            <div class="feature-title">
                                Divine<br>Blessings
                            </div>
                        </div>
                        <div class="feature">
                            <div class="feature-circle">
                               🌸
                            </div>
                            <div class="feature-title">
                                Prasad<br>Delivery
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="login-section">
                <div class="login-box">
                    <div class="brand">
                        <div class="brand-icon">
                            🛕
                        </div>
                        <h2>
                            Temple Puja Booking
                        </h2>
                        <p>
                            Faith • Pujas • Blessings • Always With You
                        </p>
                        <div class="brand-divider"></div>
                    </div>
                    <div class="welcome">
                        <h1>
                            Welcome Back
                        </h1>
                        <p>
                            Login to your account and continue
                            <br>
                            your spiritual journey.
                        </p>
                    </div>
                    <x-auth-session-status class="session-status" :status="session('status')" />
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">
                                Email Address
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    ✉
                                </span>
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                    class="form-input" placeholder="Enter your email address" required autofocus
                                    autocomplete="username">
                            </div>
                            @if ($errors->get('email'))
                            <div class="error">
                                {{ $errors->first('email') }}
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">
                                Password
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    🔒
                                </span>
                                <input id="password" type="password" name="password" class="form-input"
                                    placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                            @if ($errors->get('password'))
                            <div class="error">
                                {{ $errors->first('password') }}
                            </div>
                            @endif
                        </div>
                        <div class="form-options">
                            <label class="remember">
                                <input id="remember_me" type="checkbox" name="remember">
                                <span>
                                    Remember me
                                </span>
                            </label>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot">
                                Forgot Password?
                            </a>
                            @endif
                        </div>
                        <button type="submit" class="login-button">
                            Login & Continue &nbsp; →
                        </button>
                    </form>
                    @if (Route::has('register'))
                    <div class="register-divider">
                        New to our temple community?
                    </div>
                    <a href="{{ route('customer.register') }}" class="register-button">
                       Create Your Account
                    </a>
                    @endif
                </div>
            </section>
        </div>
    </div>
</body>
</html>