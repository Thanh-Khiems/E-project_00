@extends('layouts.app')

@section('title', 'About MediConnect')

@section('content')
<section class="about-page-hero">
    <div class="about-page-bg about-page-bg-one"></div>
    <div class="about-page-bg about-page-bg-two"></div>

    <div class="container about-page-hero-inner">
        <div class="about-page-copy reveal-up delay-2">
            <div class="about-page-badges reveal-up delay-3">
                <span>Nền tảng y tế đáng tin cậy</span>
                <span>Đặt lịch nhanh chóng</span>
                <span>Hỗ trợ tận tâm</span>
            </div>

            <h1 class="about-page-title reveal-up delay-4">
                Về <span>MediConnect</span> —
                kết nối chăm sóc sức khỏe hiện đại, tin cậy và gần gũi.
            </h1>

            <p class="about-page-lead reveal-up delay-5">
                MediConnect là nền tảng giúp bệnh nhân kết nối với bác sĩ và dịch vụ y tế một cách nhanh chóng,
                minh bạch và thuận tiện. Chúng tôi tin rằng chăm sóc sức khỏe chất lượng không chỉ đến từ chuyên môn,
                mà còn từ một trải nghiệm đơn giản, thân thiện và luôn sẵn sàng đồng hành cùng người bệnh.
            </p>

            <p class="about-page-sub reveal-up delay-5">
                Với định hướng lấy con người làm trung tâm, MediConnect xây dựng cầu nối giữa công nghệ và y tế,
                giúp việc tìm kiếm thông tin, đặt lịch khám và tiếp cận đội ngũ chuyên gia trở nên dễ dàng hơn bao giờ hết.
            </p>

            <div class="about-page-actions reveal-up delay-6">
                <a href="{{ url('/doctor-list') }}" class="btn btn-primary">Tìm bác sĩ phù hợp</a>
                <a href="#about-story" class="btn btn-outline">Câu chuyện thương hiệu</a>
            </div>

            <div class="about-page-stats reveal-up delay-6">
                <div class="about-page-stat-card">
                    <h3>15+</h3>
                    <p>Năm kinh nghiệm</p>
                </div>
                <div class="about-page-stat-card">
                    <h3>5000+</h3>
                    <p>Bệnh nhân đã hỗ trợ</p>
                </div>
                <div class="about-page-stat-card">
                    <h3>50+</h3>
                    <p>Bác sĩ & chuyên gia</p>
                </div>
                <div class="about-page-stat-card">
                    <h3>24/7</h3>
                    <p>Hỗ trợ đặt lịch</p>
                </div>
            </div>
        </div>

        <div class="about-page-visual reveal-up delay-4">
            <div class="about-page-panel">
                <div class="about-page-panel-top">
                    <div>
                        <small>Welcome back</small>
                        <h2>Lê hiếu nghĩa<span>.</span></h2>
                    </div>
                    <div class="about-page-chip">About MediConnect</div>
                </div>

                <div class="about-page-panel-body">
                    <div class="about-page-panel-text">
                        <h3>
                            Connecting Patients to
                            <span>Trusted Care</span>, Anytime Anywhere
                        </h3>
                        <p>
                            Chúng tôi tạo nên một hành trình y tế số liền mạch, nơi người bệnh có thể tìm bác sĩ phù hợp,
                            đặt lịch thuận tiện và an tâm hơn trong từng quyết định chăm sóc sức khỏe.
                        </p>

                        <div class="about-page-vision-box">
                            <small>Tầm nhìn</small>
                            <strong>
                                Trở thành nền tảng kết nối y tế số đáng tin cậy hàng đầu,
                                giúp mọi người tiếp cận dịch vụ chăm sóc chất lượng dễ dàng hơn.
                            </strong>
                        </div>
                    </div>

                    <div class="about-page-doctor-wrap">
                        <div class="about-page-doctor-glow"></div>
                        <img src="{{ asset('images/about/about-doctor.png') }}" alt="Doctor avatar" class="about-page-doctor-image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-story" id="about-story">
    <div class="container about-story-inner">
        <div class="about-story-copy reveal-up delay-2">
            <span class="about-story-label">Câu chuyện thương hiệu</span>
            <h2>Connecting Care, Advancing Health</h2>
            <p>
                MediConnect ra đời với mong muốn thu hẹp khoảng cách giữa người bệnh và dịch vụ y tế chất lượng.
                Trong một thế giới ngày càng số hóa, chúng tôi tin rằng việc chăm sóc sức khỏe cũng cần trở nên nhanh hơn,
                dễ tiếp cận hơn và nhân văn hơn.
            </p>
            <p>
                Bằng cách kết hợp công nghệ hiện đại với sự thấu hiểu hành trình của bệnh nhân, MediConnect xây dựng một không gian
                nơi mọi nhu cầu từ tìm kiếm bác sĩ, xem thông tin chuyên khoa đến đặt lịch khám đều diễn ra mượt mà trong một nền tảng thống nhất.
            </p>
        </div>

        <div class="about-story-media reveal-up delay-4">
            <div class="about-story-image-box">
                <img src="{{ asset('images/about/hospital-room.webp') }}" alt="Hospital reception" class="about-story-image">
            </div>

            <div class="about-story-steps">
                <div class="about-story-step">
                    <span>01</span>
                    <p>Khởi tạo nền tảng kết nối bệnh nhân với đội ngũ bác sĩ uy tín.</p>
                </div>
                <div class="about-story-step">
                    <span>02</span>
                    <p>Chuẩn hóa quy trình đặt lịch trực tuyến nhanh, rõ ràng và thân thiện.</p>
                </div>
                <div class="about-story-step">
                    <span>03</span>
                    <p>Mở rộng mạng lưới chuyên khoa và nâng cao trải nghiệm hỗ trợ 24/7.</p>
                </div>
                <div class="about-story-step">
                    <span>04</span>
                    <p>Phát triển hệ sinh thái chăm sóc sức khỏe số toàn diện và gần gũi hơn mỗi ngày.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-values">
    <div class="container">
        <div class="about-values-head reveal-up delay-2">
            <span>Giá trị cốt lõi</span>
            <h2>Điều làm nên sự khác biệt của MediConnect</h2>
            <p>
                Không chỉ là một nền tảng đặt lịch, MediConnect hướng đến việc tạo nên một trải nghiệm chăm sóc sức khỏe trọn vẹn,
                nơi sự tin cậy, tiện lợi và tận tâm luôn song hành.
            </p>
        </div>

        <div class="about-values-grid">
            <article class="about-value-card reveal-up delay-2">
                <div class="about-value-icon">✦</div>
                <h3>Lấy bệnh nhân làm trung tâm</h3>
                <p>Mọi trải nghiệm trên MediConnect đều được thiết kế để giúp người bệnh tìm đúng bác sĩ, đúng chuyên khoa và đặt lịch nhanh nhất.</p>
            </article>

            <article class="about-value-card reveal-up delay-3">
                <div class="about-value-icon">✦</div>
                <h3>Kết nối đáng tin cậy</h3>
                <p>Chúng tôi xây dựng một hệ sinh thái nơi bệnh nhân, bác sĩ và cơ sở y tế có thể kết nối minh bạch, an toàn và hiệu quả.</p>
            </article>

            <article class="about-value-card reveal-up delay-4">
                <div class="about-value-icon">✦</div>
                <h3>Công nghệ phục vụ chăm sóc</h3>
                <p>Từ tra cứu thông tin, đặt lịch trực tuyến đến quản lý hành trình khám bệnh, công nghệ được dùng để đơn giản hóa mọi bước.</p>
            </article>

            <article class="about-value-card reveal-up delay-5">
                <div class="about-value-icon">✦</div>
                <h3>Đồng hành dài lâu</h3>
                <p>MediConnect không chỉ giúp đặt lịch một lần, mà còn hướng tới trải nghiệm chăm sóc sức khỏe liên tục và bền vững.</p>
            </article>
        </div>
    </div>
</section>

<section class="about-cta">
    <div class="container">
        <div class="about-cta-box reveal-up delay-2">
            <div class="about-cta-copy">
                <small>MediConnect</small>
                <h2>Chúng tôi không chỉ kết nối cuộc hẹn, chúng tôi kết nối sự an tâm.</h2>
                <p>Hãy để MediConnect trở thành người bạn đồng hành đáng tin cậy trên hành trình chăm sóc sức khỏe của bạn và gia đình.</p>
            </div>

            <div class="about-cta-contact">
                <span>Hotline hỗ trợ</span>
                <h3>1900 115 115</h3>
                <p>Email: mediconnect@gmail.com</p>
                <a href="{{ url('/contact') }}" class="about-cta-button">Liên hệ với chúng tôi</a>
            </div>
        </div>
    </div>
</section>
@endsection
