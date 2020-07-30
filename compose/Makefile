SHELL := /usr/bin/env bash

up:
	docker-compose --project-name=banot up -d

down:
	docker-compose down -v

scale-detail:
	docker-compose --project-name=banot up --scale detail-scraper=10 -d
