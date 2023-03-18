<p align="center"><a href="https://formewp.github.io" target="_blank"><img src="https://formewp.github.io/logo.svg" width="400"></a></p>

# Forme WordPress Framework

Forme is an MVC framework for WordPress. This is the core Forme Framework library, which is used by the Plugin, Theme and CodeGen components.

[Click here for Documentation](https://formewp.github.io)

## Development

For development run `phive install --force-accept-unsigned` followed by `composer install`.

Tools are in `./tools` rather than `./vendor/bin`

You also need [git cliff](https://github.com/orhun/git-cliff) for generating changelogs and [pcov](https://github.com/krakjoe/pcov) to generate coverage stats for infection to measure against.

The useful ones are set up as composer scripts. Tests should run automatically on commit.

```sh
composer test # run pest
composer test:setup # set up WP installation for integration testing
composer stan # run phpstan on src
composer rector:check # rector dry run on src
composer rector:fix # rector on src
composer cs:check # php cs fixer dry run on src
composer cs:fix # php cs fixer on src
composer changelog # run git cliff
composer hooks # install git hooks (will run on composer install automatically)
composer bump:version # bump to the next patch version - can also take argument "minor" or "major"
composer infection # run infection on src
composer infection:log # run infection on src and log to infection.html
```
