# FlexPhpIO

An example of an I/O library. This project shows an alternative to
handling output through the router framework. Most router frameworks
expect the output to be passed back in the controllers in a "return"
statement. Then for example they would convert the array to JSON.

In my opinion that solution becomes too problematic, when changes and
new output modes are introduced later on to the project. Routing and
IO are better be separated into 2 distinct libraries.

Functions:

- Help handling output buffering
- Handy methods to change content type
- Access HTTP/GET parameters in a type-safe manner.
- Help with the JSON format (input / output)

For everything else, use the built-in functions of PHP.

Recommended IDE: [PhpStorm](https://www.jetbrains.com/phpstorm/)

## Example usages

```php
<?php declare(strict_types=1);

namespace MyProject;

use FlexPhpIO\Response;


Response::bufferStart();
Response::HtmlContentType();

echo '<html><body><p>Hello World!</p></body></html>';
```

```php
<?php declare(strict_types=1);

namespace MyProject;

use FlexPhpIO\Request;
use FlexPhpIO\Response;

try {
    Response::bufferStart();

    $input_data = Request::getPostedJson();
    $message = @trim((string)$input_data["message"]);

    if ($message == "") {
        throw new \Exception("Missing or invalid 'message' field in input.");
    }

    Response::printJson([
        "status" => "ok",
        "message" => $message
    ]);

} catch (\Exception $ex) {
    Response::printJson([
        "status" => "error",
        "message" => $ex->getMessage();
    ], 400);
}
```

An example for using it in a controller of [FlexPhpRouter](https://github.com/bolner/FlexPhpRouter):

- project1/src/Controller/items.php

```php
<?php declare(strict_types=1);

namespace MyProject\Controller;

use FlexPhpRouter\Router;
use FlexPhpIO\Response;


Router::get(
    "items/{id:int}/info",
    function (int $id) {
        Response::printJson([
            "item_id" => $id
        ]);
    }
);
```

Notes:
- When the output buffering is enabled, then you can change the headers
    any time, even after you have printed some output.
- When the output buffering is disable, then you cannot change the
    headers anymore after data was written to the output.
- In the FlexPhpRouter example, the output buffering should've already
    been set up in the main initialization file or entry point.
