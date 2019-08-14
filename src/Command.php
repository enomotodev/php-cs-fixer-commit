<?php

namespace Enomotodev\PhpCsFixerCommit;

class Command
{
    /**
     * @return void
     */
    public static function main()
    {
        $command = new static;

        $command->run($_SERVER['argv']);
    }

    /**
     * @param  array $argv
     * @return void
     */
    public function run(array $argv)
    {
        $argvCount = count($argv);
        if ($argvCount !== 3 && $argvCount !== 4) {
            fwrite(STDERR, 'Invalid arguments.' . PHP_EOL);
            exit(1);
        }

        list(, $name, $email) = $argv;
        $path = isset($argv[3]) ? $argv[3] : '';

        system("./vendor/bin/php-cs-fixer fix {$path}");
        system('php clean -df');

        if (strpos(system('git status -sb'), '.php') === false) {
            fwrite(STDOUT, 'No changes.' . PHP_EOL);
            exit(0);
        }

        $this->setupGitConfig($name, $email);
        $this->createCommit();
    }

    /**
     * @param  string $name
     * @param  string $email
     * @return void
     */
    private function setupGitConfig($name, $email)
    {
        system("git config user.name {$name}");
        system("git config user.email {$email}");
    }

    /**
     * @return void
     */
    private function createCommit()
    {
        if ((bool) getenv('CIRCLECI')) {
            $branch = getenv('CIRCLE_BRANCH');
            $accessToken = getenv('GITHUB_ACCESS_TOKEN');
            $repositoryName = getenv('CIRCLE_PROJECT_REPONAME');
            $repositoryUserName = getenv('CIRCLE_PROJECT_USERNAME');

            system("git remote set-url origin https://{$accessToken}@github.com/{$repositoryUserName}/{$repositoryName}/");
            system('git add -u');
            system('git commit -m "php-cs-fixer"');
            system("git push -q origin {$branch}");
        } elseif ((bool) getenv('GITLAB_CI')) {
            $branch = getenv('CI_COMMIT_REF_NAME');
            $token = getenv('GITLAB_API_PRIVATE_TOKEN');
            $repositoryUrl = getenv('CI_REPOSITORY_URL');
            preg_match('/https:\/\/gitlab-ci-token:(.*)@(.*)/', $repositoryUrl, $matches);

            system("git remote set-url origin https://gitlab-ci-token:{$token}@{$matches[2]}");
            system("git checkout {$branch}");
            system('git add -u');
            system('git commit -m "php-cs-fixer"');
            system("git push -q origin {$branch}");
        }
    }
}
