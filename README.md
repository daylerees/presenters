# Presenters

The presenters project will allow you to build multi-dimensional arrays with conditional keys and nested items. This will allow you to enforce the structure of the data, while allowing for optional keys.

It's best explained through an example:

```php
<?php

class MyPresenter extends \Rees\Presenters\Presenter
{
    private $rawData;

    public function __construct($rawData)
    {
        $this->rawData = $rawData;
    }

    public function present()
    {
        return [
            'username'  => $rawData->user_name,
            'email'     => $rawData->email_address,
            'followers' => $rawData->followers
        ];
    }
}
```

A call to the `$myPresenter->getPresentedPayload()` will result in the following:

```php
[
    'username'  => 'dayle',
    'email'     => 'me@daylerees.com',
    'followers' => 12000
]
```

But what if we want the email to be optional based on a condition? For example, if the user is not verified. We could build the payload in pieces, or do some unset fiddling, but it might upset the order of our fields. Let's use the `when()` method instead:

```php
<?php

class MyPresenter extends \Rees\Presenters\Presenter
{
    private $rawData;

    public function __construct($rawData)
    {
        $this->rawData = $rawData;
    }

    public function present()
    {
        return [
            'username'  => $rawData->user_name,
            'email'     => $this->when($rawData->active)->embed($rawData->email_address),
            'followers' => $rawData->followers
        ];
    }
}
```

This time, we receive:

```php
[
    'username'  => 'dayle',
    'followers' => 12000
]
```

This is because our user is inactive.

You can add as many conditionals as you want. Here's some other neat tricks.

```php
// Embed a default value when the condition fails.
$this->when($condition)->embed($value, $default);
```

```php
// Nested embeds are perfectly fine!
$this->when($condition)->embed([
    'foo' => $this->when($anotherCondition)->embed('hello!')
]);
```

```php
// Use callables for conditionals.
$this->when(function () { return true; })->embed('bang!');
```

```php
// Use condition value objects, see `Rees\Presenters\Condition` interface.
$this->when(new MyCondition($conditional, $values))->embed('bang!');
```

```php
// Great for building API response payloads!
'email' => $this->when(new UserHasScope('user:email'))->embed($user->email);
```
