cleanup:
	php-formatter formatter:header:fix src
	php-formatter formatter:use:sort src
	php-formatter formatter:header:fix config
	php-formatter formatter:use:sort config
	php-formatter formatter:header:fix tests
	php-formatter formatter:use:sort tests
	php-cs-fixer fix --config-file=resources/phpcs.php --diff -vvv
