# Testing User Interactions

## Click Interactions

```php
// Livewire button click
it('toggles visibility on click', function () {
    Livewire::test(Toggle::class)
        ->assertDontSee('Hidden Content')
        ->call('toggle')
        ->assertSee('Hidden Content')
        ->call('toggle')
        ->assertDontSee('Hidden Content');
});

// Alpine.js + Livewire
it('opens dropdown on click', function () {
    Livewire::test(Dropdown::class)
        ->assertDontSee('Dropdown Item')
        ->call('openDropdown')
        ->assertSee('Dropdown Item');
});
```

## Form Interactions

```php
// Typing in input
it('updates search results as user types', function () {
    Livewire::test(Search::class)
        ->set('query', 'lar')
        ->assertSee('Laravel')
        ->set('query', 'laravelavel')
        ->assertDontSee('Laravel');
});

// Form submission
it('submits form and shows success', function () {
    Livewire::test(ContactForm::class)
        ->fill([
            'name' => 'John',
            'email' => 'john@example.com',
            'message' => 'Hello',
        ])
        ->call('submit')
        ->assertSee('Thank you!')
        ->assertHasNoErrors();
});
```

## Navigation Interactions

```php
// Page navigation
it('navigates to post after creation', function () {
    Livewire::test(CreatePost::class)
        ->fill(['title' => 'My Post', 'content' => 'Content'])
        ->call('submit')
        ->assertRedirect(route('posts.show', Post::first()));
});

// Back navigation
it('navigates back on cancel', function () {
    Livewire::test(EditPost::class)
        ->call('cancel')
        ->assertRedirect()->route('posts.index');
});
```

## Modal Interactions

```php
// Open modal
it('opens confirmation modal', function () {
    Livewire::test(DeleteButton::class)
        ->call('confirmDelete')
        ->assertSee('Are you sure?')
        ->assertSee('Yes, delete');
});

// Confirm action
it('deletes on confirm', function () {
    $post = Post::factory()->create();
    
    Livewire::test(DeleteButton::class, ['postId' => $post->id])
        ->call('confirmDelete')
        ->call('delete')
        ->assertEmitted('post.deleted');
    
    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

// Cancel action
it('does not delete on cancel', function () {
    $post = Post::factory()->create();
    
    Livewire::test(DeleteButton::class, ['postId' => $post->id])
        ->call('confirmDelete')
        ->call('cancelDelete');
    
    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});
```

## Loading States

```php
// Test loading indicator
it('shows loading state during save', function () {
    Livewire::test(SlowForm::class)
        ->fill(['data' => 'value'])
        ->call('submit')
        ->assertSee('Saving...')
        ->assertDontSee('Saved!');
});

// Test completed state
it('shows success after save', function () {
    Livewire::test(SlowForm::class)
        ->fill(['data' => 'value'])
        ->call('submit')
        ->assertDontSee('Saving...')
        ->assertSee('Saved!');
});
```

## Keyboard Interactions

```php
// Enter key submission
it('submits form on Enter key', function () {
    Livewire::test(Search::class)
        ->set('query', 'test')
        ->dispatch('keydown.enter')
        ->assertSee('Search results');
});

// Escape key close
it('closes modal on Escape key', function () {
    Livewire::test(Modal::class)
        ->call('open')
        ->assertSee('Modal content')
        ->dispatch('keydown.escape')
        ->assertDontSee('Modal content');
});
```

## Touch Interactions (Mobile)

```php
// Swipe to delete
it('deletes on swipe left', function () {
    Livewire::test(SwipeableList::class)
        ->dispatch('swipe.left', $item->id)
        ->assertSee('Delete button');
});

// Long press menu
it('shows context menu on long press', function () {
    Livewire::test(LongPressItem::class)
        ->dispatch('longpress', $item->id)
        ->assertSee('Edit')
        ->assertSee('Delete');
});
```

## Real Browser Testing (Optional)

```php
<?php
// tests/Browser/LoginTest.php

use Laravel\Dusk\Browser;

it('logs in successfully', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->type('email', 'john@example.com')
            ->type('password', 'password')
            ->press('Login')
            ->assertPathIs('/dashboard');
    });
});
```

Note: Real browser tests require `pestphp/pest-plugin-browser`.
