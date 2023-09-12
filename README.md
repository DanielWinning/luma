# Luma PHP Framework

Welcome to Luma, a minimalist PHP framework designed for straightforward web development. This basic README introduces
the framework essentials.

### Table of Contents
- <a href='#installation' style='text-decoration: underline; font-weight: 600;'>Installation</a>
- <a href='#routes' style='text-decoration: underline; font-weight: 600;'>Routing</a>
- <a href='#services' style='text-decoration: underline; font-weight: 600;'>Dependency Injection</a>
- <a href='#caching' style='text-decoration: underline; font-weight: 600;'>Caching</a>
- <a href='#logging' style='text-decoration: underline; font-weight: 600;'>Logging</a>

## <span id='installation'>Installation</span>
To begin using Luma, you can effortlessly set up the framework using Composer. Simply execute the following command in your terminal:

```bash
composer create-project lumax/luma
```

### <span id='routes'>Routes Configuration</span>

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

### <span id='services'>Services Configuration</span>

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

### <span id='caching'>Caching</span>

Luma optimizes performance by caching views in the `cache/views` directory, providing a simple way to reduce the 
overhead of rendering templates on each request.

### <span id='logging'>Logging</span>

Luma integrates with the `tracy/tracy` package for straightforward logging. Logs are stored locally in `logs/info.log`, 
allowing you to keep tabs on essential information about your application's behavior. Here's how you could use this in 
your application:

```php
Debugger::log('Exception: ' . $exception->getMessage());
```

This is a very early version of Luma and as such does not have many features. Expanded documentation will be added in 
future releases. 