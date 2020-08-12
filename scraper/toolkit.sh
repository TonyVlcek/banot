#!/bin/bash

COMMAND="$1"
ARGS="${@:2}"
TOOLKIT="$0"
PROJECT_ROOT=`dirname "$0"`

__usage="
Usage: toolkit [COMMAND] [COMMAND OPTIONS]

Command:
  reload-sdk                   Updates the local version of the banot-php SDK
  build                        Build the scraper docker image
  test                         Runs tests
  first-start                  Performs actions necessary for first start

Command Options:
Are specific to each command.
"

cd ${PROJECT_ROOT}

case ${COMMAND} in
	reload-sdk )
			rm -rf ./vendor/banot/
			composer update banot/banot-php --ignore-platform-reqs
		;;

	build )
			docker build -t banot/scraper:latest .
		;;

	test )
			docker run --rm -it -v ${PWD}:/opt/project -w /opt/project banot/scraper:latest \
				./vendor/phpunit/phpunit/phpunit --configuration ./phpunit.xml ./tests
		;;

	first-start )
			echo -e "\n\n----------------------------------------\nRunning first start for SCRAPER\n----------------------------------------\n"
			composer install --ignore-platform-reqs
		;;

	* )
		echo "$__usage"
esac
