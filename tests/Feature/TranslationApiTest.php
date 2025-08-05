<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\Tag;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();

        // Create auth user
        $this->user = \App\Models\User::factory()->create();
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $this->headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
    }

    /** @test */
    public function it_creates_a_translation()
    {
        $locale = Language::create(['locale' => 'en', 'name' => 'English']);
        $tag = Tag::create(['name' => 'web']);

        $response = $this->postJson('/api/translations', [
            'key' => 'greeting',
            'value' => 'Hello',
            'language_id' => $locale->id,
            'tags' => [$tag->id]
        ], $this->headers);

        $response->assertStatus(201);
        $this->assertDatabaseHas('translations', ['key' => 'greeting', 'value' => 'Hello']);
    }

    /** @test */
    public function it_updates_a_translation()
    {
        $locale = Language::create(['locale' => 'en', 'name' => 'English']);
        $translation = Translation::create([
            'key' => 'greeting',
            'value' => 'Hi',
            'language_id' => $locale->id
        ]);

        $response = $this->putJson("/api/translations/{$translation->id}", [
            'value' => 'Hello',
        ], $this->headers);

        $response->assertStatus(200);
        $this->assertDatabaseHas('translations', ['value' => 'Hello']);
    }

    /** @test */
    public function it_searches_translation_by_key()
    {
        $locale = Language::create(['locale' => 'en', 'name' => 'English']);
        Translation::create(['key' => 'welcome', 'value' => 'Welcome!', 'language_id' => $locale->id]);

        $response = $this->getJson('/api/translations/search?key=welcome', $this->headers);
        $response->assertStatus(200)->assertJsonFragment(['key' => 'welcome']);
    }

    /** @test */
    public function it_exports_json_for_locale()
    {
        $locale = Language::create(['locale' => 'en', 'name' => 'English']);
        Translation::create(['key' => 'bye', 'value' => 'Goodbye', 'language_id' => $locale->id]);

        $response = $this->getJson('/api/translations/export/json?locale=en', $this->headers);
        $response->assertStatus(200)->assertJsonFragment(['bye' => 'Goodbye']);
    }

    /** @test */
    public function export_endpoint_should_respond_under_500ms()
    {
        $locale = Language::create(['locale' => 'en', 'name' => 'English']);
        Translation::factory()->count(100)->create(['language_id' => $locale->id]);

        $start = microtime(true);
        $response = $this->getJson('/api/translations/export/json?locale=en', $this->headers);
        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);
        $this->assertLessThan(500, $duration, "Response took too long: {$duration}ms");
    }
}
