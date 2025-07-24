<?php
// tests/Feature/HelloTest.php

namespace Tests\Feature;

use App\Models\Hello;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelloPageTest extends TestCase
{
  use RefreshDatabase;

  public function test_user_can_view_hello(): void
  {
    // Arrange
    $post = Hello::factory()->create([
      'word' => 'Test Hello View'
    ]);

    // Act
    $response = $this->get("/hello/{$post->id}");

    // Assert
    $response->assertStatus(200);
    $response->assertInertia(
      fn($page) =>
      $page->component('Hello/Show')
        ->has('post_hello')
        ->where('post_hello.word', 'Test Hello View')
    );
  }

  public function test_user_cannot_view_fake_hello(): void
  {

    $response = $this->get("/hello/fake-id");

    $response->assertStatus(404);
  }

}
