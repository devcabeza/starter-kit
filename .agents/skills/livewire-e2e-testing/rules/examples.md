# Complete Examples

## Example 1: Full CRUD Interface

### Test File
```php
<?php
// tests/Feature/Livewire/PostManagementTest.php

declare(strict_types=1);

use App\Livewire\Posts\{CreatePost, EditPost, PostList, DeletePost};
use App\Models\{Post, User};
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

// --- LIST ---

it('displays posts list', function () {
    Post::factory()->count(3)->create(['user_id' => $this->user->id]);
    
    Livewire::test(PostList::class)
        ->assertSee(Post::first()->title);
});

it('filters posts by search', function () {
    Post::factory()->create(['title' => 'Laravel Post']);
    Post::factory()->create(['title' => 'PHP Post']);
    
    Livewire::test(PostList::class)
        ->set('search', 'Laravel')
        ->assertSee('Laravel Post')
        ->assertDontSee('PHP Post');
});

// --- CREATE ---

it('renders create form', function () {
    Livewire::test(CreatePost::class)
        ->assertSee('Create Post')
        ->assertSee('Title')
        ->assertSee('Content');
});

it('validates required fields', function () {
    Livewire::test(CreatePost::class)
        ->call('submit')
        ->assertHasErrors(['title', 'content']);
});

it('creates post with valid data', function () {
    Livewire::test(CreatePost::class)
        ->fill([
            'title' => 'My New Post',
            'content' => 'This is the content.',
        ])
        ->call('submit')
        ->assertHasNoErrors()
        ->assertEmitted('post.created');
    
    $this->assertDatabaseHas('posts', [
        'title' => 'My New Post',
        'user_id' => $this->user->id,
    ]);
});

// --- EDIT ---

it('loads existing post data', function () {
    $post = Post::factory()->create(['user_id' => $this->user->id]);
    
    Livewire::test(EditPost::class, ['postId' => $post->id])
        ->assertSet('title', $post->title)
        ->assertSet('content', $post->content);
});

it('updates post', function () {
    $post = Post::factory()->create(['user_id' => $this->user->id]);
    
    Livewire::test(EditPost::class, ['postId' => $post->id])
        ->fill(['title' => 'Updated Title'])
        ->call('submit')
        ->assertHasNoErrors()
        ->assertEmitted('post.updated');
    
    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Title',
    ]);
});

// --- DELETE ---

it('confirms before deleting', function () {
    $post = Post::factory()->create(['user_id' => $this->user->id]);
    
    Livewire::test(DeletePost::class, ['postId' => $post->id])
        ->call('confirmDelete')
        ->assertSee('Are you sure?');
});

it('deletes post on confirm', function () {
    $post = Post::factory()->create(['user_id' => $this->user->id]);
    
    Livewire::test(DeletePost::class, ['postId' => $post->id])
        ->call('confirmDelete')
        ->call('delete')
        ->assertEmitted('post.deleted');
    
    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

it('does not delete on cancel', function () {
    $post = Post::factory()->create(['user_id' => $this->user->id]);
    
    Livewire::test(DeletePost::class, ['postId' => $post->id])
        ->call('confirmDelete')
        ->call('cancelDelete');
    
    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});
```

## Example 2: Dashboard with Metrics

```php
<?php
// tests/Feature/Livewire/DashboardMetricsTest.php

declare(strict_types=1);

use App\Livewire\Dashboard\Metrics;
use App\Models\{User, Order};
use Livewire\Livewire;

it('displays total users', function () {
    User::factory()->count(10)->create();
    
    Livewire::test(Metrics::class)
        ->assertSee('10')
        ->assertSee('Total Users');
});

it('displays total revenue', function () {
    Order::factory()->count(5)->create(['total' => 100]);
    
    Livewire::test(Metrics::class)
        ->assertSee('$500')
        ->assertSee('Total Revenue');
});

it('refreshes metrics on refresh button', function () {
    Livewire::test(Metrics::class)
        ->call('refresh')
        ->assertEmitted('metrics.refreshed');
});
```

## Example 3: Complex Form with Dependent Fields

```php
<?php
// tests/Feature/Livewire/OrderFormTest.php

declare(strict_types=1);

use App\Livewire\Orders\OrderForm;
use App\Models\{Product, Coupon};
use Livewire\Livewire;

it('loads products on mount', function () {
    Product::factory()->count(5)->create();
    
    Livewire::test(OrderForm::class)
        ->assertSet('products.count', 5);
});

it('updates price when product changes', function () {
    $product = Product::factory()->create(['price' => 100]);
    
    Livewire::test(OrderForm::class)
        ->set('product_id', $product->id)
        ->assertSet('price', 100);
});

it('applies coupon discount', function () {
    $coupon = Coupon::factory()->create(['discount' => 20]);
    $product = Product::factory()->create(['price' => 100]);
    
    Livewire::test(OrderForm::class)
        ->set('product_id', $product->id)
        ->set('coupon_code', $coupon->code)
        ->call('applyCoupon')
        ->assertSet('discount', 20)
        ->assertSet('total', 80);
});

it('validates coupon is valid', function () {
    Livewire::test(OrderForm::class)
        ->set('coupon_code', 'INVALID')
        ->call('applyCoupon')
        ->assertHasErrors(['coupon_code']);
});

it('places order successfully', function () {
    $product = Product::factory()->create(['price' => 100]);
    
    Livewire::test(OrderForm::class)
        ->set('product_id', $product->id)
        ->set('quantity', 2)
        ->call('placeOrder')
        ->assertHasNoErrors()
        ->assertRedirect(route('orders.confirmation'));
    
    $this->assertDatabaseHas('orders', [
        'product_id' => $product->id,
        'quantity' => 2,
        'total' => 200,
    ]);
});
```

## Example 4: Authentication Flow

```php
<?php
// tests/Feature/Livewire/Auth/LoginTest.php

declare(strict_types=1);

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders login form', function () {
    Livewire::test(Login::class)
        ->assertSee('Email')
        ->assertSee('Password')
        ->assertSee('Login');
});

it('validates email is required', function () {
    Livewire::test(Login::class)
        ->fill(['password' => 'password'])
        ->call('submit')
        ->assertHasErrors(['email' => 'required']);
});

it('validates password is required', function () {
    Livewire::test(Login::class)
        ->fill(['email' => 'john@example.com'])
        ->call('submit')
        ->assertHasErrors(['password' => 'required']);
});

it('validates credentials', function () {
    Livewire::test(Login::class)
        ->fill([
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ])
        ->call('submit')
        ->assertHasErrors(['email']);
});

it('logs in with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('password'),
    ]);
    
    Livewire::test(Login::class)
        ->fill([
            'email' => 'john@example.com',
            'password' => 'password',
        ])
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));
    
    $this->assertAuthenticatedAs($user);
});

it('remembers user', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('password'),
    ]);
    
    Livewire::test(Login::class)
        ->fill([
            'email' => 'john@example.com',
            'password' => 'password',
            'remember' => true,
        ])
        ->call('submit');
    
    $this->assertAuthenticated();
});
```

## Test Execution Commands

```bash
# Run all Livewire tests
php artisan test tests/Feature/Livewire/

# Run specific component test
php artisan test tests/Feature/Livewire/CounterTest.php

# Run with coverage
php artisan test --coverage

# Run with Pest directly
vendor/bin/pest tests/Feature/Livewire/
```
