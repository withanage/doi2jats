.PHONY: help install test analyse clean

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $1, $2}' $(MAKEFILE_LIST)

install: ## Install dependencies
	composer install

test: ## Run tests
	composer test

analyse: ## Run static analysis
	composer analyse

clean: ## Clean vendor directory
	rm -rf vendor/

example: ## Run example with test DOI
	php doi2jats.php -v 10.30430/gjae.2023.0350

dev-setup: install ## Setup development environment
	@echo "Development environment ready!"
	@echo "Try: make example"
