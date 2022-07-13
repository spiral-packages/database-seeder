# Database Seeder

[![PHP](https://img.shields.io/packagist/php-v/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spiral-packages/database-seeder/run-tests?label=tests&style=flat-square)](https://github.com/spiral-packages/database-seeder/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)

The package provides the ability to seed your database with data using seed classes.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.1+
- Spiral framework 3.0+
 
## Installation

You can install the package via composer:

```bash
composer require spiral-packages/database-seeder --dev
```

After package install you need to register bootloader from the package.

```php
protected const LOAD = [
    // ...
    \Spiral\DatabaseSeeder\Bootloader\DatabaseSeederBootloader::class,
];
```

> Note: if you are using [`spiral-packages/discoverer`](https://github.com/spiral-packages/discoverer), 
> you don't need to register bootloader by yourself.

## Usage

### Creating entities by Factory

Provides the ability to easily create entities, for example, in tests in your application or packages.

You need to create a factory class, extend it from `Spiral\DatabaseSeeder\Factory\AbstractFactory` and implement 
two methods `entity` and `definition`. Method `entity` must return a fully qualified class name. 
Method `definition` must return an array with generation rules. Keys - properties in the target class. 
Values - property value or calling method that can generate property value (for example, method from Faker).

```php
<?php

declare(strict_types=1);

namespace Database\Seeder;

use App\Entity\User;
use Spiral\DatabaseSeeder\Factory\AbstractFactory;

class UserFactory extends AbstractFactory
{
    public function entity(): string
    {
        return User::class;
    }

    public function makeEntity(array $definition): User
    {
        return new User($definition['username']);
    }

    public function admin(): self
    {
        return $this->state(fn(\Faker\Generator $faker, array $definition) => [
            'admin' => true,
        ]);
    }

    public function fromCity(string $city): self
    {
        return $this->state(fn(\Faker\Generator $faker, array $definition) => [
            'city' => $city,
        ]);
    }

    public function withBirthday(\DateTimeImmutable $date): self
    {
        return $this->entityState(static function (User $user) use ($date) {
            $user->birthday = $date;

            return $user;
        });
    }

    public function definition(): array
    {
        return [
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            'birthday' => \DateTimeImmutable::createFromMutable($this->faker->dateTime()),
            'comments' => CommentFactory::new()->times(3)->create(), // Can use other factories.
            // Be careful, circular dependencies are not allowed!
        ];
    }
}
```

After that, you can use this factory in your code. For example:
```php
<?php

declare(strict_types=1);

namespace Database\Seeder;

use Spiral\DatabaseSeeder\Seeder\AbstractSeeder;

class UserTableSeeder extends AbstractSeeder
{
    public function run(): \Generator
    {
        foreach (UserFactory::new()->times(100)->create() as $user) {
            yield $user;
        }
        
        yield UserFactory::new()
            ->state(static fn(\Faker\Generator $faker) => ['admin' => $faker->bool])
            ->createOne();
        
        yield UserFactory::new()
            ->entityState(static function(User $user) {
                return $user->markAsDeleted();
            })
            ->createOne();
        
        yield UserFactory::new()->admin()->createOne();
        
        yield UserFactory::new()->fromCity('New York')->createOne();
        
        yield UserFactory::new()
            ->withBirthday(new \DateTimeImmutable('2010-01-01 00:00:00'))
            ->createOne();
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Maxim Smakouz](https://github.com/spiral-packages)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
