# Database Seeder

[![PHP](https://img.shields.io/packagist/php-v/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spiral-packages/database-seeder/run-tests?label=tests&style=flat-square)](https://github.com/spiral-packages/database-seeder/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spiral-packages/database-seeder.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/database-seeder)

When you build apps that use databases, it's really important to make sure your database works right. This means
checking if it stores, changes, and gives back data the way it's supposed to. But sometimes, testing databases can be
tricky and a bit boring. You might have to write a lot of complicated commands and be very careful about how data is
added or removed.

## What the package offers

1. **Easy Testing:** With Spiral, you don't need to deal with complex commands. The tools are simple to use, which means
   your tests are easier to write and understand.

2. **Different Ways to Reset Your Database:** After you test something, you need to make your database clean again for
   the next test. Spiral has different ways to do this, like the Transaction, Migration, Refresh, and SqlFile methods.
   Each one has its own way of working, so you can choose what fits best for your test.

3. **Seeders and Factories:** These are like shortcuts to fill your database with test data. This data looks like the
   real data you would use in your app. You can quickly set up the data you need for testing with these tools.

4. **Checking Your Database:** After you do something in your database, you want to make sure it worked right. Spiral's
   tools let you check if the data is there or not, and if your database structure is correct.

It's a great for any developer, no matter how much experience you have. They help make sure
your database is doing what it should, which is really important for your app to work well.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.1+
- Spiral framework 3.7+

## Documentation, Installation, and Usage Instructions

See the [documentation](https://spiral.dev/docs/testing-database) for detailed installation and usage instructions.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
