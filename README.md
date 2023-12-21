A git version getter
========================================

A helper class to get the current git version of the project.

Expects either a `version` file to exist in the `base_path()` of your project
containing a version string, or the `git` binary to be available.

Installation
------------

Require it in your Laravel/Lumen project:

    composer require bitboss-hub/laravel-git-version-output

### Optional packages

This module uses [Symfony's Process component][process] if available,
or falls back to `shell_exec` otherwise.
So if your deployment environment has `shell_exec` disabled,
you can work around this by installing `symfony/process`.

[process]: https://github.com/symfony/process

Use
---

You can get the git version array with

    \BitBossHub\LaravelGitVersionOutput\GitVersionOutputHelper::getInformations()

It will output an array
```
  "app_name" => "Laravel"
  "tag" => "v1.0"
  "commit" => "g796af4b6"
  "since_tag" => "5009"
  "build_date" => "2023-12-21 10:26:47"
```

The app's name is taken from `Config::get('app.name', 'app')`, so you can
configure it in your `config/app.php` file or leave it as the default of `app`.

### Recommended usage pattern

Ensure your git tags are pushed to your servers
so that the versions are described properly.

During development and possibly in staging environments
allow the version to be determined automatically
(this is done via `git describe`).

### How it works
The first time this package is called, it creates a `version` file to your project directory.

When this `version` file exists the package will use its contents.

Ensure to add it to the `.gitignore` file
so your working tree stays clean and you don't accidentally commit it.

As part of your production deployment procedure remember to delete this file every time a new deploy is executed.
