# Changelog

All notable changes to this project will be documented in this file.

## [3.15.0] - 2025-07-11

### Miscellaneous Tasks

- Update tests and cs fixer
- Attribute for console v7

## [3.14.0] - 2025-06-22

### Miscellaneous Tasks

- Laravel 12 dependency support
- Bump version

## [3.13.1] - 2025-03-07

### Miscellaneous Tasks

- Plates linter handle phtml
- Bump version

## [3.13.0] - 2025-03-07

### Features

- Allow extension in views

### Miscellaneous Tasks

- Bump version

## [3.12.3] - 2025-03-06

### Miscellaneous Tasks

- Also symfony yaml 6 for 8.1
- Bump version

## [3.12.2] - 2025-03-06

### Miscellaneous Tasks

- Allow older version of symfony string
- Bump version

## [3.12.1] - 2025-03-06

### Features

- Template validation can now be switched off

### Miscellaneous Tasks

- Remove rendundant test case class
- Bump version

## [3.12.0] - 2025-02-20

### Miscellaneous Tasks

- Support laravel 11
- Fix illuminate 11 support and drop 9
- More dependency updates
- Update test deps
- Remove wp test utils
- Update pest
- Bump version

## [3.11.0] - 2025-02-13

### Features

- Explicitly include wp globals

### Miscellaneous Tasks

- Bump version

## [3.10.7] - 2025-02-11

### Miscellaneous Tasks

- Keep recurring failed jobs
- Complete failed jobs
- Bump version

## [3.10.6] - 2025-02-10

### Features

- Handle queue job failure

### Miscellaneous Tasks

- Use stop instead of marking failed recurring as completed
- Bump version

### Refactor

- Try catch around the handle method only

### Testing

- Failed job tests

## [3.10.5] - 2024-12-04

### Miscellaneous Tasks

- Allow asset plugin/themeable over-rides
- Bump version

## [3.10.4] - 2024-11-22

### Miscellaneous Tasks

- Ensure empty object default for queue args
- Bump version

## [3.10.3] - 2024-11-22

### Miscellaneous Tasks

- Allow passing arguments to queuable stop
- Bump version

## [3.10.2] - 2024-11-22

### Features

- Add arguments as uniqueness heurisitic

### Miscellaneous Tasks

- Bump version

### Testing

- Tests for queue uniqueness including arguments

## [3.10.1] - 2024-10-11

### Bug Fixes

- Type cant be defined

### Miscellaneous Tasks

- Bump version

## [3.10.0] - 2024-10-11

### Features

- Wrangle queue:run deprecate wpcli version

### Miscellaneous Tasks

- Bump version

## [3.9.4] - 2024-09-11

### Miscellaneous Tasks

- Update deps
- Bump version

## [3.9.3] - 2024-09-03

### Miscellaneous Tasks

- Explicitly require laravel/prompts
- Bump version

## [3.9.2] - 2024-09-02

### Bug Fixes

- Wrangle rel from cwd not dir

### Miscellaneous Tasks

- Bump version

## [3.9.1] - 2024-09-02

### Miscellaneous Tasks

- Rename wrangle
- Bump version

## [3.9.0] - 2024-08-28

### Features

- New forme cli poc

### Miscellaneous Tasks

- Bump version

## [3.8.13] - 2024-08-17

### Documentation

- Changelog

### Miscellaneous Tasks

- Allow downgrading monolog for conflict resolution
- Bump version

## [3.8.12] - 2024-08-12

### Bug Fixes

- Handle empty menu

## [3.8.11] - 2024-08-12

### Miscellaneous Tasks

- Order menu items
- Bump version

## [3.8.10] - 2024-07-31

### Miscellaneous Tasks

- Return null instead of error on rel asset fail
- Bump version

## [3.8.9] - 2024-07-05

### Bug Fixes

- Monolog 3 typing

### Miscellaneous Tasks

- Update sqlite db stub
- Bump version

### Testing

- Update tests for 24 default theme

## [3.8.8] - 2024-06-04

### Miscellaneous Tasks

- Update dependencies
- Bump version

## [3.8.7] - 2024-06-04

### Miscellaneous Tasks

- Remove faulty exec permission
- Bump version

## [3.8.6] - 2024-01-09

### Miscellaneous Tasks

- Update deps
- Bump version

## [3.8.5] - 2023-12-02

### Miscellaneous Tasks

- Bump version

### Performance

- Child post query simplification

## [3.8.4] - 2023-12-01

### Features

- Update release action

### Miscellaneous Tasks

- Bump version

## [3.8.3] - 2023-12-01

### Features

- Also normalise post content

### Miscellaneous Tasks

- Update deps
- Bump version

## [3.8.2] - 2023-11-29

### Miscellaneous Tasks

- Slighly later for template include for elementor compat
- Bump version

## [3.8.1] - 2023-09-22

### Bug Fixes

- Update deps and fix phinx 2.0 which has gone away

### Miscellaneous Tasks

- Bump version

## [3.8.0] - 2023-09-18

### Miscellaneous Tasks

- Update deps
- Update testing libraries
- Update cake dependencies
- Bump version

## [3.7.1] - 2023-08-20

### Miscellaneous Tasks

- Update illuminate version
- Bump version

## [3.7.0] - 2023-08-20

### Miscellaneous Tasks

- Allow laravel 10
- Bump version

## [3.6.1] - 2023-08-01

### Bug Fixes

- Explicitly set event timestamps to created only

### Miscellaneous Tasks

- Bump version

## [3.6.0] - 2023-08-01

### Bug Fixes

- Event does not have updated_at

### Miscellaneous Tasks

- Bump version

## [3.5.2] - 2023-08-01

### Miscellaneous Tasks

- Make events extensible
- Bump version

## [3.5.1] - 2023-07-22

### Miscellaneous Tasks

- Include altorouter directly due to upstream global namespace
- Bump version

## [3.5.0] - 2023-06-17

### Miscellaneous Tasks

- Update laminas and rector
- Bump version

## [3.4.4] - 2023-06-17

### Miscellaneous Tasks

- Update deps
- Bump version

## [3.4.3] - 2023-06-14

### Miscellaneous Tasks

- Replace datetime with carbon
- Bump version

## [3.4.2] - 2023-06-14

### Features

- Ability to see when token expires

### Miscellaneous Tasks

- Bump version

## [3.4.1] - 2023-06-14

### Miscellaneous Tasks

- Update composer deps
- Bump version

## [3.4.0] - 2023-06-14

### Features

- Auth token custom expiry
- Force invalidate all tokens by name/scope
- Auth tokens soft delete

### Miscellaneous Tasks

- Bump version

## [3.3.5] - 2023-05-16

### Features

- Slightly more helpful error message in template lint

### Miscellaneous Tasks

- Bump version

## [3.3.4] - 2023-05-16

### Miscellaneous Tasks

- Add token interface
- Bump version

## [3.3.3] - 2023-05-02

### Features

- Post excerpt

### Miscellaneous Tasks

- Bump version

## [3.3.2] - 2023-05-02

### Miscellaneous Tasks

- Asset time from manifest.json if dist
- Bump version

## [3.3.1] - 2023-04-28

### Features

- Meta relations and typing

### Miscellaneous Tasks

- Generics on the container helpers
- Bump version

## [3.3.0] - 2023-04-25

### Miscellaneous Tasks

- Specify laminas version due to cve
- Bump version

## [3.2.4] - 2023-04-25

### Miscellaneous Tasks

- Update deps
- Bump version

## [3.2.3] - 2023-04-20

### Features

- Allow mysql in testing environment

### Miscellaneous Tasks

- Bump version

## [3.2.2] - 2023-04-07

### Features

- User attribute sugar

### Miscellaneous Tasks

- Bump version

## [3.2.1] - 2023-03-20

### Miscellaneous Tasks

- No updated at for events
- Bump version

## [3.2.0] - 2023-03-20

### Features

- Event model and log handler

### Miscellaneous Tasks

- Bump version

## [3.1.1] - 2023-03-19

### Documentation

- Update readme

### Miscellaneous Tasks

- Add vscode settings for dev
- Bump version

### Refactor

- Update bootstrap flow

## [3.1.0] - 2023-03-14

### Miscellaneous Tasks

- Downgrade monolog and cake deps
- Bump version

## [3.0.14] - 2023-03-13

### Miscellaneous Tasks

- Update deps
- Bump version

## [3.0.13] - 2023-03-13

### Bug Fixes

- Try without file

### Miscellaneous Tasks

- Bump version

## [3.0.12] - 2023-03-13

### Bug Fixes

- Release fetch depth

### Miscellaneous Tasks

- Bump version

## [3.0.11] - 2023-03-13

### Miscellaneous Tasks

- Try file only
- Bump version

## [3.0.10] - 2023-03-13

### Bug Fixes

- Workflow typo

### Miscellaneous Tasks

- Bump version

## [3.0.9] - 2023-03-13

### Bug Fixes

- Release workflow file output

### Miscellaneous Tasks

- Bump version

## [3.0.8] - 2023-03-13

### Miscellaneous Tasks

- Try writing release changelog to file
- Bump version

## [3.0.7] - 2023-03-13

### Miscellaneous Tasks

- Try latest for release changelog
- Bump version

## [3.0.6] - 2023-03-13

### Documentation

- Update changelog

### Miscellaneous Tasks

- Strip footer from release changelog
- Bump version

## [3.0.5] - 2023-03-13

### Miscellaneous Tasks

- Release changelog

## [3.0.4] - 2023-03-12

### Bug Fixes

- Uncomment plates lint exception check

### Miscellaneous Tasks

- Bump version

## [3.0.3] - 2023-03-12

### Bug Fixes

- Plates linting

### Miscellaneous Tasks

- Bump version

## [3.0.2] - 2023-03-12

### Features

- Add a plates captain hook linter

### Miscellaneous Tasks

- Update release title
- Remove commented out cruft
- Bump version
- Use existing release tag
- Bump version

## [3.0.1] - 2023-03-12

### Dev Worflow

- Update phive tools

### Documentation

- Set up dev environment

### Miscellaneous Tasks

- Basic automated release
- Bump version

### Testing

- Test setup

## [3.0.0] - 2023-03-11

### Miscellaneous Tasks

- Update deps
- Bump version

## [2.10.0] - 2023-02-14

### Features

- Add a make instance helper

### Miscellaneous Tasks

- Bump version

## [2.9.0] - 2023-01-16

### Miscellaneous Tasks

- Bump version

### Refactor

- Remove end to end routes

## [2.8.7] - 2022-12-30

### Miscellaneous Tasks

- Ensure absolute path in plugin or themeable
- Bump version

## [2.8.6] - 2022-12-22

### Bug Fixes

- Remove redundant slash

### Miscellaneous Tasks

- Bump version

## [2.8.5] - 2022-12-22

### Miscellaneous Tasks

- Null if constant not defined rather than error
- Bump version

## [2.8.4] - 2022-12-22

### Miscellaneous Tasks

- Function casing consistency
- Bump version

## [2.8.3] - 2022-12-22

### Features

- Handle db connection outside of WP

### Miscellaneous Tasks

- Bump version

## [2.8.2] - 2022-12-22

### Miscellaneous Tasks

- Revert dep bump for 8.0 compat
- Bump version

## [2.8.1] - 2022-12-22

### Miscellaneous Tasks

- Bump version

### Refactor

- Use underlying function instead of higher level

## [2.8.0] - 2022-12-22

### Features

- Option model

### Miscellaneous Tasks

- Bump version

## [2.7.4] - 2022-12-20

### Miscellaneous Tasks

- Function exists before declaring helpers
- Bump version

## [2.7.3] - 2022-12-15

### Bug Fixes

- Remove redundant dep injection

### Miscellaneous Tasks

- Bump version

## [2.7.2] - 2022-12-15

### Miscellaneous Tasks

- Use symfony string instead of jawira
- Bump version

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
