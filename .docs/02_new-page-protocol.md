# Steps to create a new page

## 🎯 Overview

- Phase 1: Planning & Design
- Phase 2: Database Design
- Phase 3: Backend Development (TDD)
- Phase 4: Frontend Development
- Phase 5: Integration & Testing
- Phase 6: Refinement & Optimization

## 🎨 Phase 1: Planning & Design (30-60 minutes)

### 1.1 Requirements Analysis

```markdown
**Define the purpose:**

- What does this page do?
- Who will use it?
- What data does it display/collect?
- What actions can users perform?

**Example: Blog Post Page**

- Display individual blog post
- Show author information
- Display comments
- Allow authenticated users to comment
- Show related posts
```

### 1.2 UI/UX Design

```markdown
**Create wireframes:**

- Sketch layout structure
- Define components needed
- Plan responsive behavior
- Identify reusable components

**Data flow planning:**

- What props does each component need?
- Which components manage state?
- How do components communicate?
```

### 1.3 Technical Requirements

```markdown
**Backend needs:**

- What models are involved?
- What relationships exist?
- What validation rules?
- What API endpoints needed?

**Frontend needs:**

- What React components?
- What state management?
- What user interactions?
- What loading states?
```

---

## 🗄️ Phase 2: Database Design (30-45 minutes)

### 2.1 Model Planning

```bash
# Identify entities and relationships
# Example: Blog Post Page needs:
# - Post model (main content)
# - User model (author)
# - Comment model (user comments)
# - Category model (post categorization)
```

### 2.2 Create Models with Migrations

```bash
# Create models with migrations, factories, and seeders
./vendor/bin/sail artisan make:model Post -mfs
./vendor/bin/sail artisan make:model Comment -mfs
./vendor/bin/sail artisan make:model Category -mfs

# For existing models, just create what's needed
./vendor/bin/sail artisan make:migration add_slug_to_posts_table --table=posts
```

### 2.3 Define Migrations

```php
<?php
// database/migrations/create_posts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('slug');
        });
    }
};
```

### 2.4 Define Model Relationships

```php
<?php
// app/Models/Post.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image',
        'status', 'published_at', 'user_id', 'category_id'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    // Relationships
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    // Accessors
    public function getReadTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return ceil($wordCount / 200); // Assuming 200 words per minute
    }
}
```

### 2.5 Create Factories

```php
<?php
// database/factories/PostFactory.php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(2),
            'content' => fake()->paragraphs(8, true),
            'featured_image' => fake()->imageUrl(800, 400, 'technology'),
            'status' => fake()->randomElement(['draft', 'published']),
            'published_at' => fake()->optional(0.8)->dateTimeBetween('-6 months', 'now'),
            'views_count' => fake()->numberBetween(0, 1000),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }
}
```

### 2.6 Run Migrations

```bash
# Run migrations
./vendor/bin/sail artisan migrate

# Seed database with test data
./vendor/bin/sail artisan db:seed

# Or create specific data for testing
./vendor/bin/sail artisan tinker
# Then in tinker:
# Post::factory(10)->published()->create();
```

---

## 🔧 Phase 3: Backend Development with TDD (60-90 minutes)

### 3.1 Write Feature Tests First

```php
<?php
// tests/Feature/PostPageTest.php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_published_post(): void
    {
        // Arrange
        $post = Post::factory()->published()->create([
            'title' => 'Test Blog Post',
            'slug' => 'test-blog-post'
        ]);

        // Act
        $response = $this->get("/posts/{$post->slug}");

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Posts/Show')
                 ->has('post')
                 ->where('post.title', 'Test Blog Post')
                 ->where('post.slug', 'test-blog-post')
        );
    }

    public function test_user_cannot_view_draft_post(): void
    {
        $post = Post::factory()->create([
            'status' => 'draft',
            'slug' => 'draft-post'
        ]);

        $response = $this->get("/posts/{$post->slug}");

        $response->assertStatus(404);
    }

    public function test_post_increments_view_count(): void
    {
        $post = Post::factory()->published()->create(['views_count' => 5]);

        $this->get("/posts/{$post->slug}");

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'views_count' => 6
        ]);
    }

    public function test_post_page_includes_author_and_category(): void
    {
        $author = User::factory()->create(['name' => 'John Doe']);
        $category = Category::factory()->create(['name' => 'Technology']);
        $post = Post::factory()->published()->create([
            'user_id' => $author->id,
            'category_id' => $category->id
        ]);

        $response = $this->get("/posts/{$post->slug}");

        $response->assertInertia(fn ($page) =>
            $page->has('post.author')
                 ->where('post.author.name', 'John Doe')
                 ->has('post.category')
                 ->where('post.category.name', 'Technology')
        );
    }
}
```

### 3.2 Run Tests (They Should Fail)

```bash
# Run the specific test
./vendor/bin/sail artisan test tests/Feature/PostPageTest.php

# Expected result: Tests fail because routes and controllers don't exist yet
```

### 3.3 Create Controller

```bash
# Create the controller
./vendor/bin/sail artisan make:controller PostController
```

```php
<?php
// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function show(string $slug): Response
    {
        $post = Post::with(['author', 'category', 'comments.user'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment view count
        $post->increment('views_count');

        // Get related posts
        $relatedPosts = Post::with('author')
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->published()
            ->latest()
            ->take(3)
            ->get();

        return Inertia::render('Posts/Show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
```

### 3.4 Create Routes

```php
<?php
// routes/web.php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

// Post routes
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');
```

### 3.5 Run Tests Again

```bash
# Tests should pass now for basic functionality
./vendor/bin/sail artisan test tests/Feature/PostPageTest.php
```

### 3.6 Write Unit Tests for Models

```php
<?php
// tests/Unit/PostTest.php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_belongs_to_author(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $author->id]);

        $this->assertInstanceOf(User::class, $post->author);
        $this->assertEquals($author->id, $post->author->id);
    }

    public function test_post_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $post->category);
        $this->assertEquals($category->id, $post->category->id);
    }

    public function test_published_scope_returns_only_published_posts(): void
    {
        Post::factory()->create(['status' => 'published', 'published_at' => now()->subDay()]);
        Post::factory()->create(['status' => 'draft']);
        Post::factory()->create(['status' => 'published', 'published_at' => now()->addDay()]);

        $publishedPosts = Post::published()->get();

        $this->assertCount(1, $publishedPosts);
        $this->assertEquals('published', $publishedPosts->first()->status);
    }

    public function test_read_time_accessor_calculates_correctly(): void
    {
        $content = str_repeat('word ', 400); // 400 words
        $post = Post::factory()->make(['content' => $content]);

        $this->assertEquals(2, $post->read_time); // 400 words / 200 words per minute = 2 minutes
    }
}
```

---

## ⚛️ Phase 4: Frontend Development (90-120 minutes)

### 4.1 Create Base Layout Component

```jsx
// resources/js/Components/Layout/AppLayout.jsx

import { Head } from '@inertiajs/react';

export default function AppLayout({ title, children }) {
  return (
    <>
      <Head title={title} />
      <div className="min-h-screen bg-gray-50">
        <nav className="bg-white shadow-sm border-b border-gray-200">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex justify-between h-16">
              <div className="flex items-center">
                <h1 className="text-xl font-bold text-gray-900">My Blog</h1>
              </div>
            </div>
          </div>
        </nav>

        <main className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {children}
        </main>
      </div>
    </>
  );
}
```

### 4.2 Create Reusable Components

```jsx
// resources/js/Components/Post/PostCard.jsx

import { Link } from '@inertiajs/react';

export default function PostCard({ post }) {
  return (
    <article className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
      {post.featured_image && (
        <img
          src={post.featured_image}
          alt={post.title}
          className="w-full h-48 object-cover"
        />
      )}

      <div className="p-6">
        <div className="flex items-center text-sm text-gray-500 mb-2">
          <span>{post.category.name}</span>
          <span className="mx-2">•</span>
          <span>{post.read_time} min read</span>
        </div>

        <h3 className="text-xl font-bold text-gray-900 mb-2">
          <Link
            href={`/posts/${post.slug}`}
            className="hover:text-blue-600 transition-colors"
          >
            {post.title}
          </Link>
        </h3>

        <p className="text-gray-600 mb-4 line-clamp-3">{post.excerpt}</p>

        <div className="flex items-center justify-between">
          <div className="flex items-center space-x-2">
            <div className="text-sm">
              <p className="font-medium text-gray-900">{post.author.name}</p>
              <p className="text-gray-500">
                {new Date(post.published_at).toLocaleDateString()}
              </p>
            </div>
          </div>

          <div className="text-sm text-gray-500">{post.views_count} views</div>
        </div>
      </div>
    </article>
  );
}
```

```jsx
// resources/js/Components/UI/LoadingSpinner.jsx

export default function LoadingSpinner({ size = 'md' }) {
  const sizeClasses = {
    sm: 'h-4 w-4',
    md: 'h-8 w-8',
    lg: 'h-12 w-12',
  };

  return (
    <div className="flex justify-center items-center">
      <div
        className={`animate-spin rounded-full border-2 border-gray-300 border-t-blue-600 ${sizeClasses[size]}`}
      ></div>
    </div>
  );
}
```

### 4.3 Create Main Page Component

```jsx
// resources/js/Pages/Posts/Show.jsx

import { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/Components/Layout/AppLayout';
import PostCard from '@/Components/Post/PostCard';

export default function Show({ post, relatedPosts }) {
  const [isImageLoaded, setIsImageLoaded] = useState(false);

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
  };

  return (
    <AppLayout title={post.title}>
      <Head>
        <meta name="description" content={post.excerpt} />
        <meta property="og:title" content={post.title} />
        <meta property="og:description" content={post.excerpt} />
        <meta property="og:image" content={post.featured_image} />
        <meta property="og:type" content="article" />
      </Head>

      <div className="max-w-4xl mx-auto">
        {/* Breadcrumb */}
        <nav className="flex mb-8 text-sm">
          <Link href="/" className="text-blue-600 hover:text-blue-800">
            Home
          </Link>
          <span className="mx-2 text-gray-500">/</span>
          <Link
            href={`/categories/${post.category.slug}`}
            className="text-blue-600 hover:text-blue-800"
          >
            {post.category.name}
          </Link>
          <span className="mx-2 text-gray-500">/</span>
          <span className="text-gray-500">{post.title}</span>
        </nav>

        {/* Article Header */}
        <header className="mb-8">
          <div className="mb-4">
            <span className="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
              {post.category.name}
            </span>
          </div>

          <h1 className="text-4xl font-bold text-gray-900 mb-4 leading-tight">
            {post.title}
          </h1>

          <p className="text-xl text-gray-600 mb-6 leading-relaxed">
            {post.excerpt}
          </p>

          <div className="flex items-center justify-between border-b border-gray-200 pb-6">
            <div className="flex items-center space-x-4">
              <div>
                <p className="font-medium text-gray-900">{post.author.name}</p>
                <div className="flex items-center text-sm text-gray-500 space-x-2">
                  <time dateTime={post.published_at}>
                    {formatDate(post.published_at)}
                  </time>
                  <span>•</span>
                  <span>{post.read_time} min read</span>
                  <span>•</span>
                  <span>{post.views_count.toLocaleString()} views</span>
                </div>
              </div>
            </div>

            <div className="flex space-x-2">
              <button className="p-2 text-gray-400 hover:text-gray-600">
                <svg
                  className="w-5 h-5"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fillRule="evenodd"
                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                    clipRule="evenodd"
                  />
                </svg>
              </button>
              <button className="p-2 text-gray-400 hover:text-gray-600">
                <svg
                  className="w-5 h-5"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                </svg>
              </button>
            </div>
          </div>
        </header>

        {/* Featured Image */}
        {post.featured_image && (
          <div className="mb-8">
            <div className="relative">
              {!isImageLoaded && (
                <div className="absolute inset-0 bg-gray-200 animate-pulse rounded-lg"></div>
              )}
              <img
                src={post.featured_image}
                alt={post.title}
                className={`w-full h-96 object-cover rounded-lg transition-opacity duration-300 ${
                  isImageLoaded ? 'opacity-100' : 'opacity-0'
                }`}
                onLoad={() => setIsImageLoaded(true)}
              />
            </div>
          </div>
        )}

        {/* Article Content */}
        <article className="prose prose-lg max-w-none mb-12">
          <div
            dangerouslySetInnerHTML={{ __html: post.content }}
            className="leading-relaxed"
          />
        </article>

        {/* Related Posts */}
        {relatedPosts.length > 0 && (
          <section className="border-t border-gray-200 pt-12">
            <h2 className="text-2xl font-bold text-gray-900 mb-6">
              Related Articles
            </h2>
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
              {relatedPosts.map((relatedPost) => (
                <PostCard key={relatedPost.id} post={relatedPost} />
              ))}
            </div>
          </section>
        )}
      </div>
    </AppLayout>
  );
}
```

### 4.4 Add Custom CSS for Prose

```css
/* resources/css/app.css */
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom prose styles */
.prose {
  @apply text-gray-900;
}

.prose h2 {
  @apply text-2xl font-bold mt-8 mb-4 text-gray-900;
}

.prose h3 {
  @apply text-xl font-semibold mt-6 mb-3 text-gray-900;
}

.prose p {
  @apply mb-4 leading-relaxed;
}

.prose ul {
  @apply list-disc list-inside mb-4 space-y-2;
}

.prose ol {
  @apply list-decimal list-inside mb-4 space-y-2;
}

.prose blockquote {
  @apply border-l-4 border-blue-500 pl-4 italic text-gray-700 my-6;
}

.prose code {
  @apply bg-gray-100 text-gray-800 px-1 py-0.5 rounded text-sm;
}

.prose pre {
  @apply bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto mb-4;
}

.prose a {
  @apply text-blue-600 hover:text-blue-800 underline;
}

/* Line clamp utility for post excerpts */
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
```

---

## 🧪 Phase 5: Integration & Testing (30-45 minutes)

### 5.1 Write Frontend Component Tests

```jsx
// tests/JavaScript/Components/PostCard.test.jsx
// Note: This would require setting up Jest/React Testing Library

import { render, screen } from '@testing-library/react';
import PostCard from '../../../resources/js/Components/Post/PostCard';

const mockPost = {
  id: 1,
  title: 'Test Post',
  slug: 'test-post',
  excerpt: 'This is a test post excerpt',
  featured_image: 'https://example.com/image.jpg',
  read_time: 5,
  views_count: 100,
  published_at: '2024-01-01',
  author: { name: 'John Doe' },
  category: { name: 'Technology' },
};

test('renders post card with correct information', () => {
  render(<PostCard post={mockPost} />);

  expect(screen.getByText('Test Post')).toBeInTheDocument();
  expect(screen.getByText('This is a test post excerpt')).toBeInTheDocument();
  expect(screen.getByText('Technology')).toBeInTheDocument();
  expect(screen.getByText('John Doe')).toBeInTheDocument();
  expect(screen.getByText('5 min read')).toBeInTheDocument();
});
```

### 5.2 Run All Tests

```bash
# Run Laravel tests
./vendor/bin/sail artisan test

# Run specific test classes
./vendor/bin/sail artisan test tests/Feature/PostPageTest.php
./vendor/bin/sail artisan test tests/Unit/PostTest.php

# Run with coverage (if configured)
./vendor/bin/sail artisan test --coverage
```

### 5.3 Manual Testing Checklist

```markdown
**Functional Testing:**

- ✅ Page loads without errors
- ✅ All data displays correctly
- ✅ Images load properly
- ✅ Links work correctly
- ✅ Responsive design works on different screen sizes
- ✅ Loading states work
- ✅ Error handling works (404 for draft posts)

**Performance Testing:**

- ✅ Page loads quickly
- ✅ Images optimize and load efficiently
- ✅ No unnecessary API calls
- ✅ Database queries are optimized

**SEO Testing:**

- ✅ Meta tags are correct
- ✅ Open Graph tags work
- ✅ Structured data is present
- ✅ URLs are SEO-friendly
```

---

## 🚀 Phase 6: Refinement & Optimization (30-60 minutes)

### 6.1 Performance Optimization

#### Backend Optimization

```php
// Add eager loading in controller
public function show(string $slug): Response
{
    $post = Post::with([
        'author:id,name,email', // Only load needed fields
        'category:id,name,slug',
        'comments' => function($query) {
            $query->with('user:id,name')->latest()->take(10);
        }
    ])
    ->select('id', 'title', 'slug', 'excerpt', 'content', 'featured_image', 'status', 'published_at', 'views_count', 'user_id', 'category_id')
    ->where('slug', $slug)
    ->published()
    ->firstOrFail();

    // Increment view count asynchronously
    dispatch(function() use ($post) {
        $post->increment('views_count');
    });

    return Inertia::render('Posts/Show', [
        'post' => $post,
        'relatedPosts' => $this->getRelatedPosts($post),
    ]);
}

private function getRelatedPosts(Post $post)
{
    return Cache::remember("related_posts_{$post->id}", 3600, function() use ($post) {
        return Post::with('author:id,name')
            ->select('id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'user_id')
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->published()
            ->latest()
            ->take(3)
            ->get();
    });
}
```

#### Frontend Optimization

```jsx
// Add lazy loading for images
import { useState, useEffect } from 'react';

const LazyImage = ({ src, alt, className }) => {
  const [isLoaded, setIsLoaded] = useState(false);
  const [isInView, setIsInView] = useState(false);

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsInView(true);
          observer.disconnect();
        }
      },
      { threshold: 0.1 }
    );

    const imgElement = document.querySelector(`[data-src="${src}"]`);
    if (imgElement) observer.observe(imgElement);

    return () => observer.disconnect();
  }, [src]);

  return (
    <div className="relative">
      {!isLoaded && (
        <div className="absolute inset-0 bg-gray-200 animate-pulse" />
      )}
      {isInView && (
        <img
          src={src}
          alt={alt}
          className={className}
          onLoad={() => setIsLoaded(true)}
        />
      )}
      {!isInView && <div data-src={src} className={className} />}
    </div>
  );
};
```

### 6.2 Error Handling & User Experience

#### Backend Error Handling

```php
// app/Http/Controllers/PostController.php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

public function show(string $slug): Response
{
    try {
        $post = Post::with(['author', 'category'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Log post view for analytics
        Log::info('Post viewed', [
            'post_id' => $post->id,
            'slug' => $slug,
            'user_ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return Inertia::render('Posts/Show', [
            'post' => $post,
            'relatedPosts' => $this->getRelatedPosts($post),
        ]);

    } catch (ModelNotFoundException $e) {
        // Check if post exists but is not published
        $draftPost = Post::where('slug', $slug)->first();

        if ($draftPost && $draftPost->status !== 'published') {
            abort(404, 'This post is not yet published.');
        }

        abort(404, 'Post not found.');
    }
}
```

#### Frontend Error Boundaries

```jsx
// resources/js/Components/ErrorBoundary.jsx
import { Component } from 'react';

class ErrorBoundary extends Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false, error: null };
  }

  static getDerivedStateFromError(error) {
    return { hasError: true, error };
  }

  componentDidCatch(error, errorInfo) {
    console.error('Error caught by boundary:', error, errorInfo);

    // You could send this to an error reporting service
    // errorReportingService.captureException(error);
  }

  render() {
    if (this.state.hasError) {
      return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50">
          <div className="max-w-md w-full bg-white shadow-lg rounded-lg p-6">
            <div className="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
              <svg
                className="w-6 h-6 text-red-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"
                />
              </svg>
            </div>
            <h2 className="text-lg font-semibold text-gray-900 text-center mb-2">
              Something went wrong
            </h2>
            <p className="text-gray-600 text-center mb-4">
              We encountered an error while loading this page.
            </p>
            <button
              onClick={() => window.location.reload()}
              className="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors"
            >
              Reload Page
            </button>
          </div>
        </div>
      );
    }

    return this.props.children;
  }
}

export default ErrorBoundary;
```

#### Loading States

```jsx
// resources/js/Components/UI/LoadingState.jsx
export default function LoadingState({ type = 'page' }) {
  if (type === 'page') {
    return (
      <div className="max-w-4xl mx-auto animate-pulse">
        {/* Header skeleton */}
        <div className="mb-8">
          <div className="h-4 bg-gray-200 rounded w-24 mb-4"></div>
          <div className="h-8 bg-gray-200 rounded w-3/4 mb-4"></div>
          <div className="h-6 bg-gray-200 rounded w-1/2 mb-6"></div>
          <div className="h-4 bg-gray-200 rounded w-1/3"></div>
        </div>

        {/* Image skeleton */}
        <div className="h-96 bg-gray-200 rounded-lg mb-8"></div>

        {/* Content skeleton */}
        <div className="space-y-4">
          {[...Array(6)].map((_, i) => (
            <div key={i} className="h-4 bg-gray-200 rounded"></div>
          ))}
        </div>
      </div>
    );
  }

  return (
    <div className="flex justify-center items-center h-64">
      <div className="animate-spin rounded-full h-8 w-8 border-2 border-gray-300 border-t-blue-600"></div>
    </div>
  );
}
```

### 6.3 SEO & Accessibility Improvements

#### Enhanced Meta Tags

```jsx
// resources/js/Pages/Posts/Show.jsx - Enhanced Head section
<Head>
  <title>{post.title}</title>
  <meta name="description" content={post.excerpt} />
  <meta name="keywords" content={`${post.category.name}, blog, article`} />
  <meta name="author" content={post.author.name} />
  <meta name="robots" content="index, follow" />

  {/* Open Graph */}
  <meta property="og:title" content={post.title} />
  <meta property="og:description" content={post.excerpt} />
  <meta property="og:image" content={post.featured_image} />
  <meta
    property="og:url"
    content={`${window.location.origin}/posts/${post.slug}`}
  />
  <meta property="og:type" content="article" />
  <meta property="og:site_name" content="My Blog" />
  <meta property="article:author" content={post.author.name} />
  <meta property="article:published_time" content={post.published_at} />
  <meta property="article:section" content={post.category.name} />

  {/* Twitter Card */}
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content={post.title} />
  <meta name="twitter:description" content={post.excerpt} />
  <meta name="twitter:image" content={post.featured_image} />

  {/* Canonical URL */}
  <link rel="canonical" href={`${window.location.origin}/posts/${post.slug}`} />

  {/* JSON-LD Structured Data */}
  <script type="application/ld+json">
    {JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Article',
      headline: post.title,
      description: post.excerpt,
      image: post.featured_image,
      datePublished: post.published_at,
      dateModified: post.updated_at,
      author: {
        '@type': 'Person',
        name: post.author.name,
      },
      publisher: {
        '@type': 'Organization',
        name: 'My Blog',
      },
    })}
  </script>
</Head>
```

#### Accessibility Improvements

```jsx
// Enhanced component with accessibility features
export default function Show({ post, relatedPosts }) {
  return (
    <AppLayout title={post.title}>
      {/* Skip to content link */}
      <a
        href="#main-content"
        className="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 bg-blue-600 text-white p-2 z-50"
      >
        Skip to main content
      </a>

      <div className="max-w-4xl mx-auto">
        {/* Breadcrumb with proper markup */}
        <nav aria-label="Breadcrumb" className="flex mb-8 text-sm">
          <ol className="flex items-center space-x-2">
            <li>
              <Link href="/" className="text-blue-600 hover:text-blue-800">
                Home
              </Link>
            </li>
            <li aria-hidden="true" className="text-gray-500">
              /
            </li>
            <li>
              <Link
                href={`/categories/${post.category.slug}`}
                className="text-blue-600 hover:text-blue-800"
              >
                {post.category.name}
              </Link>
            </li>
            <li aria-hidden="true" className="text-gray-500">
              /
            </li>
            <li aria-current="page" className="text-gray-500">
              {post.title}
            </li>
          </ol>
        </nav>

        {/* Main content with proper heading hierarchy */}
        <main id="main-content">
          <article>
            <header className="mb-8">
              <div className="mb-4">
                <span className="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                  {post.category.name}
                </span>
              </div>

              <h1 className="text-4xl font-bold text-gray-900 mb-4 leading-tight">
                {post.title}
              </h1>

              <p className="text-xl text-gray-600 mb-6 leading-relaxed">
                {post.excerpt}
              </p>

              <div className="flex items-center justify-between border-b border-gray-200 pb-6">
                <div className="flex items-center space-x-4">
                  <div>
                    <p className="font-medium text-gray-900">
                      {post.author.name}
                    </p>
                    <div className="flex items-center text-sm text-gray-500 space-x-2">
                      <time dateTime={post.published_at}>
                        {formatDate(post.published_at)}
                      </time>
                      <span aria-hidden="true">•</span>
                      <span>{post.read_time} min read</span>
                      <span aria-hidden="true">•</span>
                      <span>{post.views_count.toLocaleString()} views</span>
                    </div>
                  </div>
                </div>

                <div className="flex space-x-2">
                  <button
                    className="p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded"
                    aria-label="Like this post"
                  >
                    <svg
                      className="w-5 h-5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      aria-hidden="true"
                    >
                      <path
                        fillRule="evenodd"
                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                        clipRule="evenodd"
                      />
                    </svg>
                  </button>
                  <button
                    className="p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded"
                    aria-label="Share this post"
                  >
                    <svg
                      className="w-5 h-5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      aria-hidden="true"
                    >
                      <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                    </svg>
                  </button>
                </div>
              </div>
            </header>

            {/* Featured Image with proper alt text */}
            {post.featured_image && (
              <figure className="mb-8">
                <img
                  src={post.featured_image}
                  alt={`Featured image for ${post.title}`}
                  className="w-full h-96 object-cover rounded-lg"
                />
              </figure>
            )}

            {/* Article Content with proper heading structure */}
            <div className="prose prose-lg max-w-none mb-12">
              <div
                dangerouslySetInnerHTML={{ __html: post.content }}
                className="leading-relaxed"
              />
            </div>
          </article>

          {/* Related Posts */}
          {relatedPosts.length > 0 && (
            <section className="border-t border-gray-200 pt-12">
              <h2 className="text-2xl font-bold text-gray-900 mb-6">
                Related Articles
              </h2>
              <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                {relatedPosts.map((relatedPost) => (
                  <PostCard key={relatedPost.id} post={relatedPost} />
                ))}
              </div>
            </section>
          )}
        </main>
      </div>
    </AppLayout>
  );
}
```

### 6.4 Final Testing & Quality Assurance

#### Comprehensive Test Suite

```bash
# Run all tests with coverage
./vendor/bin/sail artisan test --coverage --min=80

# Test specific functionality
./vendor/bin/sail artisan test --filter=PostPageTest

# Run performance tests (if configured)
./vendor/bin/sail artisan test --group=performance
```

#### Manual QA Checklist

```markdown
## 📝 Quality Assurance Checklist

### ✅ Functionality

- [ ] Page loads correctly for published posts
- [ ] 404 error shows for draft/non-existent posts
- [ ] View count increments properly
- [ ] Related posts display correctly
- [ ] All links work properly
- [ ] Images load and display correctly
- [ ] Meta tags are properly set
- [ ] Breadcrumb navigation works

### ✅ Performance

- [ ] Page load time < 3 seconds
- [ ] Images are optimized
- [ ] Database queries are efficient (< 5 queries total)
- [ ] Caching is working properly
- [ ] No JavaScript errors in console
- [ ] No layout shift (CLS < 0.1)

### ✅ Accessibility

- [ ] Keyboard navigation works
- [ ] Screen reader compatibility
- [ ] Proper heading hierarchy (h1 → h2 → h3)
- [ ] Alt text for images
- [ ] Focus indicators visible
- [ ] Color contrast meets WCAG AA standards
- [ ] Skip to content link works

### ✅ SEO

- [ ] Title tag is descriptive and < 60 characters
- [ ] Meta description < 160 characters
- [ ] Open Graph tags work correctly
- [ ] Structured data validates (schema.org)
- [ ] Canonical URL is set
- [ ] URL is SEO-friendly

### ✅ Mobile Responsiveness

- [ ] Layout works on mobile (320px+)
- [ ] Images scale properly
- [ ] Text is readable without zoom
- [ ] Touch targets are adequate (44px+)
- [ ] Navigation is mobile-friendly

### ✅ Cross-browser Compatibility

- [ ] Works in Chrome (latest)
- [ ] Works in Firefox (latest)
- [ ] Works in Safari (latest)
- [ ] Works in Edge (latest)
- [ ] Graceful degradation for older browsers
```

### 6.5 Documentation & Deployment

#### Code Documentation

```php
/**
 * Display the specified blog post.
 *
 * @param string $slug The post slug
 * @return \Inertia\Response
 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
 */
public function show(string $slug): Response
{
    // Implementation with detailed comments
}
```

#### Deployment Preparation

```bash
# Optimize for production
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache

# Build assets
./vendor/bin/sail npm run build

# Run final tests
./vendor/bin/sail artisan test
```

---

## 📚 Best Practices Summary

### 🎯 Development Best Practices

1. **Follow TDD**: Write tests first, then implement functionality
2. **Component Reusability**: Create reusable React components
3. **Performance First**: Optimize database queries and asset loading
4. **Accessibility**: Build for all users from the start
5. **SEO-friendly**: Include proper meta tags and structured data

### ⚡ Performance Tips

- Use eager loading for relationships
- Implement caching strategies
- Optimize images and assets
- Minimize database queries
- Use lazy loading for non-critical content

### 🔒 Security Considerations

- Validate all inputs
- Sanitize output (especially for dangerouslySetInnerHTML)
- Use CSRF protection
- Implement proper authorization
- Validate file uploads

### 📱 Mobile-First Approach

- Design for mobile first
- Use responsive breakpoints
- Optimize for touch interactions
- Consider offline functionality
- Test on real devices

---

## 🎉 Conclusion

This guideline provides a comprehensive, systematic approach to building web pages with Laravel + Inertia.js + React. By following these phases:

1. **Planning prevents poor performance** - Good upfront planning saves time later
2. **TDD ensures quality** - Tests catch bugs early and guide good design
3. **Component thinking scales** - Reusable components speed up future development
4. **Performance matters** - Users expect fast, responsive experiences
5. **Accessibility is essential** - Building for everyone from the start is easier than retrofitting

Remember: This process becomes faster with practice. Initially, it may take 4-6 hours for a complex page, but experienced teams can complete similar pages in 2-3 hours.

**Next Steps**: Apply this guideline to build your "Hello World" application with multiple routes, then gradually tackle more complex pages as you master each phase!
