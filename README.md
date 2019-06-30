# php-cs-fixer-commit

## Installation

```
$ composer require --dev enomotodev/php-cs-fixer-commit
```

## Usage (GitHub + CircleCI)

### Setting GitHub personal access token to CircleCI

GitHub personal access token is required for sending pull requests to your repository.

1. Go to [your account's settings page](https://github.com/settings/tokens) and generate a personal access token with "repo" scope
1. On CircleCI dashboard, go to your application's "Project Settings" -> "Environment Variables"
1. Add an environment variable `GITHUB_ACCESS_TOKEN` with your GitHub personal access token

### Configure circle.yml

Configure your `circle.yml` or `.circleci/config.yml` to run `php-cs-fixer-commit`, for example:

```yaml
version: 2

jobs:
  build:
    # ...
    fixer:
      steps:
        # ...
        - run:
            name: php-cs-fixer-commit
            command: ./vendor/bin/php-cs-fixer-commit <username> <email>
```

NOTE: Please make sure you replace `<username>` and `<email>` with yours.

## Usage (GitLab + GitLabCI)

### Setting GitLab personal access token to GitLabCI

GitLab personal access token is required for sending merge requests to your repository.

1. Go to [your account's settings page](https://gitlab.com/profile/personal_access_tokens) and generate a personal access token with "api" scope
1. On GitLab dashboard, go to your application's "Settings" -> "CI /CD" -> "Environment variables"
1. Add an environment variable `GITLAB_API_PRIVATE_TOKEN` with your GitLab personal access token

### Configure .gitlab-ci.yml

Configure your `.gitlab-ci.yml` to run `php-cs-fixer-commit`, for example:

```yaml
stages:
  # ...
  - fixer

# ...

fixer-commit:
  image: composer:latest
  stage: fixer
  script:
    - "composer install"
    - "$COMPOSER_HOME/vendor/bin/php-cs-fixer-commit <username> <email>"
```

NOTE: Please make sure you replace `<username>` and `<email>` with yours.

## License

php-cs-fixer-commit is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
