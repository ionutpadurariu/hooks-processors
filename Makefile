MIN_MSI=100
MIN_COVERED_MSI=100

.PHONY: valid test coding-standard-fix coding-standard static-analysis unit-test mutation-test

valid: coding-standard-fix coding-standard static-analysis test

coding-standard: vendor
	vendor/bin/phpcs --ignore=vendor/*,bin/*

coding-standard-fix: vendor
	vendor/bin/phpcbf --ignore=vendor/*,bin/*

static-analysis: vendor
	vendor/bin/phpstan analyse --memory-limit=-1 $(EXTRA_FLAGS)

unit-test: vendor
	vendor/bin/phpunit --testsuite unit --stop-on-error --stop-on-failure $(EXTRA_FLAGS)

mutation-test: vendor
	vendor/bin/infection --no-progress --test-framework-options="--testsuite=unit" -s --min-msi=$(MIN_MSI) --min-covered-msi=$(MIN_COVERED_MSI) $(EXTRA_FLAGS) $(INFECTION_FILTER)
