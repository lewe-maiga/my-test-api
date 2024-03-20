.PHONY: install database-env dev-env

deploy:
	ssh o2switch 'cd ~/immogestion.online/my-test-api && eval `ssh-agent -s` && ssh-add ~/.ssh/github && git pull && make install'

install: vendor/autoload.php .env public/storage public/build/manifest.json
	php artisan cache:clear
	php artisan migrate --force

.env:
	cp .env.example .env
	php artisan key:generate --force
	php artisan jwt:secret

public/storage:
	php artisan storage:link

vendor/autoload.php: composer.lock
	composer install
	touch vendor/autoload.php

public/build/manifest.json: package.json
	npm install
	npm run build


database-env:
	docker compose up -d
	
