
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

                <div class="register-socials">
                    <a href="#" class="register-social-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M21.8 12.2c0-.7-.1-1.3-.2-1.9H12v3.6h5.5a4.7 4.7 0 0 1-2 3.1v2.6h3.2c1.9-1.8 3.1-4.4 3.1-7.4Z"/>
                            <path d="M12 22c2.8 0 5.2-.9 6.9-2.5l-3.2-2.6c-.9.6-2.1 1-3.7 1-2.8 0-5.1-1.9-5.9-4.4H2.8v2.7A10 10 0 0 0 12 22Z"/>
                            <path d="M6.1 13.5A6 6 0 0 1 5.8 12c0-.5.1-1 .3-1.5V7.8H2.8A10 10 0 0 0 2 12c0 1.5.4 3 1 4.2l3.1-2.7Z"/>
                            <path d="M12 6.1c1.5 0 2.8.5 3.9 1.5l2.9-2.9A10 10 0 0 0 2.8 7.8l3.3 2.7C6.9 8 9.2 6.1 12 6.1Z"/>
                        </svg>
                        Google
                    </a>

                    <a href="#" class="register-social-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.9.3-1.6 1.6-1.6h1.7V4.8c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.4-4 4.2V11H8v3h2.4v8h3.1Z"/>
                        </svg>
                        Facebook
                    </a>

                    <a href="#" class="register-social-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M22 5.8c-.7.3-1.5.5-2.3.6.8-.5 1.4-1.2 1.7-2.1-.8.5-1.6.8-2.5 1A4 4 0 0 0 12 8.3c0 .3 0 .6.1.9-3.3-.2-6.3-1.8-8.2-4.2-.4.6-.5 1.3-.5 2 0 1.4.7 2.7 1.9 3.5-.6 0-1.3-.2-1.8-.5v.1c0 2 1.4 3.6 3.2 4-.3.1-.7.1-1 .1-.2 0-.5 0-.7-.1.5 1.6 2 2.7 3.8 2.8A8 8 0 0 1 3 18.6 11.3 11.3 0 0 0 9.1 20c7.4 0 11.5-6.1 11.5-11.5v-.5c.8-.5 1.4-1.1 1.9-1.8Z"/>
                        </svg>
                        Twitter
                    </a>
                </div>

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
