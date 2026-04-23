<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicBlogAccessTest extends TestCase
{
    public function test_guest_can_open_blog_listing_page(): void
    {
        $response = $this->get(route('blog.index'));

        $response->assertOk();
    }

    public function test_guest_can_open_blog_detail_page_from_home_featured_articles(): void
    {
        $response = $this->get(route('blog.show', '7-daily-habits-that-help-patients-stay-healthy'));

        $response->assertOk();
        $response->assertSee('7 Daily Habits That Help Patients Stay Healthy');
        $response->assertSee('Related articles');
    }
}
