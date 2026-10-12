
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Registration | Puja Booking</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        :root {
            --primary: #ea0505;
            --primary-hover: #c90000;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: #f6f7fb;
            font-family: Arial, sans-serif;
        }

        .registration-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 35px 15px;
        }

        .registration-card {
            width: 100%;
            max-width: 1000px;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .08);
        }

        .registration-info {
            height: 100%;
            min-height: 700px;
            padding: 45px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
            background: linear-gradient(145deg, #ea0505, #990000);
        }

        .brand-icon {
            width: 68px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, .16);
            font-size: 30px;
            margin-bottom: 24px;
        }

        .registration-info h2 {
            font-size: 30px;
            font-weight: 700;
            line-height: 1.4;
        }

        .registration-info p {
            line-height: 1.8;
            color: rgba(255, 255, 255, .9);
        }

        .benefit-list {
            list-style: none;
            padding: 0;
            margin-top: 24px;
        }

        .benefit-list li {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
        }

        .registration-form {
            padding: 40px 45px;
        }

        .form-heading {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-subheading {
            color: #777;
            font-size: 14px;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            min-height: 46px;
            border: 1px solid #e2e2e2;
            border-radius: 8px;
            font-size: 14px;
            box-shadow: none !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
        }

        .input-group .form-control {
            border-radius: 8px 0 0 8px;
        }

        .password-toggle {
            border: 1px solid #e2e2e2;
            border-left: 0;
            background: #fff;
            padding: 0 15px;
            border-radius: 0 8px 8px 0;
            color: #666;
        }

        .btn-register {
            width: 100%;
            min-height: 48px;
            border: none;
            border-radius: 8px;
            background: var(--primary);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
        }

        .btn-register:hover {
            background: var(--primary-hover);
            color: #fff;
        }

        .login-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .form-footer {
            margin-top: 22px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }

        @media (max-width: 767px) {
            .registration-info {
                min-height: auto;
                padding: 30px 25px;
            }

            .registration-info h2 {
                font-size: 25px;
            }

            .registration-form {
                padding: 30px 22px;
            }
        }
    </style>
</head>

<body>

<div class="registration-wrapper">
    <div class="registration-card">

        <div class="row g-0">

            {{-- Left information panel --}}
            <div class="col-lg-5">
                <div class="registration-info">

                    <div class="brand-icon">
                        <i class="fa fa-om"></i>
                    </div>

                    <h2>Join Our Spiritual Community</h2>

                    <p>
                        Create your customer account to book pujas,
                        explore temples, and manage your bookings online.
                    </p>

                    <ul class="benefit-list">
                        <li>
                            <i class="fa fa-check-circle"></i>
                            <span>Book pujas online</span>
                        </li>

                        <li>
                            <i class="fa fa-check-circle"></i>
                            <span>Manage your bookings</span>
                        </li>

                        <li>
                            <i class="fa fa-check-circle"></i>
                            <span>Receive booking updates</span>
                        </li>

                        <li>
                            <i class="fa fa-check-circle"></i>
                            <span>Stay connected with temples</span>
                        </li>
                    </ul>

                </div>
            </div>

            {{-- Registration form --}}
            <div class="col-lg-7">
                <div class="registration-form">

                    <h1 class="form-heading">Create Account</h1>

                    <p class="form-subheading">
                        Fill in your details to register as a customer.
                    </p>

                    {{-- Success message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Validation errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('customer.register.store') }}"
                          method="POST"
                          id="customerRegistrationForm">

                        @csrf

                        {{-- Full name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Full Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Enter your full name"
                                   value="{{ old('name') }}"
                                   maxlength="255"
                                   autocomplete="name"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>

                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Enter your email address"
                                   value="{{ old('email') }}"
                                   maxlength="255"
                                   autocomplete="email"
                                   required>

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                Phone Number <span class="text-danger">*</span>
                            </label>

                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="Enter your phone number"
                                   value="{{ old('phone') }}"
                                   maxlength="20"
                                   autocomplete="tel"
                                   required>

                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">

                            {{-- Gender --}}
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">
                                    Gender
                                </label>

                                <select name="gender"
                                        id="gender"
                                        class="form-select @error('gender') is-invalid @enderror">

                                    <option value="">Select Gender</option>

                                    <option value="Male"
                                        {{ old('gender') === 'Male' ? 'selected' : '' }}>
                                        Male
                                    </option>

                                    <option value="Female"
                                        {{ old('gender') === 'Female' ? 'selected' : '' }}>
                                        Female
                                    </option>

                                    <option value="Other"
                                        {{ old('gender') === 'Other' ? 'selected' : '' }}>
                                        Other
                                    </option>
                                </select>

                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Birth date --}}
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">
                                    Date of Birth
                                </label>

                                <input type="date"
                                       name="birth_date"
                                       id="birth_date"
                                       class="form-control @error('birth_date') is-invalid @enderror"
                                       value="{{ old('birth_date') }}"
                                       max="{{ now()->subDay()->format('Y-m-d') }}">

                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Create a password"
                                       autocomplete="new-password"
                                       required>

                                <button type="button"
                                        class="password-toggle"
                                        onclick="togglePassword('password', this)"
                                        aria-label="Show password">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>

                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirm password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Confirm Password
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control"
                                       placeholder="Confirm your password"
                                       autocomplete="new-password"
                                       required>

                                <button type="button"
                                        class="password-toggle"
                                        onclick="togglePassword('password_confirmation', this)"
                                        aria-label="Show confirm password">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>

                            <div id="passwordMismatch"
                                 class="text-danger small mt-1"
                                 style="display:none;">
                                Passwords do not match.
                            </div>
                        </div>

                        {{-- Register --}}
                        <button type="submit" class="btn btn-register">
                            <i class="fa fa-user-plus me-2"></i>
                            Create Customer Account
                        </button>

                    </form>

                    <div class="form-footer">
                        Already have an account?
                        <a href="{{ route('login') }}" class="login-link">
                            Login Here
                        </a>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ url('/') }}"
                           class="text-muted text-decoration-none small">
                            <i class="fa fa-arrow-left me-1"></i>
                            Back to Home
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
            button.setAttribute('aria-label', 'Hide password');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
            button.setAttribute('aria-label', 'Show password');
        }
    }

    const form = document.getElementById('customerRegistrationForm');
    const password = document.getElementById('password');
    const confirmation = document.getElementById('password_confirmation');
    const mismatch = document.getElementById('passwordMismatch');

    function checkPasswords() {
        const different =
            confirmation.value !== '' &&
            password.value !== confirmation.value;

        confirmation.setCustomValidity(
            different ? 'Passwords do not match.' : ''
        );

        mismatch.style.display = different ? 'block' : 'none';

        return !different;
    }

    password.addEventListener('input', checkPasswords);
    confirmation.addEventListener('input', checkPasswords);

    form.addEventListener('submit', function (event) {
        if (!checkPasswords()) {
            event.preventDefault();
            confirmation.reportValidity();
        }
    });
</script>

</body>
</html>
