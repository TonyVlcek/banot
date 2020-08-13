# Banot

Is a software which addresses problems associated with searching for stolen items online by implementing a system which scrapes online bazaars and aggregates the postings into one dataset. The solution then allows the user to search through the dataset and set up notifications so that they receive a message when a new item matching their search criteria is added. The system consists of multiple services implemented as docker images. These services use REST API to communicate and this API is also exposed for potential external services to use. Scraping makes use of messaging queues which are being processed asynchronously by consumers built on top of the ReactPHP framework. The dataset of items is stored in Elasticsearch which provides advanced search capabilities and is easy to scale.


## Quick start

1. Pull the repository
2. `cd ./compose && make first-start`

For the `first-start` command to complete successfully you'll need these **dependencies**:

- docker & docker-compose
- [composer](https://getcomposer.org/doc/00-intro.md#globally)
- [yarn](https://yarnpkg.com/getting-started/install)

The `first-start` command will then build the docker images, run them, and run all the necessary setup such as migrations.
