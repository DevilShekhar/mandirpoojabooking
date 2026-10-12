<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Temple Manager Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #fff8f0;
            font-family: Arial, sans-serif;
        }

        .registration-wrapper {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 15px;
        }

        .registration-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0, 0, 0, .08);
        }

        .registration-header {
            background: linear-gradient(135deg, #a84300, #e97818);
            color: white;
            text-align: center;
            padding: 30px;
        }

        .registration-header h2 {
            font-weight: 700;
        }

        .registration-body {
            padding: 30px;
        }

        .section-title {
            color: #a84300;
            font-size: 19px;
            font-weight: 700;
            border-bottom: 1px solid #eee0d5;
            padding-bottom: 10px;
            margin: 25px 0 20px;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control,
        .form-select {
            min-height: 45px;
            border-radius: 7px;
        }

        .btn-register {
            background: #b84b08;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 7px;
            font-weight: 600;
        }

        .btn-register:hover {
            background: #8f3900;
            color: white;
        }

        .required {
            color: red;
        }
    </style>
</head>

<body>

<div class="registration-wrapper">
    <div class="registration-card">

        <div class="registration-header">
            <h2>Temple Manager Registration</h2>
            <p class="mb-0">
                Register your temple for admin approval.
            </p>
        </div>

        <div class="registration-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="templeRegistrationForm"
                  action="{{ route('temple.register.store') }}"
                  method="POST">

                @csrf

                <h5 class="section-title">1. Manager Details</h5>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            Full Name <span class="required">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">
                            Email Address <span class="required">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="mobile" class="form-label">
                            Mobile Number <span class="required">*</span>
                        </label>
                        <input type="tel"
                               name="mobile"
                               id="mobile"
                               class="form-control @error('mobile') is-invalid @enderror"
                               value="{{ old('mobile') }}"
                               maxlength="15"
                               required>
                        @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">
                            Password <span class="required">*</span>
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               minlength="8"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">
                            Confirm Password <span class="required">*</span>
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="form-control"
                               minlength="8"
                               required>
                    </div>

                </div>

                <h5 class="section-title">2. Temple Details</h5>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="temple_name" class="form-label">
                            Temple Name <span class="required">*</span>
                        </label>
                        <input type="text"
                               name="temple_name"
                               id="temple_name"
                               class="form-control @error('temple_name') is-invalid @enderror"
                               value="{{ old('temple_name') }}"
                               required>
                        @error('temple_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="location" class="form-label">
                            Temple Address <span class="required">*</span>
                        </label>
                        <input type="text"
                               name="location"
                               id="location"
                               class="form-control @error('location') is-invalid @enderror"
                               value="{{ old('location') }}"
                               required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="country_id" class="form-label">
                            Country <span class="required">*</span>
                        </label>
                        <select name="country_id"
                                id="country_id"
                                class="form-select @error('country_id') is-invalid @enderror"
                                required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}"
                                    @selected((string) old('country_id') === (string) $country->id)>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="state_id" class="form-label">
                            State <span class="required">*</span>
                        </label>
                        <select name="state_id"
                                id="state_id"
                                class="form-select @error('state_id') is-invalid @enderror"
                                required>
                            <option value="">Select State</option>
                        </select>
                        @error('state_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="district_id" class="form-label">
                            District <span class="required">*</span>
                        </label>
                        <select name="district_id"
                                id="district_id"
                                class="form-select @error('district_id') is-invalid @enderror"
                                required>
                            <option value="">Select District</option>
                        </select>
                        @error('district_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="city_id" class="form-label">
                            City <span class="required">*</span>
                        </label>
                        <select name="city_id"
                                id="city_id"
                                class="form-select @error('city_id') is-invalid @enderror"
                                required>
                            <option value="">Select City</option>
                        </select>
                        @error('city_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="alert alert-warning mt-4">
                    <strong>Pending Approval:</strong>
                    Your registration will be reviewed by the administrator
                    before your account is activated.
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                        Back to Home
                    </a>

                    <button type="submit"
                            class="btn btn-register"
                            id="submitBtn">
                        Submit Registration
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const country = document.getElementById('country_id');
    const state = document.getElementById('state_id');
    const district = document.getElementById('district_id');
    const city = document.getElementById('city_id');

    const oldCountry = @json(old('country_id'));
    const oldState = @json(old('state_id'));
    const oldDistrict = @json(old('district_id'));
    const oldCity = @json(old('city_id'));

    function resetSelect(select, label) {
        select.innerHTML = '';

        const option = document.createElement('option');
        option.value = '';
        option.textContent = label;

        select.appendChild(option);
    }

    async function loadOptions(url, select, label, selectedId = '') {
        resetSelect(select, label);
        select.disabled = true;

        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load location options.');
            }

            const items = await response.json();

            items.forEach(function (item) {
                const option = document.createElement('option');

                option.value = item.id;
                option.textContent = item.name;
                option.selected = String(item.id) === String(selectedId);

                select.appendChild(option);
            });
        } catch (error) {
            console.error(error);
        } finally {
            select.disabled = false;
        }
    }

    function statesUrl(countryId) {
        return @json(url('/temple-register/get-states')) + '/' + countryId;
    }

    function districtsUrl(stateId) {
        return @json(url('/temple-register/get-districts')) + '/' + stateId;
    }

    function citiesUrl(districtId) {
        return @json(url('/temple-register/get-cities')) + '/' + districtId;
    }

    country.addEventListener('change', async function () {
        resetSelect(state, 'Select State');
        resetSelect(district, 'Select District');
        resetSelect(city, 'Select City');

        if (this.value) {
            await loadOptions(
                statesUrl(this.value),
                state,
                'Select State'
            );
        }
    });

    state.addEventListener('change', async function () {
        resetSelect(district, 'Select District');
        resetSelect(city, 'Select City');

        if (this.value) {
            await loadOptions(
                districtsUrl(this.value),
                district,
                'Select District'
            );
        }
    });

    district.addEventListener('change', async function () {
        resetSelect(city, 'Select City');

        if (this.value) {
            await loadOptions(
                citiesUrl(this.value),
                city,
                'Select City'
            );
        }
    });

    async function restoreDropdowns() {
        if (oldCountry) {
            await loadOptions(
                statesUrl(oldCountry),
                state,
                'Select State',
                oldState
            );
        }

        if (oldState) {
            await loadOptions(
                districtsUrl(oldState),
                district,
                'Select District',
                oldDistrict
            );
        }

        if (oldDistrict) {
            await loadOptions(
                citiesUrl(oldDistrict),
                city,
                'Select City',
                oldCity
            );
        }
    }

    restoreDropdowns();

    document.getElementById('templeRegistrationForm')
        .addEventListener('submit', function () {
            const button = document.getElementById('submitBtn');

            if (this.checkValidity()) {
                button.disabled = true;
                button.textContent = 'Submitting...';
            }
        });
});
</script>

</body>
</html>
