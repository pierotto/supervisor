# Supervisor Bundle
The Supervisor Bundle is a Symfony extension that simplifies the generation of supervisor program configurations.

## Requirements

- PHP 8.4+
- Symfony 6.4, 7.4, or 8.x

### Version compatibility

| Bundle version | PHP | Symfony |
| --- | --- | --- |
| 3.x | 8.4+ | 6.4, 7.4, or 8.x |
| 2.x | 8.1+ | 6.x or 7.x |

Version 3.0 drops support for PHP versions below 8.4 and for Symfony versions older than 6.4.

Version 3.1 adds the optional `group` option, which wraps all generated programs in a supervisor
process group.

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
    group: '' # Wraps all programs in a supervisor process group (optional)
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

## Process groups

On a server that runs several projects under one shared `supervisord`, `supervisorctl stop all` and
`supervisorctl reload` hit every project at once. Setting `group` wraps the generated programs in a
[supervisor process group](http://supervisord.org/configuration.html#group-x-section-settings), so a
deployment can address only its own processes:

```yaml
supervisor:
    group: 'myapp'
    programs:
        worker:
            command: 'php %kernel.project_dir%/bin/console app:worker'
```

generates:

```ini
[program:worker]
process_name = %(program_name)s_%(process_num)02d
command = php /var/www/myapp/bin/console app:worker
numprocs = 1
autostart = true
autorestart = true
killasgroup = true
startretries = 3

[group:myapp]
programs = worker
```

The processes are then addressed as `myapp:worker`, and a deployment can restart just that group
without touching the other projects on the machine:

```shell
supervisorctl stop myapp:*
# ... deploy ...
php bin/console supervisor:generate config/supervisor.conf
supervisorctl reread          # reads the config files, restarts nothing
supervisorctl update myapp    # applies changes to this group only
supervisorctl start myapp:*
```

Note that `supervisorctl reload` restarts the whole `supervisord` daemon and therefore every project
on the machine; `reread` + `update <group>` is the group-scoped equivalent. Targeting `update` at a
group name requires supervisor 3.2 or newer.

`group` and `prefix` are independent, but combining them is redundant — the group already namespaces
the program names. Use one or the other, otherwise you end up with `myapp:myapp_worker`.

### Renaming existing programs

Adding `group` to a project that already runs under supervisor renames its processes from `worker`
(or `myapp_worker`) to `myapp:worker`. A group-scoped `supervisorctl update myapp` will not remove
the old, ungrouped programs, because they are not part of that group. Stop them by their old names
once and run an unscoped `supervisorctl reread && supervisorctl update`; that only restarts groups
whose configuration actually changed, so the other projects on the machine stay untouched.
