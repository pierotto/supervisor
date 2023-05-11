# Supervisor
Symfony extension for generating configuration of supervisor programs.

## About

From the configuration it will generate a supervisor.conf containing the programs.

## Installation

Require the bundle and its dependencies with composer:

`$ composer require pierotto/supervisor`

Register the bundle:

```php
// app/AppKernel.php
public function registerBundles(): array
{
    $bundles = [
        new \Pierotto\SupervisorBundle\SupervisorBundle(),
    ];
}
```

## Usage

In your configuration, write all the programs that you want to run through supervisord.

Use the console command `$ php bin/console supervisor:generate` to start generating the supervisor.conf file. This file is generated in the `app/config` directory.

```yaml
supervisor:
    programs:
        program_name:
            command: 'php %kernel.project_dir%/bin/console rabbitmq:consumer -m 10 queue_name'
            numprocs: 1
            autorestart: true
            killasgroup: true
```
