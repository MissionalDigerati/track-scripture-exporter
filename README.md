# Track Scripture Exporter

This tool provides an easy way to export Bible verses for your Christian track development.  The uses the [Digital Bible Platform](https://digitalbibleplatform.com/) provided graciously by [Faith Comes By Hearing](https://www.faithcomesbyhearing.com/).

## Development

This repository is following the branching technique described in [this blog post](http://nvie.com/posts/a-successful-git-branching-model/), and the semantic version set out on the [Semantic Versioning Website](http://semver.org/).

Questions or problems? Please post them on the [issue tracker](https://github.com/MissionalDigerati/track-scripture-exporter/issues). You can contribute changes by forking the project and submitting a pull request.

## Installation

This project was built on PHP 7.0.  It uses [Composer](https://getcomposer.org/) to manage third party libraries.  Here are the steps to install.

1. Put a copy of the code on your webserver.  Setup your virtual host to point the document root to the webroot folder.  **Important** This secures your private data.
2. Install [Composer](https://getcomposer.org/) on your server based on their documentation.
3. Move into the directory above webroot, and install third party libraries: `composer install --no-dev`.
4. Copy **example.env** to **.env** and set your settings.
5. Copy **booklets.example.yaml** to **.booklets.yaml** and set your your booklet information based on the information below.
6. You should now be ready to go!

## Booklets.yaml

This file provides information about each booklet in your catalog.  You will need to add all your booklet information to this file before you can use this tool. See **booklets.example.yaml** for more information and details about its structure.


## License

This code is copyrighted by [Missional Digerati](http://missionaldigerati.org) and is under the [GNU General Public License v3](http://www.gnu.org/licenses/gpl-3.0-standalone.html).
