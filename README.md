![Luma PHP Logo](https://danielwinning.co.uk/images/luma.svg "Luma PHP")

# Luma PHP Framework

Welcome to Luma, a minimalist PHP framework designed for straightforward web development. This README introduces
the framework essentials.

### Table of Contents

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

**Luma** and **PHPEnv** go hand-in-hand to provide a smooth development environment, allowing you to start developing and
debugging as quickly as possible.

**Note: PHPEnv requires Docker Desktop to be installed on your system.**

To download and install **PHPEnv**, run the following command to install the package globally:

```shell
composer global require dannyxcii/phpenv
```

Next, ensure **Docker Desktop** is running.

Now, creating and running a new **Luma** app inside **PHPEnv** is as simple as running the following commands:

```shell
~> composer create-project lumax/luma luma-project
~> phpenv build luma-app ~/luma-project
```

The `phpenv build` command will display a local URL for your application. Setup complete.

Refer to the [PHPEnv README](https://github.com/DanielWinning/php-environment) for more detailed information about the
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

You can also define dynamic routes:

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

This is a very early version of Luma and as such does not have many features. Expanded documentation will be added in 
future releases. 