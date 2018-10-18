# Laravel

## Fresh install

On local environment

	git clone git@github.com:Stolz/laravel.git -b develop
	cd laravel
	composer install
	cp .env.example .env
	php artisan key:generate
	$EDITOR .env
	php artisan migrate --seed

On development environment

	git clone https://github.com/Stolz/laravel.git -b develop
	cd laravel
	composer install --optimize-autoloader --no-suggest
	cp .env.example .env
	php artisan key:generate
	$EDITOR .env
	php artisan doctrine:generate:proxies
	php artisan migrate --seed

On production environment

	git clone https://github.com/Stolz/laravel.git -b master
	cd laravel
	composer install --no-dev --classmap-authoritative --no-suggest
	cp .env.example .env
	php artisan key:generate
	$EDITOR .env
	php artisan doctrine:ensure:production
	php artisan doctrine:generate:proxies
	php artisan config:cache
	php artisan route:cache
	php artisan migrate --seed

NOTE: If yo are generating classes at runtime use `--optimize-autoloader` instead of `--classmap-authoritative`.

## Update existing installation

On local environment

	git checkout develop
	git pull
	composer update
	php artisan migrate:refresh --seed

On development environment

	php artisan down
	git pull
	composer install --optimize-autoloader --no-suggest
	php artisan doctrine:generate:proxies
	php artisan migrate
	php artisan up
	php artisan queue:restart

On production environment

	php artisan down
	php artisan config:clear
	php artisan route:clear
	php artisan cache:clear
	php artisan view:clear
	php artisan doctrine:clear:metadata:cache
	php artisan doctrine:clear:query:cache
	php artisan doctrine:clear:result:cache
	git pull
	composer install --no-dev --classmap-authoritative --no-suggest
	php artisan doctrine:generate:proxies
	php artisan migrate
	php artisan route:cache
	php artisan config:cache
	php artisan up
	php artisan queue:restart

NOTE: If yo are generating classes at runtime use `--optimize-autoloader` instead of `--classmap-authoritative`.

## Static code analysis

To run a syntax check only (PHP linter)

	./bin/check_code -l

To check if there are potential problems within the code ([PHP Mess Detector](https://phpmd.org/) and [PHP Stan](https://github.com/phpstan/phpstan))

	./bin/check_code -m
	./bin/check_code -S

To check if there are violations of the code style ([PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer))

	./bin/check_code -s # Summary of all violations
	./bin/check_code -i # Detail of violations per file

To automatically fix some of the detected problems

	./bin/fix_code fileWithProblems.php

To make sure all files have proper permissions

	./bin/fix_permissions

To search code for pending tasks (TODO tags)

	./bin/search_code_for_pending_tasks

To get some stats about the code

	./bin/stats

To install a Git hook that checks the code before each commit

	ln -sf ../../bin/pre-commit-hook .git/hooks/pre-commit

## Testing

Run all tests on the default suites (default suites are defined with `defaultTestSuite` attribute within `phpunit.xml`)

	./vendor/bin/phpunit

Run all test on a file

	./vendor/bin/phpunit tests/TestClass.php

Run all test on a directory

	./vendor/bin/phpunit tests/Unit

Run a single test on a single file

	./vendor/bin/phpunit --filter testSomething tests/TestClass.php

Run all test on certails test suites only (use `phpunit --list-suites` to see availale suites)

	./vendor/bin/phpunit --testsuite Unit,Feature

To disable code coverage on run time for faster tests execution add the option `--no-coverage`.

## Compiling assets

Install tools to compile assets

	npm install

Compile assets for local environment

	npm run dev

To automatically recompile assets on local environment whenever a change is detected

	npm run watch

Compile assets for production environment (concatenated a minified)

	npm run production

## Documentation

To generate documentation for the source code run

	./bin/generate_app_docs

The generated documentation can be found in the `docs` directory.
