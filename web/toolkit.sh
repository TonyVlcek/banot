#!/bin/bash

COMMAND="$1"
ARGS="${@:2}"
TOOLKIT="$0"
PROJECT_ROOT=`dirname "$0"`

__usage="
Usage: toolkit [COMMAND] [COMMAND OPTIONS]

Command:
  reload-sdk                   Updates the local version of the banot-php SDK
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

	first-start )
			echo -e "\n\n----------------------------------------\nRunning first start for WEB\n----------------------------------------\n"
			composer install --ignore-platform-reqs
			yarn install
			yarn encore dev
		;;

	* )
		echo "$__usage"
esac
