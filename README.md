# Raccoon WordPress starter

**[Raccoon Plugin](https://github.com/hiwelo/raccoon-plugin)** is a [_WordPress_](https://wordpress.org/) plugin which allows you to easily manage WordPress theme features with a JSON configuration file (manifest.json).

[![Build Status](https://travis-ci.org/hiwelo/raccoon-plugin.svg?branch=develop)](https://travis-ci.org/hiwelo/raccoon)


## Summary
  - [Dev requirements](#dev-requirements)
  - [Installation](#installation)


## Dev requirements
For its development, **[Raccoon Plugin](https://github.com/hiwelo/raccoon-plugin)** requires:
  - PHP >= 5.6
  - Composer
  - Node.js with npm for package management
  - WordPress >= 4.4


## Installation
To install the **[Raccoon Plugin](https://github.com/hiwelo/raccoon-plugin)** in your
WordPress project, you can :

1. Clone the repository in your _WordPress_ plugin directory :
`git clone https://github.com/hiwelo/raccoon-plugin.git`

2. If your using _Composer_ and an environment like _[Bedrock](https://roots.io/bedrock)_ : `composer require hiwelo/raccoon-plugin`


If you want to install this plugin directly in your WordPress without Composer, I suggest you to install it in the *Must-Use Plugins* `mu-plugins/` directory.

If you want to install this plugin with _Composer_, note that this package has a specific type (`wordpress-muplugin`) for a direct installation into the `mu-plugins/` directory (like in _[Bedrock](https://roots.io/bedrock)_).


## After installation

If the plugin is in the WordPress `mu-plugins/` directory, you do not need to do something special to activate it. 
You just need to create a `manifest.json` file in your theme's root directory to use it.

If the plugin is in the WordPress `plugins/` directory, do not forget to activate it in the WordPress admin panel and create the `manifest.json` file in your theme's root directory.


## Manifest JSON file

Once you created the `manifest.json` file in your theme's root directory, you can use in this file all features described in [this project's wiki](https://github.com/hiwelo/raccoon-plugin/wiki).


## Third-part features

### Raccoon loves Bedrock
_[Bedrock](https://roots.io/bedrock/)_ is a _WordPress_ boilerplate which create a better projet structure mainly with a new `wp-content/` folder renamed `app/`.
_[Bedrock](https://roots.io/bedrock/)_ use Composer and environments variables.

By example, **[Raccoon](https://github.com/hiwelo/raccoon/)** can use environments variables from Bedrocks to know the environment status (development, production, staging) and manage which debug informations has to be returned by the theme.
If you don't use _[Bedrock](https://roots.io/bedrock/)_, you can manually set environment status in the `manifest.json` file like this :
```json
{
  "environment-status": "development"
}
```
