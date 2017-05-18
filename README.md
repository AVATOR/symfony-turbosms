AVATORTurbosmsBundle
========================

This bundle helps you to send sms in Symfony2/3 project.


Installation
------------

### Composer

Download LexxpavlovSettingsBundle and its dependencies to the vendor directory.

You can use Composer for the automated process:

```bash
$ composer require avator/symfony-turbosms
```

or manually add link to bundle into your `composer.json` and run `$ composer update`:

```json
{
    "require" : {
        "avator/symfony-turbosms": "~1.0"
    }
}
```

Composer will install bundle to `vendor/avator/symfony-turbosms` directory.

### Adding bundle to your application kernel

```php
<?php

// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new AVATOR\TurbosmsBundle\AVATORTurbosmsBundle(),
        // ...
    );
}
```

### Configuration

```yaml
# app/config/config.yml


avator_turbosms:
    # required
    login: 'your_login'
    # required
    password: 'your_password'
    # required
    sender: 'your_sender'
    # default value `false`, if debug == `true` message not sent
    debug: false
    # default value `true`
    save_to_db: true
```

Now you need create the tables in your database:

```bash
$ php bin/console doctrine:schema:update --dump-sql
```
or in Symfony2:
```bash
$ php app/console doctrine:schema:update --dump-sql
```

This will show SQL queries for creating of tables in the database. You may manually run these queries.

> **Note.**
You may also execute `php bin/console doctrine:schema:update --force` command, and Doctrine will create needed
tables for you. But I strongly recommend you to execute `--dump-sql` first and check SQL, which Doctrine will execute.


Use in controller
-----

```php
<?php 

namespace App\YourBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        .....
        $this->get('avator_turbosms.turbosms')->send("test", "+380XXXXXXXXX");
        .....
    }
}
```


TODO
-----
* Translate message
* Save log to file
* SonataAdmin

## License

**symfony-turbosms** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.