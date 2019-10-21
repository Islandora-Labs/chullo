# ![Chullo](https://cloud.githubusercontent.com/assets/2371345/15409650/21fd66a6-1dec-11e6-9fb3-4a1554a0fb3d.png) Chullo

[![Latest Stable Version](https://img.shields.io/packagist/v/Islandora/chullo.svg?style=flat-square)](https://packagist.org/packages/islandora/chullo)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg?style=flat-square)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/islandora/chullo.svg?style=flat-square)](https://packagist.org/packages/islandora/chullo)
[![Build Status](https://travis-ci.org/Islandora/chullo.svg?branch=master)](https://travis-ci.org/Islandora/chullo)
[![Contribution Guidelines](http://img.shields.io/badge/CONTRIBUTING-Guidelines-blue.svg)](./CONTRIBUTING.md)
[![LICENSE](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](./LICENSE)
[![codecov](https://codecov.io/gh/Islandora/chullo/branch/master/graph/badge.svg)](https://codecov.io/gh/Islandora/chullo)

## Introduction
Chullo is a PHP client for [Fedora](http://fedorarepository.org/) built using [Guzzle](http://guzzlephp.org) and [EasyRdf](http://www.easyrdf.org/).

## Requirements

This module requires the following modules/libraries:

* PHP 5.6+
* [Composer](https://getcomposer.org/)

## Installation

1. `git clone git@github.com:Islandora/chullo.git`
2. `cd chullo`
3. `php composer.phar install`

You can also install with composer by pointing to your local clone. Just add these relevant bits to your `composer.json`:

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "/path/to/chullo"
        }
    ],
    "require": {
        "islandora/chullo": "dev-master"
    }
}
```

Then just `php composer.phar install` as usual.

## Usage

```php
use Islandora\Chullo\Chullo;

// Instantiated with static factory
$chullo = FedoraApi::create('http://localhost:8080/fcrepo/rest');

// Create a new resource
$uri = $chullo->createResource(); // http://localhost:8080/fcrepo/rest/0b/0b/6c/68/0b0b6c68-30d8-410c-8a0e-154d0fd4ca20

// Parse resource as an EasyRdf Graph
$graph = $chullo->getGraph($uri);

// Set the resource's title
$graph->set($uri, 'dc:title', 'My Sweet Title');

// Save the graph to Fedora
$chullo->saveGraph($uri, $graph);

```

## Documentation

Further documentation for this module is available on the [Islandora 8 documentation site](https://islandora.github.io/documentation/).

## Troubleshooting/Issues

Having problems or solved a problem? Check out the Islandora google groups for a solution.

* [Islandora Group](https://groups.google.com/forum/?hl=en&fromgroups#!forum/islandora) 
* [Islandora Dev Group](https://groups.google.com/forum/?hl=en&fromgroups#!forum/islandora-dev)

## Maintainers/Sponsors

Current maintainers:

* [Rosie Le Faive](https://github.com/rosiel)


## Development

If you would like to contribute, please get involved by attending our weekly [Tech Call](https://github.com/Islandora/documentation/wiki). We love to hear from you!

If you would like to contribute code to the project, you need to be covered by an Islandora Foundation [Contributor License Agreement](http://islandora.ca/sites/default/files/islandora_cla.pdf) or [Corporate Contributor License Agreement](http://islandora.ca/sites/default/files/islandora_ccla.pdf). Please see the [Contributors](http://islandora.ca/resources/contributors) pages on Islandora.ca for more information.
[islandora-playbook](https://github.com/Islandora-Devops/islandora-playbook) to get started. If you want to pull down the submodules for development, don't forget to run `git submodule update --init --recursive` after cloning.

## License

[MIT](/LICENSE) 
