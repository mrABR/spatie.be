## General PHP Rules

Code style must follow [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/) and [PSR-12](https://www.php-fig.org/psr/psr-12/). Generally speaking, everything string-like that's not public-facing should use camelCase. Detailed examples on these are spread throughout the guide in their relevant sections.

### Class defaults

By default, we don't use `final`. In our team, we haven't met any benefits with what `final` offers so far. We assume that all our users know they are responsible for writing tests for any overwritten behaviour.

### Nullable and union types

Whenever possible use the short nullable notation of a type, instead of using a union of the type with `null`.

```php
// in a class

// good
public ?string $variable;

// bad
public string | null $variable;
```

### Void return types

If a method returns nothing, it should be indicated with `void`.
This makes it more clear to the users of your code what your intention was when writing it.

```php
// good

// in a Laravel model
public function scopeArchived(Builder $query): void
{
    $query->
        ...
}
```


## Typed properties

You should type a property whenever possible for a more strict assembly. 

```php
// good
class Foo
{
    public string $bar;
}
```

## Docblocks

Always use docblocks, even when methods that can be fully type hinted.

Adding a description proves itself valuable when reviewing and revising funcionality without actually checking how the code was written. If there is a need to check the written code, then proper comments within the method and clear variable names will be required.

```php
// Good
class Url
{
    /**
     * Create a url from a string.
     *
     * @param string $url
     *
     * @return \Spatie\Url\Url
     */
    public static function fromString(string $url): Url
    {
        // ...
    }
}

// Bad
class Url
{
    
    public static function fromString(string $url): Url
    {
        // ...
    }
}
```

Always use fully qualified class names in docblocks.

```php
// Good

/**
 * @param string $url
 *
 * @return \Spatie\Url\Url
 */

// Bad

/**
 * @param string $foo
 *
 * @return Url
 */
```

If a variable has multiple types, the most common occurring type should be first.

```php
// Good

/** @var \Spatie\Goo\Bar|null */

// Bad

/** @var null|\Spatie\Goo\Bar */
```

## Constructor property promotion

Avoid using constructor property promotion. 

```php
// Good
class MyClass {
    protected string $firstArgument
    protected string $secondArgument

    public function __construct(string $firstArgument, string $secondArgument)
    {
        $this->firstArgument = $firstArgument;
        $this->secondArgument = $secondArgument;
    }
}


// Bad
class MyClass {
    public function __construct(
        protected string $firstArgument,
        protected string $secondArgument,
    ) {}
}
```

## Traits

Each applied trait should go on its own line, and the `use` keyword should be used for each of them. This will result in clean diffs when traits are added or removed.

```php
// Good

class MyClass {
    use TraitA;
    use TraitB;
}
```

```php
// Bad

class MyClass {
    use TraitA, TraitB;
}
```

## Strings

When possible prefer string interpolation above `sprintf` and the `.` operator.

```php
// Good
$greeting = "Hi, I am {$name}.";
```

```php
// Bad
$greeting = 'Hi, I am ' . $name . '.';
```


## Ternary operators

Every portion of a ternary expression should be on its own line unless it's a really short expression.

```php
// Good
$name = $isFoo ? 'foo' : 'bar';
$name = $isFoo 
    ? 'foo' 
    : 'bar';

// Bad
$result = $object instanceof Model ?
    $object->name :
   'A default value';
```

## If statements

### Bracket position

Always use curly brackets.

```php
// Good
if ($condition) {
   ...
}

// Bad
if ($condition) ...
```

### Happy path

Generally a function should have its unhappy path first and its happy path last.

```php
// Good

if (! $goodCondition) {
  throw new Exception;
}

// do work
```


```php
// Bad

if ($goodCondition) {
 // do work
}

throw new Exception;
```

### Avoid else

In general, `else` should be avoided because it makes code less readable. In most cases it can be refactored using early returns. This will also cause the happy path to go last, which is desirable.

```php
// Good

if (! $conditionBA) {
   // conditionB A failed

   return;
}

if (! $conditionB) {
   // conditionB A passed, B failed

   return;
}

// condition A and B passed
```

```php
// Bad

if ($conditionA) {
   if ($conditionB) {
      // condition A and B passed
   }
   else {
     // conditionB A passed, B failed
   }
}
else {
   // conditionB A failed
}
```

Another option to refactor an `else` away is using a ternary

```php
// Bad

if ($condition) {
    $this->doSomething();
} 
else {
    $this->doSomethingElse();
}


```

```php
// Good

$condition
    ? $this->doSomething();
    : $this->doSomethingElse();
```


### Compound ifs

In general, separate `if` statements should be preferred over a compound condition. This makes debugging code easier.


```php
// Good
if (! $conditionA) {
   return;
}

if (! $conditionB) {
   return;
}

if (! $conditionC) {
   return;
}

// do stuff
```

```php
// bad
if ($conditionA && $conditionB && $conditionC) {
  // do stuff
}
```

## Comments

Generally, comments should be avoided as much as possible by writing expressive code. If the code can't be read like a book, you need to refactor.
If you do need to use a comment (happens), format it like this:

```php
// There should be a space before a single line comment.

/*
 * If you need to explain a lot you can use a comment block. Notice the
 * single * on the first line. Comment blocks don't need to be three
 * lines long or three characters shorter than the previous line.
 */
```

## Test classes

If you need a specific class for your test cases, you should keep them within the same test file when possible. When you want to reuse test classes throughout tests, it's fine to make a dedicated class instead. Test methods can be either camelCase or snake_case in order to provide readability. Here's an example of internal classes:

```php
<?php

namespace Spatie\EventSourcing\Tests\AggregateRoots;

// …

class AggregateEntityTest extends TestCase
{
    /** @test */
    public function test_entities()
    {
        // …
    }
}

class ItemAdded extends ShouldBeStored
{
    public function __construct(
        public string $name
    ) {
    }
}

class CartCleared extends ShouldBeStored
{
}
```

## Whitespace

Statements should be allowed to breathe. In general always add blank lines between statements, unless they're a sequence of single-line equivalent operations. This isn't something enforceable, it's a matter of what looks best in its context.

```php
// Good
public function getPage($url)
{
    $page = $this->pages()->where('slug', $url)->first();

    if (! $page) {
        return null;
    }

    if ($page['private'] && ! Auth::check()) {
        return null;
    }

    return $page;
}

// Bad: Everything's cramped together.
public function getPage($url)
{
    $page = $this->pages()->where('slug', $url)->first();
    if (! $page) {
        return null;
    }
    if ($page['private'] && ! Auth::check()) {
        return null;
    }
    return $page;
}
```

```php
// Good: A sequence of single-line equivalent operations.
public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });
}
```

Don't add any extra empty lines between `{}` brackets.

```php
// Good
if ($foo) {
    $this->foo = $foo;
}

// Bad
if ($foo) {

    $this->foo = $foo;

}
```

## Configuration

Configuration files must use kebab-case.

```
config/
  pdf-generator.php
```

Configuration keys must use snake_case.

```php
// config/pdf-generator.php
return [
    'chrome_path' => env('CHROME_PATH'),
];
```

Avoid using the `env` helper outside of configuration files. Create a configuration value from the `env` variable like above.

When adding config values for a specific service, add them to the `services` config file. Do not create a new config file.

```php
// Good: adding credentials to `config/services.php`
return [
     'ses' => [
            'key' => env('SES_AWS_ACCESS_KEY_ID'),
            'secret' => env('SES_AWS_SECRET_ACCESS_KEY'),
            'region' => env('SES_AWS_DEFAULT_REGION', 'us-east-1'),
        ],
    
    'github' => [
        'username' => env('GITHUB_USERNAME'),
        'token' => env('GITHUB_TOKEN'),
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_CALLBACK_URL'),
        'docs_access_token' => env('GITHUB_ACCESS_TOKEN'),
    ],
    
    'weyland_yutani' => [
        'token' => env('WEYLAND_YUTANI_TOKEN')
    ],   
];
```

```php
// Bad: creating a new config file: `weyland-yutani.php`

return [
    'weyland_yutani' => [
        'token' => env('WEYLAND_YUTANI_TOKEN')
    ],  
]
```

## Artisan commands

The names given to artisan commands should all be kebab-cased.

```bash
# Good
php artisan delete-old-records

# Bad
php artisan deleteOldRecords
```

A command should always give some feedback on what the result is. Minimally you should let the `handle` method spit out a comment at the end indicating that all went well.

```php
// in a Command
public function handle()
{
    // do some work

    $this->comment('All ok!');
}
```

When the main function of a result is processing items, consider adding output inside of the loop, so progress can be tracked. Put the output before the actual process. If something goes wrong, this makes it easy to know which item caused the error.

At the end of the command, provide a summary on how much processing was done.

```php
// in a Command
public function handle()
    {
    $this->comment("Start processing items...")

    // do some work
    $items->each(function(Item $item) {
        $this->info("Processing item id `{$item-id}`...")

        $this->processItem($item)
    });

    $this->comment("Processed {$item->count()} items.");
}
```

## Routing

Public-facing urls must use kebab-case.

```
https://spatie.be/open-source
https://spatie.be/jobs/front-end-developer
```

Prefer to use the route tuple notation when possible.

```php
// Good
Route::get('open-source', [OpenSourceController::class, 'index']);

// Bad/Old 
Route::get('open-source', 'OpenSourceController@index');
```

Route names must use camelCase or dot notation.

```php
// Good
Route::get('open-source', [OpenSourceController::class, 'index'])->name('openSource');
Route::get('open-source', [OpenSourceController::class, 'index'])->name('open.source');

// Bad
Route::get('open-source', [OpenSourceController::class, 'index'])->name('open-source');
```

All routes have an http verb, that's why we like to put the verb first when defining a route. It makes a group of routes very readable. Any other route options should come after it.

```php
// good: all http verbs come first
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('open-source', [OpenSourceController::class, 'index'])->name('openSource');

// bad: http verbs not easily scannable
Route::name('home')->get('/', [HomeController::class, 'index']);
Route::name('openSource')->get([OpenSourceController::class, 'index']);
```

Route parameters should use camelCase.

```php
Route::get('news/{newsItem}', [NewsItemsController::class, 'index']);
```

A route url should not start with `/` unless the url would be an empty string.

```php
// good
Route::get('/', [HomeController::class, 'index']);
Route::get('open-source', [OpenSourceController::class, 'index']);

//bad
Route::get('', [HomeController::class, 'index']);
Route::get('/open-source', [OpenSourceController::class, 'index']);
```

## Controllers

Controllers that control a resource must use the singular resource name.

```php
class PostController
{
    // ...
}
```

Try to keep controllers simple and stick to the default CRUD or BREAD keywords (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`). Extract a new controller if you need other actions.

In the following example, we could have `PostsController@favorite`, and `PostsController@unfavorite`, or we could extract it to a separate `FavoritePostsController`.

```php
class PostController
{
    public function create()
    {
        // ...
    }

    // ...

    public function favorite(Post $post)
    {
        request()->user()->favorites()->attach($post);

        return response(null, 200);
    }

    public function unfavorite(Post $post)
    {
        request()->user()->favorites()->detach($post);

        return response(null, 200);
    }
}
```

Here we fall back to default CRUD words, `store` and `destroy`.

```php
class FavoritePostController
{
    public function store(Post $post)
    {
        request()->user()->favorites()->attach($post);

        return response(null, 200);
    }

    public function destroy(Post $post)
    {
        request()->user()->favorites()->detach($post);

        return response(null, 200);
    }
}
```

This is a loose guideline that doesn't need to be enforced.

## Views

View files must use camelCase.

```
resources/
  views/
    openSource.blade.php
```

```php
class OpenSourceController
{
    public function index() {
        return view('openSource');
    }
}
```

## Validation

When using multiple rules for one field in a form request, avoid using `|`, always use array notation. Using an array notation will make it easier to apply custom rule classes to a field.

```php
// Good
public function rules()
{
    return [
        'email' => ['required', 'email'],
    ];
}

// Bad
public function rules()
{
    return [
        'email' => 'required|email',
    ];
}
```


All custom validation rules must use snake_case:

```php
Validator::extend('organisation_type', function ($attribute, $value) {
    return OrganisationType::isValid($value);
});
```

## Blade Templates

Indent using four spaces.

```html
<a href="/open-source">
    Open Source
</a>
```

Don't add spaces after control structures.

```html
@if($condition)
    Something
@endif
```

## Authorization

Policies must use camelCase.

```php
Gate::define('editPost', function ($user, $post) {
    return $user->id == $post->user_id;
});
```

```html
@can('editPost', $post)
    <a href="{{ route('posts.edit', $post) }}">
        Edit
    </a>
@endcan
```

Try to name abilities using default CRUD words. One exception: replace `show` with `view`. A server shows a resource, a user views it.

## Translations

Translations must be rendered with the `__` function. We prefer using this over `@lang` in Blade views because `__` can be used in both Blade views and regular PHP code. Here's an example:

```php
<h2>{{ __('newsletter.form.title') }}</h2>

{!! __('newsletter.form.description') !!}
```

## Naming Classes

Naming things is often seen as one of the harder things in programming. That's why we've established some high level guidelines for naming classes.

### Controllers

Generally controllers are named by the singular form of their corresponding resource and a `Controller` suffix. 

e.g. `UserController` or `EventDayController`

When writing non-resourceful controllers you might come across invokable controllers that perform a single action. These can be named by the action they perform again suffixed by `Controller`.

e.g. `PerformCleanupController`

### Resources (and transformers)

Both Eloquent resources and Fractal transformers are both singular or plural resources, depending if its representing a single object or a collection, suffixed with `Resource` or `Transformer` accordingly. This is to avoid naming collisions with models.

Work with CollectionResource whenever pagination is included, otherwise use JsonResource

### Jobs

A job's name should describe its action.

E.g. `CreateUser` or `PerformDatabaseCleanup`

### Events

Events will often be fired before or after the actual event. This should be very clear by the tense used in their name.

E.g. `ApprovingLoan` before the action is completed and `LoanApproved` after the action is completed.

### Listeners

Listeners will perform an action based on an incoming event. Their name should reflect that action with a `Listener` suffix. This might seem strange at first but will avoid naming collisions with jobs.

E.g. `SendInvitationMailListener`

### Commands

To avoid naming collisions we'll suffix commands with `Command`, so they are easiliy distinguisable from jobs.

e.g. `PublishScheduledPostsCommand`

### Mailables

Again to avoid naming collisions we'll suffix mailables with `Mail`, as they're often used to convey an event, action or question.

e.g. `AccountActivatedMail` or `NewEventMail`
