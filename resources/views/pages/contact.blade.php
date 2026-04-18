@extends('layouts.app')

@section('title', 'Liên hệ - MediConnect')

@section('content')
<section class="contact-page-hero">
    <div class="contact-page-bg contact-page-bg-one"></div>
    <div class="contact-page-bg contact-page-bg-two"></div>

    <div class="container contact-page-hero-inner">
        <div class="contact-page-copy reveal-up delay-2">
            <div class="contact-page-badges reveal-up delay-3">
                <span>Hỗ trợ bệnh nhân 24/7</span>
                <span>Đội ngũ phản hồi nhanh</span>
                <span>Kết nối chăm sóc tin cậy</span>
            </div>

            <h1 class="contact-page-title reveal-up delay-4">
                Liên hệ với <span>MediConnect</span> để được hỗ trợ nhanh chóng và đúng nhu cầu.
            </h1>

            <p class="contact-page-lead reveal-up delay-5">
                Dù bạn cần tư vấn đặt lịch khám, hỗ trợ tài khoản, tìm bác sĩ phù hợp hay giải đáp thông tin dịch vụ,
                đội ngũ MediConnect luôn sẵn sàng đồng hành cùng bạn với trải nghiệm thân thiện, chuyên nghiệp và rõ ràng.
            </p>

            <p class="contact-page-sub reveal-up delay-5">
                Chúng tôi ưu tiên phản hồi nhanh cho các yêu cầu liên quan đến lịch hẹn, thông tin chuyên khoa,
                hỗ trợ người dùng và hợp tác với bác sĩ hoặc cơ sở y tế.
            </p>

            <div class="contact-page-actions reveal-up delay-6">
                <a href="#contact-form" class="btn btn-primary">Gửi yêu cầu hỗ trợ</a>
                <a href="{{ route('doctors.index') }}" class="btn btn-outline">Tìm bác sĩ ngay</a>
            </div>

            <div class="contact-page-stats reveal-up delay-6">
                <div class="contact-page-stat-card">
                    <h3>24/7</h3>
                    <p>Tiếp nhận yêu cầu hỗ trợ từ người bệnh và bác sĩ.</p>
                </div>
                <div class="contact-page-stat-card">
                    <h3>15m</h3>
                    <p>Ưu tiên phản hồi nhanh cho các câu hỏi khẩn về lịch hẹn.</p>
                </div>
                <div class="contact-page-stat-card">
                    <h3>100%</h3>
                    <p>Tập trung vào trải nghiệm chăm sóc rõ ràng và an tâm.</p>
                </div>
            </div>
        </div>

        <div class="contact-page-visual reveal-up delay-4">
            <div class="contact-page-visual-card">
                <div class="contact-page-visual-top">

                <div class="contact-page-quick-list">
                    <div class="contact-page-quick-item">
                        <strong>Hotline ưu tiên</strong>
                        <p>1900 115 115</p>
                    </div>
                    <div class="contact-page-quick-item">
                        <strong>Email hỗ trợ</strong>
                        <p>mediconnect@gmail.com</p>
                    </div>
                    <div class="contact-page-quick-item">
                        <strong>Thời gian làm việc</strong>
                        <p>Thứ 2 - Chủ nhật, 7:00 - 22:00</p>
                    </div>
                    <div class="contact-page-quick-item">
                        <strong>Địa chỉ</strong>
                        <p>123 Nguyễn Văn Cừ, Ninh Kiều, Cần Thơ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-support">
    <div class="container contact-support-inner">
        <div class="services-page-section-head reveal-up delay-2">
            <span class="contact-page-label">Kênh hỗ trợ</span>
            <h2>Mỗi nhu cầu đều có một đầu mối liên hệ phù hợp</h2>
            <p>
                Chúng tôi thiết kế phần liên hệ theo đúng tinh thần MediConnect: rõ ràng, dễ tiếp cận và hữu ích cho cả bệnh nhân lẫn bác sĩ.
            </p>
        </div>

        <div class="contact-support-grid">
            <article class="contact-support-card reveal-up delay-2">
                <div class="contact-support-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.3 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.5 21 3 13.5 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.3 1l-2.2 2.2Z"/></svg>
                </div>
                <h3>Hotline hỗ trợ</h3>
                <p>Gọi trực tiếp để được hướng dẫn nhanh về lịch khám, tài khoản hoặc dịch vụ.</p>
                <a href="tel:1900115115">1900 115 115</a>
            </article>

            <article class="contact-support-card reveal-up delay-3">
                <div class="contact-support-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 5h16a2 2 0 0 1 2 2v.4l-10 6.25L2 7.4V7a2 2 0 0 1 2-2Zm18 4.75-9.47 5.92a1 1 0 0 1-1.06 0L2 9.75V17a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9.75Z"/></svg>
                </div>
                <h3>Email liên hệ</h3>
                <p>Phù hợp cho các yêu cầu chi tiết, hợp tác, góp ý hoặc hỗ trợ ngoài giờ cao điểm.</p>
                <a href="mailto:mediconnect@gmail.com">mediconnect@gmail.com</a>
            </article>

            <article class="contact-support-card reveal-up delay-4">
                <div class="contact-support-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.1 7 13 7 13s7-7.9 7-13c0-3.9-3.1-7-7-7Zm0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5Z"/></svg>
                </div>
                <h3>Văn phòng hỗ trợ</h3>
                <p>Địa chỉ liên hệ chính thức cho các hoạt động phối hợp, làm việc và hỗ trợ đối tác.</p>
                <p>123 Nguyễn Văn Cừ, Ninh Kiều, Cần Thơ</p>
            </article>

            <article class="contact-support-card reveal-up delay-5">
                <div class="contact-support-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 1.75A10.25 10.25 0 1 0 22.25 12 10.26 10.26 0 0 0 12 1.75Zm.75 5.5v4.44l3.22 1.92-.77 1.29-4.2-2.5V7.25Z"/></svg>
                </div>
                <h3>Khung giờ phản hồi</h3>
                <ul>
                    <li>Thứ 2 - Thứ 6: 7:00 - 22:00</li>
                    <li>Thứ 7 - Chủ nhật: 8:00 - 21:00</li>
                    <li>Ưu tiên lịch hẹn và yêu cầu khẩn</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="contact-form-section" id="contact-form">
    <div class="container contact-form-inner">
        <div class="contact-benefit-card reveal-up delay-2">
            <div class="contact-form-card-head">
                <small>Why patients choose us</small>
                <h2>Một trang contact đúng chất <span>MediConnect</span></h2>
                <p>
                    Không chỉ là nơi để lại thông tin, đây là điểm chạm giúp bệnh nhân và bác sĩ nhận được hỗ trợ đúng ngữ cảnh,
                    đúng chuyên môn và đúng thời điểm.
                </p>
            </div>

            <div class="contact-benefit-list">
                <div class="contact-benefit-item">
                    <span>01</span>
                    <div>
                        <h3>Ưu tiên lịch hẹn</h3>
                        <p>Các yêu cầu liên quan đến thay đổi, xác nhận hoặc cần hỗ trợ lịch khám được xử lý rõ ràng hơn.</p>
                    </div>
                </div>
                <div class="contact-benefit-item">
                    <span>02</span>
                    <div>
                        <h3>Hỗ trợ đa đối tượng</h3>
                        <p>Phù hợp cho bệnh nhân, bác sĩ, đối tác và cả người dùng cần tư vấn trước khi đăng ký.</p>
                    </div>
                </div>
                <div class="contact-benefit-item">
                    <span>03</span>
                    <div>
                        <h3>Thông tin nhất quán</h3>
                        <p>Tất cả hotline, email, địa chỉ và khung giờ đều được trình bày đồng bộ với thương hiệu.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-form-card reveal-up delay-3">
            <div class="contact-form-card-head">
                <small>Send a message</small>
                <h2>Để lại yêu cầu, chúng tôi sẽ <span>liên hệ lại</span></h2>
                <p>
                    Điền thông tin bên dưới để đội ngũ MediConnect hỗ trợ bạn nhanh hơn. Với câu hỏi về lịch hẹn hoặc bác sĩ,
                    hãy mô tả ngắn gọn để chúng tôi điều phối đúng đầu mối.
                </p>
            </div>

            @if(session('success'))
                <div class="contact-flash-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="contact-form-grid">
                    <div class="contact-form-field">
                        <label for="name">Họ và tên</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nhập họ và tên">
                        @error('name')<span class="input-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="contact-form-field">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Ví dụ: 0901 234 567">
                        @error('phone')<span class="input-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="contact-form-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Nhập email của bạn">
                        @error('email')<span class="input-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="contact-form-field">
                        <label for="subject">Chủ đề hỗ trợ</label>
                        <select id="subject" name="subject">
                            <option value="">Chọn chủ đề</option>
                            <option value="Đặt lịch khám" {{ old('subject') === 'Đặt lịch khám' ? 'selected' : '' }}>Đặt lịch khám</option>
                            <option value="Hỗ trợ tài khoản" {{ old('subject') === 'Hỗ trợ tài khoản' ? 'selected' : '' }}>Hỗ trợ tài khoản</option>
                            <option value="Tư vấn dịch vụ" {{ old('subject') === 'Tư vấn dịch vụ' ? 'selected' : '' }}>Tư vấn dịch vụ</option>
                            <option value="Hợp tác bác sĩ / đối tác" {{ old('subject') === 'Hợp tác bác sĩ / đối tác' ? 'selected' : '' }}>Hợp tác bác sĩ / đối tác</option>
                        </select>
                        @error('subject')<span class="input-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="contact-form-field contact-form-field--full">
                        <label for="message">Nội dung</label>
                        <textarea id="message" name="message" placeholder="Mô tả ngắn gọn nhu cầu của bạn">{{ old('message') }}</textarea>
                        @error('message')<span class="input-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="contact-form-submit">
                    <button type="submit" class="btn btn-primary">Gửi liên hệ</button>
                    <p class="contact-form-note">Thông tin của bạn chỉ được dùng để phản hồi yêu cầu hỗ trợ trên MediConnect.</p>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="contact-faq">
    <div class="container contact-faq-inner">
        <div class="contact-faq-head reveal-up delay-2">
            <span class="contact-page-label">Giải đáp nhanh</span>
            <h2>Một vài câu hỏi thường gặp trước khi <span>liên hệ</span></h2>
            <p>
                Các nội dung dưới đây giúp người dùng mới hiểu nhanh cách MediConnect hỗ trợ và khi nào nên liên hệ trực tiếp.
            </p>
        </div>

        <div class="contact-faq-grid">
            <article class="contact-faq-item reveal-up delay-2">
                <h3>Tôi muốn đổi lịch khám thì làm thế nào?</h3>
                <p>Bạn có thể vào tài khoản để kiểm tra lịch hẹn, hoặc liên hệ hotline để được hỗ trợ nhanh nếu lịch khám sắp diễn ra.</p>
            </article>

            <article class="contact-faq-item reveal-up delay-3">
                <h3>Tôi chưa biết nên chọn chuyên khoa nào?</h3>
                <p>Hãy gửi mô tả ngắn về nhu cầu trong form liên hệ, đội ngũ hỗ trợ sẽ định hướng bác sĩ hoặc chuyên khoa phù hợp hơn.</p>
            </article>

            <article class="contact-faq-item reveal-up delay-4">
                <h3>Bác sĩ có thể liên hệ để hợp tác không?</h3>
                <p>Có. Bạn có thể chọn chủ đề “Hợp tác bác sĩ / đối tác” trong form để MediConnect tiếp nhận và phản hồi đúng bộ phận.</p>
            </article>

            <article class="contact-faq-item reveal-up delay-5">
                <h3>Trang contact này đã gửi email thật chưa?</h3>
                <p>Hiện tại form đã xử lý giao diện và gửi yêu cầu nội bộ thành công. Nếu bạn muốn, có thể nối tiếp với mail hoặc lưu cơ sở dữ liệu ở bước sau.</p>
            </article>
        </div>
    </div>
</section>
@endsection
