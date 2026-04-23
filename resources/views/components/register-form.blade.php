
<section class="register-page">
    <div class="container">
        <div class="register-card" data-reveal="zoom" data-delay="0">
            <div class="register-logo-wrap">
                <img src="{{ asset('images/logo/MediConnect.png') }}" alt="MediConnect Logo" class="register-logo-img">
            </div>

            <form class="register-form" method="POST" action="{{ route('register.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}">
                    @error('full_name')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}">
                    @error('email')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}">
                    @error('phone')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <div class="gender-group">
                        <label class="radio-item">
                            <input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                            <span>Male</span>
                        </label>

                        <label class="radio-item">
                            <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                            <span>Female</span>
                        </label>
                    </div>
                    @error('gender')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
    <label for="province">Province / City</label>
    <select id="province" name="province">
        <option value="">-- Select province/city --</option>
        @foreach($provinces as $province)
            <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>
                {{ $province }}
            </option>
        @endforeach
    </select>
    @error('province')
        <small class="form-error">{{ $message }}</small>
    @enderror
</div>

        <div class="form-group">
            <label for="district">District</label>
            <select id="district" name="district">
                <option value="">-- Select district --</option>
            </select>
            @error('district')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="ward">Ward</label>
            <select id="ward" name="ward">
                <option value="">-- Select ward --</option>
            </select>
            @error('ward')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="address_detail">Address Detail</label>
            <input id="address_detail" name="address_detail" type="text" value="{{ old('address_detail') }}">
            @error('address_detail')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input id="dob" name="dob" type="date" value="{{ old('dob') }}">
                    @error('dob')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password">
                    @error('password')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password">
                </div>

                <button type="submit" class="register-submit-btn">Register an account</button>
                <p class="register-login-text">
                    Already have an account?
                    <a href="{{ route('login') }}">Log in</a>
                </p>
            </form>
        </div>
    </div>
</section>

{{-- JS Drop Down --}}
<script>
    const locations = @json($locations);

    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    const oldDistrict = @json(old('district'));
    const oldWard = @json(old('ward'));

    function resetSelect(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
    }

    function populateDistricts(province, selectedDistrict = '') {
        resetSelect(districtSelect, '-- Select district --');
        resetSelect(wardSelect, '-- Select ward --');

        if (!province || !locations[province]) return;

        const districts = Object.keys(locations[province]);

        districts.forEach(district => {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;

            if (district === selectedDistrict) {
                option.selected = true;
            }

            districtSelect.appendChild(option);
        });

        if (selectedDistrict) {
            populateWards(province, selectedDistrict, oldWard);
        }
    }

    function populateWards(province, district, selectedWard = '') {
        resetSelect(wardSelect, '-- Select ward --');

        if (!province || !district || !locations[province] || !locations[province][district]) return;

        const wards = locations[province][district];

        wards.forEach(ward => {
            const option = document.createElement('option');
            option.value = ward;
            option.textContent = ward;

            if (ward === selectedWard) {
                option.selected = true;
            }

            wardSelect.appendChild(option);
        });
    }

    provinceSelect.addEventListener('change', function () {
        populateDistricts(this.value);
    });

    districtSelect.addEventListener('change', function () {
        populateWards(provinceSelect.value, this.value);
    });

    document.addEventListener('DOMContentLoaded', function () {
        const selectedProvince = provinceSelect.value;

        if (selectedProvince) {
            populateDistricts(selectedProvince, oldDistrict);
        }
    });
</script>
