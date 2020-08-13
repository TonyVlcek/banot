#!/bin/bash

COMMAND="$1"
ARGS="${@:2}"
TOOLKIT="$0"
PROJECT_ROOT=`dirname "$0"`

__usage="
Usage: toolkit [COMMAND] [COMMAND OPTIONS]

Command:
  composer                     Runs composer (prestissimo) in a docker container
  cli                          Runs console of the api application
  build                        Builds the docker image
  es-setup                     Creates the items index in Elasticsearch and set thresholds
  sql-migrations               Resets the schema of the MySQL database
  first-start                  Performs actions necessary for first start

Command Options:
Are specific to each command.
"

cd ${PROJECT_ROOT}

case ${COMMAND} in
	composer )
		docker run --rm -it -v $PWD:/app -u $(id -u):$(id -g) \
			clevyr/prestissimo \
			${ARGS} \
		;;

	cli )
			docker exec -it api /app/bin/console ${ARGS}
		;;

	build )
			docker build -t banot/api:latest .
		;;

	es-setup )
			echo "Put items index mapping."
			curl -X PUT -H "Content-Type: application/json" \
				-d @./es-index.json \
				http://localhost:9200/items

			echo -e "\n\n Set disk threshold.\n"
			curl -X PUT -H "Content-Type: application/json" \
				-d '{ "transient": { "cluster.routing.allocation.disk.threshold_enabled": false } }' \
				http://localhost:9200/_cluster/settings

			echo -e "\n\n Read only allow delete.\n"
			curl -X PUT -H "Content-Type: application/json" \
				-d '{"index.blocks.read_only_allow_delete": null}' \
				http://localhost:9200/_all/_settings
		;;

	sql-migrations )
			${TOOLKIT} cli migrations:reset
		;;

	first-start )
			echo -e "\n\n----------------------------------------\nRunning first start for API\n----------------------------------------\n"
			composer install --ignore-platform-reqs
			echo "Waiting for MySQL and Elasticsearch... (15s)"; sleep 15
			${TOOLKIT} es-setup
			${TOOLKIT} sql-migrations
			docker exec api chmod -R 777 /app/temp/ /app/log/   # fix permissions after console run
		;;

	* )
		echo "$__usage"
esac


