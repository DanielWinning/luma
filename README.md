![Luma PHP Logo](https://danielwinning.co.uk/images/luma.svg "Luma PHP")

<div>
<!-- Version Badge -->
<img src="https://img.shields.io/badge/Version-1.2.0-blue" alt="Version 1.2.0">
</div>

Welcome to Luma, an _opinionated_ PHP framework.

```shell
composer create-project lumax/luma my-luma-project
```

New updated README for 1.X releases coming soon. Some of the information found
below may be outdated.

---

- [Installation](#installation)
- [Get Started](#get-started)
    - [Optional: Using PHPEnv + Docker](#optional-using-phpenv--docker)
- [Configuration](#configuration)
  - [Routes Configuration](#routes-configuration)
  - [Services Configuration](#services-configuration)
  - [The `.env` File](#the-env-file)
- [Controllers](#controllers)
  - [The LumaController](#the-lumacontroller)
- [Templating Engine](#templating-engine)
- [The Database Component](#the-database-component)
  - [Create Database Tables](#create-database-tables)
  - [Define Aurora Models](#define-aurora-models)
  - [Using Aurora Models](#using-aurora-models)
    - [Create](#create)
    - [Read](#read)
    - [Update](#update)
    - [Delete](#delete)
    - [The Query Builder](#the-query-builder)
- [Additional Features](#additional-features)
  - [HTTP Client](#http-client)
  - [Caching](#caching)
  - [Logging](#logging)

## Installation
To begin using Luma, you can effortlessly set up the framework using Composer. Simply execute the following command in your terminal:

```bash
composer create-project lumax/luma
```

## Get Started
When your project is installed via the `create-project` command, **Luma** runs post-setup commands to clean up
your project structure and help you get started as quickly as possible. This installs all required PHP libraries and NPM
dependencies inside your project, meaning your new **Luma** app can be run straight away.

### Optional: Using PHPEnv + Docker
> This section provides information on setting up a development environment for your Luma application. If you already
> have a PHP development environment or are comfortable running PHP applications locally, you can skip this section.

**Luma** and **PHPEnv** go hand-in-hand to provide a smooth development experience, allowing you to start building and
debugging as quickly as possible.

**Note: PHPEnv requires Docker Desktop to be installed on your system.**

To download and install **PHPEnv**, run the following command to install the package globally:

```bash
composer global require dannyxcii/phpenv
```

Next, ensure **Docker Desktop** is running.

Now, creating and running a new **Luma** app inside **PHPEnv** is as simple as running the following commands:

```bash
~> composer create-project lumax/luma luma-project
~> phpenv build luma-app ~/luma-project
```

The `phpenv build` command will display a local URL for your application. Click or copy/paste the URL into your browser 
to visit your new **Luma** app. Setup complete.

Refer to the [PHPEnv README](https://github.com/DanielWinning/phpenv) for more detailed information about the
library.

## Configuration
### Routes Configuration
Define your application's routes in the `config/routes.yaml` file. Here's an example route definition (which is included 
in your application by default):

```yaml
routes:
  index:
    path: '/'
    handler: ['App\Controller\AppController', 'index']
```

In the example above, we've defined a route for the root path, directing it to the `index` method of the 
`App\Controller\AppController` - check out your applications routes file and controller to see how these work.

In addition, we can also define dynamic routes:

```yaml
routes:
  user_profile:
    path: '/user/{id}'
    handler: 'App\Controller\UserController', 'profile'
```

```php
public function profile(int $id): Response
{
    return $this->respond(`User ${id}`);
}
```

### Services Configuration
Service definitions are stored in `config/services.yaml`. Below is an example service configuration:

```yaml
services:
  test_service:
    class: App\Service\TestService
    arguments: ['arg1', '@another_service']
```

Services can currently be injected into the constructor of other services using the `@service_id` argument as shown 
above. Services can also be injected into controller constructors. Services cannot currently be injected into controller 
methods.

### The `.env` File
**Luma** uses the `vlucas/phpdotenv` package to allow you to handle environment variables. Your new **Luma** app will
include some default environment variables, but you are free to use this file to define any custom environment variables
your application might need.

Variables within this file can be accessed within your application by accessing the `$_ENV` array.

Currently, your default `.env` contains a single used variable: `ENVIRONMENT=development`. Optional database credentials
are provided but commented out by default. Uncomment these values and supply your database credentials to allow your app
to connect to and work with a database.

## Controllers
We have briefly touched upon controllers in the [routes configuration](#routes-configuration) section where we learned
how to define our routes. This section aims to expand upon that and provide an overview of controllers within a **Luma**
application.

### The LumaController
The `LumaController` is the base class for all controllers within a **Luma** application. A simple "Hello, world!" `LumaController`
might look something like this:

```php
use Luma\Framework\Controller;
use Luma\HttpComponent;

class HelloWorldController extends LumaController
{
    /**
    * @return Response
    */
    public function sayHelloWorld(): Response
    {
        return $this->respond('Hello, world!');
    }
}
```

Next, we can define a new route that uses this method.

```yaml
# config/routes.yaml
routes:
  say_hello_world:
    path: '/hello-world'
    handler: ['App\Controller\HelloWorldController', 'sayHelloWorld']
```

This will ensure that all requests to `/hello-world` within your application will now be handled by the 
`HelloWorldController::sayHelloWorld` method. The `respond` method will echo the text (`Hello, world!`) to
the page when this endpoint is reached.

The `LumaController` also allows us to return a JSON response, great for building API's. This allows us to serialize arrays
or objects. To return a JSON response from your controller methods, use the built-in `LumaController::json` method:

```php
public function respondsWithJson(): Response
{
    $dataArray = [
       ...
    ];
    
    return $this->json($dataArray);
}
```

## Templating Engine
Luma uses the **Latte** templating engine in order to render dynamic HTML content. We can use the `render` 
method in the `LumaController` to render a view. This method takes two parameters: the name of the template file 
(with or without the `.latte` extension) and an array of parameters to pass to the template.

Here's an example of how you might use it in a controller:

```php
<?php

use Luma\Framework\LumaController;
use Luma\HttpComponent\Response;

class ExampleController extends LumaController
{
    public function index(): Response
    {
        $data = [
            'title' => 'Welcome to Luma',
            'description' => 'A minimalist PHP framework',
        ];

        return $this->render('index', $data);
    }
}
```

In this example, the `index` method of the `ExampleController` is rendering the `index.latte` template and passing in 
an array of data. This data can then be accessed in the `index.latte` template file like so:

```latte
{layout 'layout.latte'}

<h1>{$title}</h1>
<p>{$description}</p>
```

In this template, `{$title}` and `{$description}` are placeholders that get replaced with the corresponding values from 
the `$data` array passed to the `render` method.

Remember to place your `.latte` files in the `views` directory of your Luma project. The `render` method looks for 
templates in this directory by default.

## The Database Component
**Luma** allows you to easily connect to and interact with a database within your application, powered by the `lumax/aurora-db`
library. To start using a database with your **Luma** app, uncomment the `DATABASE_` variables in your `.env` file.

The `DATABASE_SCHEMA` and `DATABASE_DRIVER` variables are optional - if you wish to access all Schemas within your database
then you can omit the `SCHEMA` variable. If your application uses a **MySQL** database, there is no need to include the
`DRIVER` variable.

### Create Database Tables
To start, define your database tables and execute in your preferred console. Let's define a table where our users will be
stored:

```mysql
CREATE SCHEMA User;

USE User;

CREATE TABLE tblUser (
  intUserId INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  strUsername VARCHAR(60) NOT NULL,
  strEmailAddress VARCHAR(255) NOT NULL UNIQUE
);
```

This creates a simple test `tblUser` table in the `User` schema.

Now, let's create a table that references the `User.tblUser` table:

```mysql
CREATE SCHEMA Core;

USE Core;

CREATE TABLE tblArticle (
  intArticleId INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  strTitle VARCHAR(255) NOT NULL,
  intAuthorId INT(11) NOT NULL,
  dtmCreatedAt DATETIME NOT NULL DEFAULT NOW(),
  FOREIGN KEY (intAuthorId) REFERENCES User.tblUser(intUserId)
);
```

### The `Aurora` Model
Next, we can create `Aurora` models to interact with the new database tables we just created. Starting with the User:

```php
#[Schema('User')]
#[Table('tblUser')]
class User extends Aurora
{
    #[Identifier]
    #[Column('intUserId')]
    protected int $id;
    
    #[Column('strUsername')]
    private string $username;
    
    #[Column('strEmailAddress')]
    private string $emailAddress;
}
```

This basic `Aurora` model is already quite powerful, allowing you to perform CRUD operations directly against your database
table.

Here is an example of the Aurora model we could create for our `Article` table:

```php
#[Schema('Core')]
#[Table('tblArticle')]
class Article extends Aurora
{
    #[Identifier]
    #[Column('intArticleId')]
    protected int $id;
    
    #[Column('strTitle')]
    private string $title;
    
    #[Column('intAuthorId')]
    private User $author;
    
    #[Column('dtmCreatedAt')]
    private \DateTimeInterface $created;
    
    /**
    * @param string $title
    * 
    * @return void
    */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
```

### Using `Aurora` Models
As you can see from the examples above, an `Aurora` model is small, providing a simple *representation of our database 
rows* in PHP. Before I begin detailing the various ways you can use your `Aurora` classes to interact with the database,
it's important to understand a few important rules:

- All classes extending `Aurora` **MUST** have a primary identifier, indicated by the `#[Identifier]` attribute.
- All primary identifiers **MUST** be protected (or public, but not recommended).
- All properties that you wish to map to a database column **MUST** include a `#[Column($name)]` attribute.

#### Create
We can easily create new records in our database using the `create` method:

```php
// Create a new User instance. We can pass in property OR column names.
$user = User::create([
    'username' => 'Danny',
    'emailAddress' => 'test@test.com',
]);

// Save the new User to the database.
$user->save();

Article::create([
    'title' => 'First Post!',
    'author' => $user,
])->save();

Article::create([
    'title' => 'Second Post!',
    'author' => $user,
])->save();
```

#### Read
We can easily retrieve records with the `find` and `findBy` methods:

```php
$user = User::find(1); // Danny

$article = Article::findBy('title', 'Second Post!');

$articles = Article::all();

$article = Article::getLatest();

$articleCount = Article::count();
```

#### Update
The `save` method also allows us to update existing records:

```php
$article = Article::findBy('title', 'First Post!');

$article->setTitle('First Post... Improved!');

$article->save();
```

#### Delete
We can `delete` existing records really easily too:

```php
$article = Article::find(2);

$article->delete();
```

##### The Query Builder
In addition to the methods described above, **Luma** + **Aurora DB** allow you to build more complex queries thanks to the built-in
query builder. We start by building up our query:

```php
// Select all columns from the Article table
$article = Article::select();

// Execute the query
$article = $article->get();
```

The above snippet executes `SELECT * FROM Core.tblArticle`. The `select` method allows you to specify the columns you wish
to return and map. You can specify the name of the property or column:

```php
$article = Article::select('title')->get();
```

**Note:** The query builder will *always* return the primary identifier, so you do not need to specify this explicitly.

We can also specify conditions with the `whereIs`, `whereNot`, `whereIn` and `whereNotIn` methods. These can be chained, in
which case they are automatically converted to `AND`:

```php
// Returns an Article instance as there is only one result
$article = Article::select()
    ->whereIs('title', 'Second Post!')
    ->get();

// Returns an array (Article[]) as there is more than one result
$articles = Article::select()
    ->whereNot('title', 'Does not exist!')
    ->get();

// Returns an array (Article[])
$articles = Article::select()
    ->whereIn('id', [1, 2])
    ->get();

// Returns NULL as there are no results for this query
$articles = Article::select()
    ->whereNotIn('id', [1,2])
    ->get();
```

We can also add `orderBy` and `limit` conditions:

```php
$articles = Article::select()
    ->orderBy('id', 'DESC')
    ->get();

$article = Article::select()
    ->orderBy('id', 'DESC')
    ->limit(1)
    ->get();
```

## Additional Features
### HTTP Client
Luma provides a built-in `HttpClient` class to help simplify HTTP requests within your PHP. Using it is quite straightforward:

```php
$httpClient = new HttpClient();

// Methods also provided for POST, PUT, PATCH and DELETE
$response = $httpClient->get('/endpoint', $headersArray, $bodyString);
```

### Caching
Luma optimizes performance by caching views in the `cache/views` directory, providing a simple way to reduce the 
overhead of rendering templates on each request.

### Logging
Luma integrates with the `tracy/tracy` package for straightforward logging. Logs are stored locally in `logs/info.log`, 
allowing you to keep tabs on essential information about your application's behavior. Here's how you could use this in 
your application:

```php
Debugger::log('Exception: ' . $exception->getMessage());
```