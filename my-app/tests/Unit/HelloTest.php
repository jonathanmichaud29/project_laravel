<?php
// tests/Unit/HelloTest.php

namespace Tests\Unit;

use App\Models\Hello;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelloTest extends TestCase
{
  use RefreshDatabase;

  public function test_hello_creation(): void
  {
    $post_hello = Hello::factory()->create(['word' => 'unit-test-word']);

    $this->assertDatabaseHas('hellos', [
      'word' => 'unit-test-word'
    ]);

    // Assert: Model is valid
    $this->assertInstanceOf(Hello::class, $post_hello);
    $this->assertEquals('unit-test-word', $post_hello->word);
    $this->assertNotNull($post_hello->id);
  }

}