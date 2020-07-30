SHELL := /usr/bin/env bash

build:
	docker build -t banot/scraper:latest .

run:
	docker-compose up -d

scale:
	docker-compose up --scale list-scraper=2 --scale detail-scraper=3 -d

down:
	docker-compose down -v

test:
	docker run --rm -it -v ${PWD}:/opt/project -w /opt/project banot/scraper:latest \
		./vendor/phpunit/phpunit/phpunit --configuration ./phpunit.xml ./tests
