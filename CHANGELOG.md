# Changelog

All notable changes to this project will be documented in this file.

## [2.7.2] - 2022-12-15

### Miscellaneous Tasks

- Use symfony string instead of jawira

## [2.7.1] - 2022-12-13

### Bug Fixes

- Controller abstract signature

### Miscellaneous Tasks

- Bump version

## [2.7.0] - 2022-12-13

### Features

- End to end test routes

### Miscellaneous Tasks

- Bump version

## [2.6.2] - 2022-12-08

### Miscellaneous Tasks

- Replace string u func with class inst
- Bump version

## [2.6.1] - 2022-12-04

### Features

- Wp cli forme queue handle name

### Miscellaneous Tasks

- Bump version

## [2.6.0] - 2022-12-04

### Features

- Named job queues

### Miscellaneous Tasks

- Bump version

### Testing

- Job queue test suite

## [2.5.8] - 2022-11-29

### Miscellaneous Tasks

- Downgrade monolog for now
- Bump version

## [2.5.7] - 2022-11-29

### Miscellaneous Tasks

- Update deps
- Bump version

## [2.5.6] - 2022-11-22

### Bug Fixes

- Also check frequency

### Miscellaneous Tasks

- Bump version

## [2.5.5] - 2022-11-22

### Features

- Handle started jobs with stop

### Miscellaneous Tasks

- Bump version

## [2.5.4] - 2022-11-16

### Miscellaneous Tasks

- Only load fields/options if acf is available
- Bump version

## [2.5.3] - 2022-10-19

### Bug Fixes

- More null coalesce

### Miscellaneous Tasks

- Bump version

## [2.5.2] - 2022-10-19

### Bug Fixes

- Coerce queue args to null

### Miscellaneous Tasks

- Bump version

## [2.5.1] - 2022-10-14

### Miscellaneous Tasks

- Rollback should fully rollback
- Bump version

## [2.5.0] - 2022-10-14

### Features

- Add a rollback method to migrations class

### Miscellaneous Tasks

- Bump version

## [2.4.8] - 2022-10-14

### Miscellaneous Tasks

- Sqlite for testing
- Bump version

## [2.4.7] - 2022-10-01

### Miscellaneous Tasks

- Update deps including twig security patch
- Bump version

## [2.4.6] - 2022-09-19

### Bug Fixes

- Handle missing child posts

### Miscellaneous Tasks

- Bump version

## [2.4.5] - 2022-09-16

### Bug Fixes

- Str ends with correction

### Miscellaneous Tasks

- Bump version

## [2.4.4] - 2022-09-16

### Features

- Post meta magic prop

### Miscellaneous Tasks

- Bump version

## [2.4.3] - 2022-09-16

### Dev Worflow

- Remove irrelevant codegen bump script

### Features

- Meta magic prop

### Miscellaneous Tasks

- Bump version

## [2.4.2] - 2022-09-15

### Bug Fixes

- One router per custom handler

### Miscellaneous Tasks

- Bump version

## [2.4.1] - 2022-09-04

### Bug Fixes

- Missing import and allow string

### Miscellaneous Tasks

- Bump version

## [2.4.0] - 2022-09-04

### Features

- Make queue flexible for standalone usage

### Miscellaneous Tasks

- Bump version

## [2.3.2] - 2022-08-23

### Bug Fixes

- Magic resolver

### Miscellaneous Tasks

- Bump version

## [2.3.1] - 2022-08-23

### Dev Worflow

- Update phive deps

### Features

- Dot magic in legacy plates

### Miscellaneous Tasks

- Bump version

## [2.3.0] - 2022-08-22

### Features

- Magic index view resolve

### Miscellaneous Tasks

- Bump version

## [2.2.2] - 2022-07-27

### Features

- Handle testing env in connection

### Miscellaneous Tasks

- Bump version

### Testing

- Forme constants to stub
- Stub setup for phpstan and tests

## [2.2.1] - 2022-07-22

### Dev Worflow

- Update php cs fixer rules

### Miscellaneous Tasks

- Add typing to strategy
- Bump version

### Refactor

- Hook to strategy for consistency

## [2.2.0] - 2022-07-05

### Dev Worflow

- Update git cliff
- Update the conventional commits config

### Features

- Acf enums

### Miscellaneous Tasks

- Bump version

## [2.1.13] - 2022-07-03

### Miscellaneous Tasks

- Add a router exception
- Add a router strategy excpetion
- Bump version

### Testing

- Test for router strategy factory

## [2.1.12] - 2022-07-03

### Bug Fixes

- Fix dist path logic

### Miscellaneous Tasks

- Bump version

### Testing

- Rename test groupings
- Tests for the assets helper

## [2.1.11] - 2022-07-02

### Dev Worflow

- Use ramsey conventional

### Miscellaneous Tasks

- Bump version

## [2.1.10] - 2022-07-02

### Miscellaneous Tasks

- Update php deps
- Use local php cs fixer
- Bump version

### Testing

- Loader tests

## [2.1.9] - 2022-06-30

### Miscellaneous Tasks

- Bump version

### Testing

- Adds wp integration tests and adds hooksetup

## [2.1.8] - 2022-06-30

### Bug Fixes

- Action not filter

### Miscellaneous Tasks

- Bump version

## [2.1.7] - 2022-06-30

### Features

- Move forme queue cmd to core

### Miscellaneous Tasks

- Regex escape chars to help editor
- Bump version

### Refactor

- Extract hook add from core hooks

### Testing

- Token class

## [2.1.6] - 2022-06-28

### Miscellaneous Tasks

- Bump version

### Refactor

- Update list to destruct syntax

### Testing

- Update test system

## [2.1.5] - 2022-06-28

### Bug Fixes

- Menu item url

### Documentation

- Changelog

## [2.1.4] - 2022-06-24

### Bug Fixes

- Allow callable array in router map
- Callable array in all route methods

### Documentation

- Changelog

## [2.1.3] - 2022-06-23

### Documentation

- Changelog

### Miscellaneous Tasks

- Typehint forme server request too

## [2.1.2] - 2022-06-21

### Bug Fixes

- Remove broken id sugar
- Collect typehint

### Features

- Mutators for title and slug

## [2.1.1] - 2022-06-21

### Features

- Post syntactic sugar

## [2.1.0] - 2022-06-21

### Features

- Post children

## [2.0.11] - 2022-06-20

### Bug Fixes

- Variable assignment rule

### Documentation

- Changelog

## [2.0.10] - 2022-06-20

### Bug Fixes

- Multi statement regex

### Documentation

- Update changelog

### Miscellaneous Tasks

- Update changelog

## [2.0.9] - 2022-06-19

### Documentation

- Update changelog

### Features

- Additional validator rule

## [2.0.8] - 2022-06-19

### Documentation

- Update changelog

### Features

- Improve usefulness of template error exception

## [2.0.7] - 2022-06-19

### Bug Fixes

- Validator logic

### Documentation

- Update changelog

## [2.0.6] - 2022-06-19

### Documentation

- Update changelog

### Features

- Add some line numbers to validator

### Miscellaneous Tasks

- Typo

### Refactor

- Validation config pattern

## [2.0.5] - 2022-06-17

### Bug Fixes

- Acf might be false

### Documentation

- Update changelog

## [2.0.4] - 2022-06-17

### Bug Fixes

- Typing in plates
- Nullable types

### Features

- Use local phpcs and add composer scripts

### Miscellaneous Tasks

- Add wp cli stubs
- Remove cli from phpstan config
- Add back wp cli stubs

### Refactor

- Rector pass

## [2.0.3] - 2022-05-07

### Documentation

- Changelog

### Features

- Add route params to the query

## [2.0.2] - 2022-05-06

### Documentation

- Update changelog

### Miscellaneous Tasks

- Update deps

## [2.0.1] - 2022-05-06

### Bug Fixes

- Dont replace body with params

### Documentation

- Changelog

### Refactor

- Typing

## [2.0.0] - 2022-04-28

### Miscellaneous Tasks

- Remove deprecated functionality

## [1.3.6] - 2022-04-27

### Refactor

- Return types
- Typing

## [1.3.5] - 2022-04-27

### Bug Fixes

- Controller interface signature

## [1.3.4] - 2022-04-27

### Bug Fixes

- Handler interface and trait

## [1.3.3] - 2022-04-19

### Documentation

- Update changelog

### Miscellaneous Tasks

- Update composer deps

## [1.3.2] - 2022-04-19

### Documentation

- Update changelog

### Features

- Install and configure rector

### Refactor

- Automated rector plus manual

## [1.3.1] - 2022-03-19

### Documentation

- Update changelog
- Update changelog

### Features

- Delay template include for woo commerce

### Miscellaneous Tasks

- Increment min phinx version
- Add git cliff config

### Refactor

- Fix typing

## [1.3.0] - 2022-02-18

### Documentation

- Attribution comment

### Miscellaneous Tasks

- Update laravel deps
- Update blade integration for laravel 9

## [1.2.7] - 2022-02-17

### Miscellaneous Tasks

- Update symfony deps

### Refactor

- Remove dep on abandoned jenssegers blade

## [1.2.6] - 2022-02-17

### Miscellaneous Tasks

- Update phpstan config

## [1.2.5] - 2022-02-17

### Miscellaneous Tasks

- Update acf pro stubs

## [1.2.4] - 2022-02-17

### Documentation

- Update changelog

### Miscellaneous Tasks

- Type custom postable property
- Update deps

## [1.2.3] - 2022-02-03

### Bug Fixes

- Lower case blade directive

## [1.2.2] - 2022-02-03

### Features

- Add local directives

## [1.2.1] - 2022-02-01

### Miscellaneous Tasks

- Add strict typing to directives

### Refactor

- Remove unneeded comments and update typing
- Better method names
- Remove superfluous comments

## [1.2] - 2022-02-01

### Documentation

- Add changelog

## [1.1] - 2021-12-27

### Features

- Update assets to handle npm run serve

## [1.0.1] - 2021-12-25

### Documentation

- Add logo and doc link to the readme

### Miscellaneous Tasks

- Remove php 7.3

## [1.0] - 2021-12-24

### Features

- Initial commit
- Add license to composer json

<!-- generated by git-cliff -->
