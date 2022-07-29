COMPOSER_ARGS=--no-interaction
DB_URL_ARGS=--db-url=mysql://$(FRICKS_DATABASE_USER):$(FRICKS_DATABASE_PASSWORD)@$(FRICKS_DATABASE_HOST):$(FRICKS_DATABASE_PORT)/$(FRICKS_DATABASE_NAME)
MYSQL_CLI_ARGS=-u $(FRICKS_DATABASE_USER) -p$(FRICKS_DATABASE_PASSWORD) -h $(FRICKS_DATABASE_HOST)
CREDENTIALS_ARGS=--account-name=$(FRICKS_ADMIN_ACCOUNT_NAME) --account-mail=$(FRICKS_ADMIN_ACCOUNT_MAIL) --account-pass=$(FRICKS_ADMIN_ACCOUNT_PASSWORD)
RED:=\033[0;31m
GREEN:=\033[0;32m
NC=\033[0m

define dc
	docker-compose $(1) $(2) $(3)
endef

TARGETS:=$(MAKEFILE_LIST)

-include .env
.env:
	$(MAKE) .env

.PHONY: help
help:
	@grep -Eh '^[0-9a-zA-Z_#-]+:.*?## .*$$' $(TARGETS) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: stop
stop: ## Stop and down containers
	$(call dc,stop)
	$(call dc,down --remove-orphans)

.PHONY: stop-a
stop-a:
	$(MAKE) stop

.PHONY: image-cleanup
image-cleanup: 
	docker image prune --force

.PHONY: rebuild
rebuild: ## Rebuild containers
	$(call dc,stop)
	$(call dc,down --remove-orphans)
	$(call dc,up --build -d)

.PHONY: restart
restart: ## Restart containers
	$(call dc,stop)
	$(call dc,down --remove-orphans)
	$(call dc,up -d)

.PHONY: restart-a
restart-a:
	$(MAKE) restart

.PHONY: restart-b

.PHONY: bash
bash: ## Start bash session
	$(call dc,exec,drupal,bash)

.PHONY: install
install: stop drupal-clear-db-data rebuild mysql-wait stop-a restart drupal-vendor drupal-site-install drupal-set-file-permission

.PHONY: drupal-clear-db-data
drupal-clear-db-data:
	docker volume rm fricks_drupal-db-data || true

.PHONY: drupal-set-file-permission
drupal-set-file-permission: 
	chmod -R 777 drupal/web/sites/default

.PHONY: drupal-site-install
drupal-site-install: ## Install drupal instance, set project UUID and import configurations
	$(call dc,exec,drupal,drush sql:drop $(DB_URL_ARGS) -y) || true
	$(call dc,exec,drupal,drush site:install minimal $(CREDENTIALS_ARGS) $(DB_URL_ARGS) -y)
	$(call dc,exec,drupal,drush config-set system.site uuid $(FRICKS_CONFIG_UUID) -y)
	$(call dc,exec,drupal,drush updb -y)
	$(call dc,exec,drupal,drush cim -y)

.PHONY: cim
cim: ## Alias for drupal-config-import
	$(call dc,exec,drupal,drush cim -y)

.PHONY: drupal-config-import
drupal-config-import: ## Import drupal configurations, from files to database 
	$(call dc,exec,drupal,drush cim -y)

.PHONY: drupal-config-status
drupal-config-status: ## Dry run analyse for drupal configuration
	$(call dc,exec,drupal,drush config:status -y)

.PHONY: cr
cr: drupal-cc ## alias for drupal-cc

.PHONY: drupal-cc
drupal-cc: ## CMS cache clear
	$(call dc,exec,drupal,drush cr)

.PHONY: updb
updb: ## CMS database schema update
	$(call dc,exec,drupal,drush updb -y)

.PHONY: drupal-refresh
drupal-refresh: drupal-vendor cr updb cim ## Refresh CMS

.PHONY: cex
cex: drupal-config-export ## Alias for drupal-config-export

.PHONY: drupal-config-export
drupal-config-export: ## Export CMS configuration from database to files
	$(call dc,exec,drupal,drush cex -y)

.PHONY: drupal-vendor
drupal-vendor: ## Run CMS composer install
	$(call dc,exec,drupal,composer install $(COMPOSER_ARGS))

.PHONY: drupal-composer-require
drupal-composer-require: ## Composer require for CMS
	$(call dc,exec,drupal,composer require drupal/$${m})

.PHONY: en
en: ## Enable existing drupal module. ex: make en m=rest
	$(call dc,exec,drupal,drush pm:enable $${m} -y)

.PHONY: drupal-require
drupal-require: drupal-composer-require ## Composer require for CMS

.PHONY: pmu
pmu: ## Uninstall drupal module
	$(call dc,exec,drupal,drush pm:uninstall $${m} -y)

.PHONY: add
add: drupal-require en ## First require drupal module vendor, then enable it

.PHONY: mysql
mysql: ## connect to mysql-cli
	$(call dc,exec,database,mysql $(MYSQL_CLI_ARGS))

.PHONY: mysql-wait
mysql-wait: ## wait for mysql to be ready
	$(call dc,exec,drupal, bash -c "while ! mysql $(MYSQL_CLI_ARGS) -e 'use $(FRICKS_DATABASE_NAME)' &>/dev/null; do echo 'Waiting for mysql to be ready...'; sleep 3;done")

.PHONY: mysql-save
mysql-save: ## save msyql database 
	$(call dc,exec,drupal, bash -c "drush sql:dump --extra-dump=--no-tablespaces > backup.sql")

.PHONY: mysql-restore
mysql-restore: ## restore backuped database
	$(call dc,exec,drupal, bash -c "drush sql:cli < backup.sql")

.PHONY: spip-import
spip-import: ## import spip content into drupal
	$(call dc,exec,drupal, bash -c "drush fricks:spip:import")

.PHONY: reload 
reload: mysql-restore cr cim spip-import ## restore backuped databe, update config and start import

.PHONY: npm
npm:
	$(call dc,exec,drupal, bash -c "cd ../front && npm $${c}")

.PHONY: front-vendor
front-vendor:
	$(call dc, exec,drupal, bash -c "cd ../front && npm ci")

.PHONY: front-watch
front-watch:
	$(call dc,exec,drupal, bash -c "cd ../front && npm run watch")

.PHONY: front-build
front-build:
	$(call dc,exec,drupal, bash -c "cd ../front && npm run build")