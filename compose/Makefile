SHELL := /usr/bin/env bash

up:
	docker-compose --project-name=banot up -d

down:
	docker-compose --project-name=banot down -v

stop:
	docker-compose --project-name=banot stop

scale-detail:
	docker-compose --project-name=banot up --scale detail-scraper=10 -d

api:
	../api/toolkit.sh first-start

web:
	../web/toolkit.sh first-start

scraper:
	../scraper/toolkit.sh first-start

copy-envs:
	cp ../api/.env.template ../api/.env
	cp ../scraper/.env.template ../scraper/.env
	cp ../web/.env.template ../web/.env

first-start: copy-envs up api web scraper
