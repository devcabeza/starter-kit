# Mandatory Testing Policy

## Why E2E Testing is Mandatory

1. **Regression Prevention:** Catch UI bugs before users do
2. **Documentation:** Tests show how the interface should work
3. **Confidence:** Refactor without fear
4. **Quality Gate:** No untested code in production

## What Requires E2E Tests

### Livewire Components (ALL)
- Every Livewire class
- Every public method
- Every form
- Every modal/dialog
- Every dynamic interaction

### Blade Views (ALL)
- Every new page
- Every layout change
- Every component
- Every conditional display

### Forms (CRITICAL)
- Every form submission
- Every validation rule
- Every success/error message
- Every redirect after submission

## Test-First Approach

```php
// 1. Write the test FIRST
it('creates a post with title and content', function () {
    Livewire::test(CreatePost::class)
        ->fill([
            'title' => 'My First Post',
            'content' => 'This is the content.',
        ])
        ->call('submit')
        ->assertHasNoErrors();
    
    $this->assertDatabaseHas('posts', [
        'title' => 'My First Post',
    ]);
});

// 2. Then implement the component
class CreatePost extends Component
{
    public string $title = '';
    public string $content = '';
    
    public function submit(): void
    {
        $validated = $this->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);
        
        Post::create($validated);
    }
}
```

## Coverage Requirements

| Component Type | Minimum Coverage |
|----------------|------------------|
| Livewire Component | 100% public methods |
| Form | 100% validation rules |
| Modal | Open/close + content |
| Page | Rendering + auth |
| Navigation | All links work |

## Consequences of Missing Tests

1. **Code Review:** Will be rejected
2. **Merge:** Cannot merge without tests
3. **Deployment:** Cannot deploy untested UI

## Exceptions

Only these are exempt from E2E testing:
- Pure presentational components (no logic)
- Third-party component wrappers (test the wrapper, not the library)

Even these should have basic rendering tests.
