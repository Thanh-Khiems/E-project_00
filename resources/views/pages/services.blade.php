@extends('layouts.app')

@section('title', 'Dịch vụ - MediConnect')

@section('content')
<section class="services-page-hero">
    <div class="services-page-hero-bg services-page-hero-bg-one"></div>
    <div class="services-page-hero-bg services-page-hero-bg-two"></div>

    <div class="container services-page-hero-inner">
        <div class="services-page-copy reveal-up delay-2">
            <div class="services-page-badges reveal-up delay-3">
                <span>Đặt lịch nhanh chóng</span>
                <span>Hỗ trợ nhiều chuyên khoa</span>
                <span>Đồng hành 24/7</span>
            </div>

            <h1 class="services-page-title reveal-up delay-4">
                Dịch vụ y tế số toàn diện từ <span>MediConnect</span>
            </h1>

            <p class="services-page-lead reveal-up delay-5">
                MediConnect mang đến hệ sinh thái chăm sóc sức khỏe hiện đại, nơi người bệnh có thể dễ dàng
                tìm đúng bác sĩ, đặt đúng lịch hẹn và nhận được sự hỗ trợ nhanh chóng trong suốt hành trình khám chữa bệnh.
            </p>

            <p class="services-page-sub reveal-up delay-5">
                Chúng tôi kết hợp công nghệ, trải nghiệm người dùng và đội ngũ chuyên môn để giúp mỗi lần tiếp cận dịch vụ y tế
                trở nên rõ ràng hơn, thuận tiện hơn và an tâm hơn.
            </p>

            <div class="services-page-actions reveal-up delay-6">
                <a href="{{ route('doctors.index') }}" class="btn btn-primary">Tìm bác sĩ ngay</a>
                <a href="#services-list" class="btn btn-outline">Khám phá dịch vụ</a>
            </div>

            <div class="services-page-stats reveal-up delay-6">
                <div class="services-page-stat-card">
                    <h3>10+</h3>
                    <p>Dịch vụ chăm sóc nổi bật</p>
                </div>
                <div class="services-page-stat-card">
                    <h3>24/7</h3>
                    <p>Hỗ trợ đặt lịch & tư vấn</p>
                </div>
                <div class="services-page-stat-card">
                    <h3>100%</h3>
                    <p>Tập trung vào trải nghiệm bệnh nhân</p>
                </div>
            </div>
        </div>

        <div class="services-page-visual reveal-up delay-4">
            <div class="services-page-panel">
                <div class="services-page-panel-head">
                    <div>
                        <small>Medical service overview</small>
                        <h2>Professional care<span>.</span></h2>
                    </div>
                    <div class="services-page-chip">MediConnect Services</div>
                </div>

                <div class="services-page-panel-body">
                    <div class="services-page-image-wrap">
                        <img src="{{ asset('images/services/service-main.webp') }}" alt="MediConnect services" class="services-page-image">
                        <div class="services-page-floating-card">
                            <strong>Trusted Care</strong>
                            <p>Đồng bộ đặt lịch, tư vấn và theo dõi dễ dàng trong một nền tảng duy nhất.</p>
                        </div>
                    </div>

                    <div class="services-page-panel-grid">
                        <div class="services-page-mini-card">
                            <span>01</span>
                            <h3>Khám tổng quát</h3>
                            <p>Đặt lịch nhanh với quy trình minh bạch, dễ theo dõi.</p>
                        </div>
                        <div class="services-page-mini-card">
                            <span>02</span>
                            <h3>Chuyên khoa</h3>
                            <p>Kết nối bác sĩ phù hợp theo nhu cầu điều trị cụ thể.</p>
                        </div>
                        <div class="services-page-mini-card">
                            <span>03</span>
                            <h3>Theo dõi lịch hẹn</h3>
                            <p>Nhắc lịch, quản lý trạng thái và cập nhật thuận tiện.</p>
                        </div>
                        <div class="services-page-mini-card">
                            <span>04</span>
                            <h3>Hỗ trợ tận tâm</h3>
                            <p>Đồng hành giải đáp trước, trong và sau khi đặt lịch.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services-page-section" id="services-list">
    <div class="container">
        <div class="services-page-section-head reveal-up delay-2">
            <span class="services-page-label">Danh mục dịch vụ</span>
            <h2>Những dịch vụ nổi bật trên MediConnect</h2>
            <p>
                Mỗi dịch vụ được thiết kế để giúp người bệnh tiếp cận chăm sóc sức khỏe thuận tiện hơn,
                đồng thời tối ưu trải nghiệm từ bước tìm hiểu thông tin cho đến khi hoàn tất lịch khám.
            </p>
        </div>

        <div class="services-page-grid">
            <article class="services-page-card reveal-up delay-2">
                <div class="services-page-card-icon">✚</div>
                <h3>Đặt lịch khám trực tuyến</h3>
                <p>Chọn bác sĩ, khung giờ phù hợp và xác nhận lịch hẹn chỉ trong vài bước đơn giản.</p>
                <ul>
                    <li>Quy trình rõ ràng</li>
                    <li>Tiết kiệm thời gian chờ</li>
                    <li>Dễ quản lý lịch sử đặt hẹn</li>
                </ul>
            </article>

            <article class="services-page-card reveal-up delay-3">
                <div class="services-page-card-icon">✚</div>
                <h3>Tư vấn chuyên khoa</h3>
                <p>Kết nối với đội ngũ bác sĩ theo từng chuyên khoa để nhận định hướng phù hợp với tình trạng sức khỏe.</p>
                <ul>
                    <li>Nhiều chuyên khoa hỗ trợ</li>
                    <li>Thông tin bác sĩ minh bạch</li>
                    <li>Dễ lựa chọn theo nhu cầu</li>
                </ul>
            </article>

            <article class="services-page-card reveal-up delay-4">
                <div class="services-page-card-icon">✚</div>
                <h3>Theo dõi cuộc hẹn</h3>
                <p>Kiểm tra trạng thái lịch khám, nhận nhắc nhở và chủ động sắp xếp thời gian hiệu quả hơn.</p>
                <ul>
                    <li>Nhắc lịch thông minh</li>
                    <li>Thông tin lịch hẹn tập trung</li>
                    <li>Giảm thiếu sót khi tái khám</li>
                </ul>
            </article>

            <article class="services-page-card reveal-up delay-5">
                <div class="services-page-card-icon">✚</div>
                <h3>Hồ sơ bệnh nhân tiện lợi</h3>
                <p>Quản lý thông tin cá nhân và lịch sử tương tác trong giao diện thân thiện, dễ sử dụng.</p>
                <ul>
                    <li>Cập nhật hồ sơ nhanh</li>
                    <li>Tăng tính liên tục điều trị</li>
                    <li>Thao tác trực quan</li>
                </ul>
            </article>

            <article class="services-page-card reveal-up delay-6">
                <div class="services-page-card-icon">✚</div>
                <h3>Hỗ trợ bác sĩ & điều phối</h3>
                <p>Hỗ trợ quản lý lịch làm việc, tối ưu quy trình tiếp nhận và nâng cao trải nghiệm phục vụ bệnh nhân.</p>
                <ul>
                    <li>Tổ chức lịch hiệu quả</li>
                    <li>Giảm thao tác thủ công</li>
                    <li>Tăng tính phối hợp nội bộ</li>
                </ul>
            </article>

            <article class="services-page-card reveal-up delay-7">
                <div class="services-page-card-icon">✚</div>
                <h3>Chăm sóc sau đặt lịch</h3>
                <p>MediConnect không dừng ở việc tạo cuộc hẹn mà còn tiếp tục đồng hành trong suốt hành trình chăm sóc.</p>
                <ul>
                    <li>Hỗ trợ sau xác nhận</li>
                    <li>Thông tin rõ ràng, dễ tra cứu</li>
                    <li>Tăng sự an tâm cho bệnh nhân</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="services-process">
    <div class="container services-process-inner">
        <div class="services-process-copy reveal-up delay-2">
            <span class="services-page-label">Quy trình trải nghiệm</span>
            <h2>Hành trình sử dụng dịch vụ đơn giản, nhanh và chuyên nghiệp</h2>
            <p>
                Chúng tôi thiết kế quy trình theo hướng tinh gọn để bệnh nhân dễ dàng tiếp cận dịch vụ,
                đồng thời vẫn đảm bảo sự rõ ràng, chủ động và đáng tin cậy ở từng bước.
            </p>
        </div>

        <div class="services-process-steps">
            <div class="services-process-step reveal-up delay-3">
                <span>01</span>
                <div>
                    <h3>Tìm dịch vụ phù hợp</h3>
                    <p>Khám phá chuyên khoa, bác sĩ và lựa chọn dịch vụ phù hợp với nhu cầu hiện tại.</p>
                </div>
            </div>
            <div class="services-process-step reveal-up delay-4">
                <span>02</span>
                <div>
                    <h3>Đặt lịch linh hoạt</h3>
                    <p>Chọn thời gian phù hợp, xác nhận nhanh và nhận thông tin chi tiết ngay trên hệ thống.</p>
                </div>
            </div>
            <div class="services-process-step reveal-up delay-5">
                <span>03</span>
                <div>
                    <h3>Theo dõi & cập nhật</h3>
                    <p>Quản lý lịch hẹn, cập nhật trạng thái và chủ động điều chỉnh khi cần thiết.</p>
                </div>
            </div>
            <div class="services-process-step reveal-up delay-6">
                <span>04</span>
                <div>
                    <h3>Nhận hỗ trợ liên tục</h3>
                    <p>Đội ngũ MediConnect luôn sẵn sàng đồng hành để trải nghiệm chăm sóc trở nên trọn vẹn hơn.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services-advantages">
    <div class="container">
        <div class="services-page-section-head reveal-up delay-2">
            <span class="services-page-label">Lợi thế MediConnect</span>
            <h2>Vì sao người dùng tin tưởng lựa chọn chúng tôi?</h2>
            <p>
                Không chỉ cung cấp chức năng, MediConnect còn chú trọng cảm giác an tâm, tính minh bạch
                và sự mượt mà trong toàn bộ trải nghiệm sử dụng.
            </p>
        </div>

        <div class="services-advantages-grid">
            <div class="services-advantage-item reveal-up delay-2">
                <h3>Giao diện thân thiện</h3>
                <p>Dễ thao tác cho cả người dùng mới, hỗ trợ truy cập nhanh các thông tin quan trọng.</p>
            </div>
            <div class="services-advantage-item reveal-up delay-3">
                <h3>Thông tin minh bạch</h3>
                <p>Người bệnh dễ theo dõi lịch hẹn, trạng thái xử lý và lựa chọn dịch vụ phù hợp hơn.</p>
            </div>
            <div class="services-advantage-item reveal-up delay-4">
                <h3>Định hướng chuyên nghiệp</h3>
                <p>Thiết kế lấy y tế làm trọng tâm, tạo cảm giác tin cậy và hiện đại cho toàn bộ nền tảng.</p>
            </div>
            <div class="services-advantage-item reveal-up delay-5">
                <h3>Khả năng mở rộng</h3>
                <p>Sẵn sàng phát triển thêm chuyên khoa, tính năng và quy trình hỗ trợ trong tương lai.</p>
            </div>
        </div>
    </div>
</section>

<section class="services-page-cta">
    <div class="container">
        <div class="services-page-cta-box reveal-up delay-2">
            <div class="services-page-cta-copy">
                <small>MediConnect Services</small>
                <h2>Sẵn sàng bắt đầu hành trình chăm sóc sức khỏe thuận tiện hơn?</h2>
                <p>
                    Hãy khám phá đội ngũ bác sĩ và dịch vụ nổi bật của MediConnect để tìm ra lựa chọn phù hợp nhất cho bạn.
                </p>
            </div>

            <div class="services-page-cta-actions">
                <a href="{{ route('doctors.index') }}" class="services-page-cta-primary">Xem danh sách bác sĩ</a>
                <a href="{{ url('/about') }}" class="services-page-cta-secondary">Tìm hiểu về MediConnect</a>
            </div>
        </div>
    </div>
</section>
@endsection
