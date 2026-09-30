<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Temple Puja Booking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html,
        body {
            width: 100%;
            min-height: 100%;
        }
        body {
            font-family: Georgia, "Times New Roman", serif;
            background: #f6ecdc;
            color: #4b2417;
            overflow-x: hidden;
        }
        .login-page {
            width: 100%;
            min-height: 100vh;
            height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(
                    circle at 10% 20%,
                    rgba(211, 139, 40, 0.12),
                    transparent 30%
                ),
                #f6ecdc;
            overflow: hidden;
        }
        .login-container {
            width: 100%;
            max-width: 1450px;
            height: calc(100vh - 40px);
            max-height: 850px;
            display: grid;
            grid-template-columns: 1fr 0.78fr;
            overflow: hidden;
            border-radius: 28px;
            background: #f8f0e1;
            box-shadow: 0 30px 80px rgba(70, 35, 15, 0.20);
        }
        .temple-section {
            position: relative;
            min-height: 0;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image:
                linear-gradient(
                    rgba(39, 24, 18, 0.20),
                    rgba(39, 24, 18, 0.25)
                ),
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
            padding: 35px;
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
            margin-bottom: 24px;
        }
        .temple-content h1 {
            font-size: clamp(42px, 4vw, 62px);
            line-height: 1.05;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        .temple-content h1 span {
            display: block;
            color: #f4bd52;
        }
        .gold-divider {
            width: 250px;
            height: 1px;
            margin: 0 auto 20px;
            background: linear-gradient(
                to right,
                transparent,
                #f3bd54,
                transparent
            );
        }
        .temple-description {
            max-width: 570px;
            margin: auto;
            color: rgba(255, 255, 255, 0.9);
            font-size: 17px;
            line-height: 1.6;
        }
        .features {
            display: flex;
            justify-content: center;
            gap: 35px;
            margin-top: 30px;
        }
        .feature {
            width: 110px;
            text-align: center;
        }
        .feature-circle {
            width: 68px;
            height: 68px;
            margin: auto auto 10px;
            border: 2px solid #f3c35f;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #f8d47c;
            background: rgba(0, 0, 0, 0.08);
        }
        .feature-title {
            color: #fff;
            font-size: 15px;
        }
        .login-section {
            height: 100%;
            min-height: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px 40px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(204, 125, 23, 0.08),
                    transparent 35%
                ),
                #fffaf1;
            overflow: hidden;
        }
        .login-box {
            width: 100%;
            max-width: 500px;
            padding: 0;
        }
        .brand {
            text-align: center;
            margin-bottom: 20px;
        }
        .brand-icon {
            font-size: 50px;
            color: #d66a08;
            line-height: 1;
            margin-bottom: 8px;
        }
        .brand h2 {
            font-size: 22px;
            color: #8f301b;
            margin-bottom: 6px;
        }
        .brand p {
            font-size: 13px;
            color: #b25432;
            letter-spacing: 1px;
        }
        .brand-divider {
            width: 190px;
            height: 1px;
            margin: 15px auto;
            background: linear-gradient(
                to right,
                transparent,
                #d98324,
                transparent
            );
        }
        .welcome {
            text-align: center;
            margin-bottom: 22px;
        }
        .welcome h1 {
            font-size: clamp(32px, 3vw, 42px);
            color: #422015;
            margin-bottom: 8px;
        }
        .welcome p {
            color: #75635a;
            font-size: 15px;
            line-height: 1.5;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 7px;
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
            height: 56px;
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
            box-shadow: 0 0 0 4px rgba(201, 91, 32, 0.08);
        }
        .form-input::placeholder {
            color: #aaa;
        }
        .login-button {
            width: 100%;
            height: 58px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #c52c1a, #d83b20);
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
            box-shadow: 0 15px 30px rgba(190, 50, 25, 0.30);
        }
        .register-divider {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 20px 0 16px;
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
            height: 52px;
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
            margin-top: 18px;
            color: #806f62;
            font-size: 13px;
            line-height: 1.5;
        }
        .blessing-icon {
            display: block;
            color: #e4a132;
            font-size: 23px;
            margin-bottom: 5px;
        }
        .error {
            color: #c62828;
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin-top: 5px;
        }
        .session-status {
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            background: #edf8ee;
            color: #39853e;
            font-family: Arial, sans-serif;
            font-size: 13px;
            text-align:center;
        }
        .otp-info {
            text-align: center;
            color: #75635a;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 18px;
        }
        .otp-input {
            text-align: center;
            letter-spacing: 8px;
            font-size: 22px;
            font-weight: 700;
            padding-left: 20px;
        }
        .otp-timer {
            margin-top: 6px;
            color: #c63120;
            font-size: 14px;
            font-weight: 600;
        }
        .otp-timer span {
            font-size: 16px;
            font-weight: 700;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            color: #c63120;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        @media (max-height: 800px) and (min-width: 851px) {
            .login-page {
                padding: 12px;
            }
            .login-container {
                height: calc(100vh - 24px);
            }
            .temple-content {
                padding: 20px;
            }
            .temple-logo {
                font-size: 34px;
            }
            .temple-small-title {
                margin-bottom: 16px;
            }
            .temple-content h1 {
                font-size: 46px;
                margin-bottom: 15px;
            }
            .temple-description {
                font-size: 15px;
            }
            .features {
                margin-top: 20px;
            }
            .feature-circle {
                width: 60px;
                height: 60px;
                font-size: 26px;
            }
            .login-section {
                padding: 18px 30px;
            }
            .brand {
                margin-bottom: 15px;
            }
            .brand-icon {
                font-size: 42px;
                margin-bottom: 5px;
            }
            .brand-divider {
                margin: 10px auto;
            }
            .welcome {
                margin-bottom: 16px;
            }
            .welcome h1 {
                font-size: 34px;
            }
            .form-group {
                margin-bottom: 12px;
            }
            .form-input {
                height: 50px;
            }
            .login-button {
                height: 52px;
            }
            .register-divider {
                margin: 14px 0 12px;
            }
            .register-button {
                height: 46px;
            }
            .blessing {
                margin-top: 12px;
            }
        }
        @media (max-width: 1100px) {
            .login-page {
                padding: 15px;
            }
            .login-container {
                height: calc(100vh - 30px);
                grid-template-columns: 1fr 0.9fr;
            }
            .login-section {
                padding: 25px;
            }
            .login-box {
                padding: 0;
            }
            .temple-content {
                padding: 30px;
            }
            .temple-content h1 {
                font-size: 48px;
            }
            .features {
                gap: 20px;
            }
        }
        @media (max-width: 850px) {
            html,
            body {
                min-height: 100%;
            }
            body {
                overflow-y: auto;
            }
            .login-page {
                min-height: 100vh;
                height: auto;
                padding: 0;
                overflow: visible;
            }
            .login-container {
                min-height: 100vh;
                height: auto;
                max-height: none;
                border-radius: 0;
                grid-template-columns: 1fr;
                overflow: visible;
            }
            .temple-section {
                display: none;
            }
            .login-section {
                min-height: 100vh;
                height: auto;
                padding: 30px 20px;
                overflow: visible;
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
            .login-button {
                height: 58px;
            }
            .form-input {
                height: 54px;
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
                    @if (!session('forgot_password_otp') && !session('forgot_password_otp_verified'))
                        <div class="welcome">
                            <h1>
                                Forgot Password?
                            </h1>
                            <p>
                                Enter your registered email address
                                <br>
                                to receive a password reset OTP.
                            </p>
                        </div>
                        <x-auth-session-status class="session-status":status="session('status')"/>
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">
                                    Email Address
                                </label>
                                <div class="input-wrapper">
                                    <span class="input-icon">
                                        ✉
                                    </span>
                                    <input id="email"type="email" name="email" value="{{ old('email', session('forgot_password_email')) }}"
                                        class="form-input" placeholder="Enter your email address" required autofocus autocomplete="email">
                                </div>
                                @if ($errors->get('email'))
                                    <div class="error">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="login-button">
                                Send OTP &nbsp; →
                            </button>
                        </form>
                        <div class="register-divider">
                            Remember your password?
                        </div>
                        <a href="{{ route('login') }}" class="register-button">
                            ← Back to Login
                        </a>
                    @elseif (!session('forgot_password_otp_verified'))
                        <div class="welcome">
                            <h1>
                                Verify OTP
                            </h1>
                            <p>
                                Enter the 6-digit OTP sent to your email
                                <br>
                                to verify your account.
                            </p>
                        </div>
                        <x-auth-session-status class="session-status":status="session('status')"/>
                        <div class="otp-info">
                            OTP has been sent to
                            <strong>{{ session('forgot_password_email') }}</strong>
                            <div class="otp-timer">
                                OTP expires in
                                <span id="otp-countdown">10:00</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('password.verify.otp') }}">
                            @csrf
                            <div class="form-group">
                                <label for="otp">
                                    Enter OTP
                                </label>
                                <div class="input-wrapper">
                                    <span class="input-icon">
                                        🔐
                                    </span>
                                    <input id="otp" type="text" name="otp" class="form-input otp-input" placeholder="000000" maxlength="6" inputmode="numeric"  autocomplete="one-time-code" required  autofocus>
                                </div>
                                @if ($errors->get('otp'))
                                    <div class="error">
                                        {{ $errors->first('otp') }}
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="login-button" id="verify-otp-button" >
                                Verify OTP &nbsp; →
                            </button>
                        </form>                       
                    @else
                        <div class="welcome">
                            <h1>
                                Create New Password
                            </h1>
                            <p>
                                Your OTP has been verified successfully.
                                <br>
                                Enter your new password below.
                            </p>
                        </div>
                        <x-auth-session-status class="session-status" :status="session('status')"/>
                        <form method="POST" action="{{ route('password.reset') }}">
                            @csrf
                            <div class="form-group">
                                <label for="password">
                                    New Password
                                </label>
                                <div class="input-wrapper">
                                    <span class="input-icon">
                                        🔒
                                    </span>
                                    <input id="password" type="password" name="password" class="form-input"  placeholder="Enter new password" required autofocus autocomplete="new-password"  >
                                </div>
                                @if ($errors->get('password'))
                                    <div class="error">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">
                                    Confirm Password
                                </label>
                                <div class="input-wrapper">
                                    <span class="input-icon">
                                        🔒
                                    </span>
                                    <input id="password_confirmation" type="password"  name="password_confirmation" class="form-input" placeholder="Confirm new password" required autocomplete="new-password">
                                </div>
                            </div>
                            <button type="submit" class="login-button">
                                Reset Password &nbsp; →
                            </button>
                        </form>
                    @endif
                    <div class="blessing">
                        May your spiritual journey always be blessed.
                    </div>
                </div>
            </section>
        </div>
    </div>
    @if (session('forgot_password_otp') && !session('forgot_password_otp_verified'))
        <script>
            const otpExpiresAt = Number(@json(session('forgot_password_otp_expires_at', 0)));
            const countdownElement = document.getElementById('otp-countdown');
            const verifyOtpButton = document.getElementById('verify-otp-button');
            if (otpExpiresAt > 0 && countdownElement) {
                const expiryTime = otpExpiresAt * 1000;
                const updateOtpCountdown = () => {
                    const currentTime = Date.now();
                    const remainingTime = expiryTime - currentTime;
                    if (remainingTime <= 0) {
                        countdownElement.textContent = '00:00';
                        if (verifyOtpButton) {
                            verifyOtpButton.disabled = true;
                            verifyOtpButton.style.opacity = '0.6';
                            verifyOtpButton.style.cursor = 'not-allowed';
                        }
                        clearInterval(otpCountdownInterval);
                        return;
                    }
                    const totalSeconds = Math.floor(remainingTime / 1000);
                    const minutes = Math.floor(totalSeconds / 60);
                    const seconds = totalSeconds % 60;
                    countdownElement.textContent =
                        String(minutes).padStart(2, '0') +
                        ':' +
                        String(seconds).padStart(2, '0');
                };
                updateOtpCountdown();
                const otpCountdownInterval = setInterval(
                    updateOtpCountdown,
                    1000
                );
            }
        </script>
    @endif
</body>
</html>