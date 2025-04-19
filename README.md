# Supervisor Bundle
The Supervisor Bundle is a Symfony extension that simplifies the generation of supervisor program configurations.

## Requirements

- PHP 8.1+
- Symfony 6.0+

## About

This bundle takes your configuration and generates a supervisor.conf file containing the specified programs, along with their settings. You have the flexibility to choose the path and filename for this file.

## Installation

To get started, require the bundle and its dependencies using Composer:

```shell
composer require pierotto/supervisor
```

Then, register the bundle in your Symfony application:

```php
// bundles.php
return [
    Pierotto\SupervisorBundle\Infrastructure\Symfony\SupervisorBundle::class => ['all' => true],
];
```

## Usage

You can add this configuration to your Symfony project under `config/packages/supervisor.yaml`.

To configure programs that you want to run via supervisord, follow these steps:

1. In your Symfony configuration, define all the programs you wish to manage with Supervisor.
2. Use the console command to generate the supervisor.conf file:

```shell
php bin/console supervisor:generate path/to/supervisor.conf
```

This will generate a flat `supervisor.conf` file that can be directly included in your system's Supervisor configuration. It is recommended to run this command as part of your deployment process to ensure the generated configuration is always up to date.

Here's an example configuration in YAML format:

```yaml
supervisor:
    prefix: '' # Prefix for all program names (optional)
    programs:
        program_name: # Custom unique program name
            command: 'php %kernel.project_dir%/bin/console your_custom_command'
            numprocs: 1 # Number of process instances (optional, default: 1)
            autostart: true # Automatically start the program on Supervisor startup (optional, default: true)
            autorestart: true # Automatically restart the program if it exits or fails (optional, default: true)
            killasgroup: true # Kill the program's process group when stopping (optional, default: true)
            startretries: 10 # Number of retries to start the program in case of failure (optional, default: 3)
            user: 'www-data' # User under which the program should run (optional)
            directory: '/path/to/working/directory' # Working directory of the program (optional)
            stdout_logfile: '/path/to/stdout.log' # File for standard output (optional)
            stderr_logfile: '/path/to/stderr.log' # File for error output (optional)
            environment: KEY1="value1",KEY2="value2" # Environment variable definitions (optional)
            stopsignal: 'TERM' # Signal for program termination (optional)
            stopwaitsecs: 10 # Time limit for program termination (optional)
            priority: 999 # Program priority (optional)
            startsecs: 1 # Defines the duration, in seconds, a program must run after starting to be considered successful (optional)
```
