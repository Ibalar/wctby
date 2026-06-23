<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_page_is_displayed(): void
    {
        $page = Page::factory()->create([
            'slug' => 'about-us',
            'title' => 'About Us',
            'content' => '<p>Welcome to our store!</p>',
        ]);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertStatus(200);
        $response->assertViewIs('pages.show');
        $response->assertSee('About Us');
        $response->assertSee('Welcome to our store!');
    }

    public function test_inactive_page_returns_404(): void
    {
        $page = Page::factory()->inactive()->create(['slug' => 'hidden-page']);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertStatus(404);
    }

    public function test_nonexistent_page_returns_404(): void
    {
        $response = $this->get('/page/nonexistent-slug');

        $response->assertStatus(404);
    }
}
