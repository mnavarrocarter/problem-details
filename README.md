ApiException (RFC7807 Implementation)
=====================================

ApiException is an **abstract exception class for php** that implements the 
[RFC7807](https://tools.ietf.org/html/rfc7807) problem details structure.

It is ment to be extended into other exception classes that define specific error
types, according to the RFC.

### Installing
Simply do:
    
    composer require mnavarrocarter/problem-details

## Usage

### Creating your custom Exception Types
For the different problem types in your api, you must create meaningful exception
classes that will extend this abstract one, and override the constructor to set
your custom type, title and, if you want, status code.

```php
<?php

namespace App\Errors;

use MNC\ProblemDetails\ApiException;

class AuthenticationProblemException extends ApiException
{
    public function __construct(string $detail = '', array $extra = [], ?\Throwable $previous = null)
    {
        $type = 'errors/authentication';
        $title = 'Authentication Error';
        $statusCode = 401;
        parent::__construct($type, $title, $statusCode, $detail, $extra, $previous);
    }
}

```

### Using Factory Pattern to simplify error creation.

Now, with your class created, you can use the factory pattern to quickly and simply
create new instances of the custom error type you just created.

```php
<?php

namespace App\Errors;

use MNC\ProblemDetails\ApiException;

class AuthenticationProblemException extends ApiException
{
    public function __construct(string $detail = '', array $extra = [], ?\Throwable $previous = null)
    {
        $type = 'errors/authentication';
        $title = 'Authentication Error';
        $statusCode = 401;
        parent::__construct($type, $title, $statusCode, $detail, $extra, $previous);
    }
    
    public static function invalidCredentials(array $sentCredentials)
    {
        return new self(
            'Invalid credentials',
            $sentCredentials  
        );
    }
}

```

Then, in your client code:

```php
<?php

namespace App\Services;

use App\Error\AuthenticationProblemException;

class Authenticator extends ApiException
{
    public function authenticate(array $sentCredentials)
    {
        if ($this->areValidCredentials($sentCredentials)) {
            return $this->findUser($sentCredentials);
        }
        throw AuthenticationProblemException::invalidCredentials($sentCredentials);
    }
}

```

Then, at your controller level, you can catch these exceptions to provide a response.
Or, if you use frameworks like Symfony, you can configure an Exception Listener.

```php

<?php

namespace App\Controller;

use MNC\ProblemDetails\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SomeController extends Controller
{
    public function someAction(Request $request)
    {
        try {
            $variable = $this->tryAction($request);
        } catch (ApiException $e) {
            return $this->json($e, $e->getStatusCode());
        }
        return $variable;
    }
}
```

If you noticed, the ApiException implements PHP's `\JsonSerializable` interface. So
it is easily serialized with a simple `json_encode()`. If you want to just normalize
the object, you can call the `toArray()` method.

### Modifiying the implementation

tldr; You can't. You can implement the interface though.

This implementation is very closed. Property visibility is hidden from you,
and you can only interact with the properties via the public methods.

Also, the implementation methods are declared final, so you can't override them.
This will protect you from breaking anything, as this class is developed to always
provide a meaningful response by checking stuff in getters and setters.

The interface is there in case you want to create your own implementation.