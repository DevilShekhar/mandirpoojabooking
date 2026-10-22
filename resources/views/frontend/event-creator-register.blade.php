
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Creator Registration</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 15px;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #252b36;
        }

        .register-wrapper {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            padding: 32px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }

        .register-header {
            margin-bottom: 28px;
            text-align: center;
        }

        .register-header h1 {
            margin: 0 0 10px;
            font-size: 27px;
            color: #222b45;
        }

        .register-header p {
            margin: 0;
            color: #72798a;
            font-size: 14px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 19px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .required {
            color: #dc3545;
        }

        input,
        select {
            display: block;
            width: 100%;
            min-height: 45px;
            padding: 11px 12px;
            border: 1px solid #dce1eb;
            border-radius: 6px;
            background: #ffffff;
            color: #252b36;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #4263eb;
            box-shadow: 0 0 0 3px rgba(66, 99, 235, 0.10);
        }

        .field-error {
            margin-top: 6px;
            color: #dc3545;
            font-size: 12px;
        }

        .alert {
            margin-bottom: 22px;
            padding: 13px 15px;
            border-radius: 6px;
            font-size: 14px;
            line-height: 1.6;
        }

        .alert-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .alert-error {
            background: #f8d7da;
            color: #842029;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .submit-button {
            display: block;
            width: 100%;
            min-height: 47px;
            margin-top: 8px;
            border: none;
            border-radius: 6px;
            background: #4263eb;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .submit-button:hover {
            background: #304fc4;
        }

        .register-footer {
            margin-top: 20px;
            text-align: center;
            color: #72798a;
            font-size: 13px;
            line-height: 1.6;
        }

        @media (max-width: 520px) {
            body {
                padding: 20px 12px;
            }

            .register-wrapper {
                padding: 22px 18px;
            }

            .register-header h1 {
                font-size: 23px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="register-header">
            <h1>Event Creator Registration</h1>
            <p>Create your account to register and manage Sanatan events.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('event-creator.register.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name <span class="required">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter your full name"
                    maxlength="255"
                    autocomplete="name"
                    required
                >
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        maxlength="255"
                        autocomplete="email"
                        required
                    >
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number <span class="required">*</span></label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="Enter phone number"
                        maxlength="20"
                        autocomplete="tel"
                        required
                    >
                    @error('phone')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="birth_date">Date of Birth</label>
                    <input
                        type="date"
                        id="birth_date"
                        name="birth_date"
                        value="{{ old('birth_date') }}"
                        max="{{ now()->subDay()->format('Y-m-d') }}"
                    >
                    @error('birth_date')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimum 8 characters"
                        minlength="8"
                        autocomplete="new-password"
                        required
                    >
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Re-enter password"
                        minlength="8"
                        autocomplete="new-password"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="submit-button">
                Create Event Creator Account
            </button>
        </form>

        <div class="register-footer">
            Your account will be assigned the Event Creator role automatically.
        </div>
    </div>
</body>
</html>
