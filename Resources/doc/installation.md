# Installation

## Symfony 2.3.*

Require the bundle in your composer.json file:

```
{
    "require": {
        "whyte624/mailbox-analyzer-bundle": "~1.0",
    }
}
```

Register the bundle:

``` php
// app/AppKernel.php

public function registerBundles()
{
    return array(
        new Whyte624\MailboxAnalyzerBundle\Whyte624MailboxAnalyzerBundle(),
        // ...
    );
}
```

Install the bundle:

```
$ composer update
```
