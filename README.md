# Raccoon WordPress starter
README.md subject to update.

**[Raccoon](https://github.com/hiwelo/raccoon/)** is a [_WordPress_](https://wordpress.org/) starter theme based on Composer & NPM & Babel (ES 2015 preset) & Gulp & Knacss.

[![Build Status](https://travis-ci.org/hiwelo/raccoon.svg?branch=develop)](https://travis-ci.org/hiwelo/raccoon)


## Summary
  - [Requirements](#requirements)
  - [Installation](#installation)
  - [How to work with a raccoon](#how-to-work-with-a-raccoon)
    - [Before to work](#before-to-work)
    - [Vendor update](#vendor-update)
    - [Before to commit](#before-to-commit)
    - [Documentation](#documentation)
  - [How to code with a raccoon](#how-to-code-with-a-raccoon)
    - [OOP PHP Class](#oop-php-class)
    - [Raccoon loves PSR-2 Coding Standards and documentation](#raccoon-loves-psr-2-coding-standards-and-documentation)
  - [How a raccoon can help you with _WordPress_](#how-a-raccoon-can-help-you-with-wordpress)
    - [Configuration manifest](#configuration-manifest)
    - [_WordPress_ theme namespace](#wordpress-theme-namespace)
    - [Theme supports](#theme-support)
    - [Theme features](#theme-features)
    - [Navigations](#navigations)
    - [Post Types](#post-types)
      - [Create a custom post type](#create-a-custom-post-type)
      - [Post type unregistration](#post-type-unregistration)
    - [Post Status](#post-status)
    - [Sidebars](#sidebars)
    - [Widgets](#widgets)
  - [Third part features](#third-part-features)
    - [Raccoon loves Bedrock](#raccoon-loves-bedrock)


## Requirements
For its development, **[Raccoon](https://github.com/hiwelo/raccoon/)** requires:
  - PHP >= 5.6
  - Composer
  - Node.js with npm for package management
  - WordPress >= 4.4


## Installation
It's pretty simple: you just have to clone the repository and run `composer install`
to start all required jobs.
```
git clone https://github.com/hiwelo/raccoon.git
composer install
```


## How to work with a raccoon

### Before to work
Before any modification, please run:
```
composer work
```
With this command you verify that your repository is up to date and it starts all
watch jobs.

### Vendor update
Regularly, don't forget to update all dependencies (composer & npm) with:
```
composer update
```

### Before to commit
After any modification and before you commit anything, I strongly advice to run:
```
composer test
```
And if there's no errors, you can commit your modifications.

If there's some errors and you absolutely want to commit, you've got to run this command
to avoid pre-commit verifications:
```
git commit --no-verify
```

### Documentation
If you want to parse all PHP files to generate the documentation, please run:
```
composer documentation
```
The generated documentation is available in the `./docs/api/` folder.


## How to code with a raccoon

### OOP PHP Class
**[Raccoon](https://github.com/hiwelo/raccoon/)** is an OOP-based _WordPress_ template.
All **[Raccoon](https://github.com/hiwelo/raccoon/)**'s classes are placed within the namespace `Hwlo\Raccoon\` and you can find them in the `./lib` directory.

Any custom class that you can create should be placed in a specific namespace.
For example, you can use a namespace like `Hwlo\Raccoon\Custom\`.

When you create a new namespace, you have to add it in the `composer.json` file, in the `autoload` section.
I strongly advice to use a PSR-4 namespace.

For example, if you want to register a custom namespace like `Hwlo\Raccoon\Custom\`, you have to write custom classes in `./custom-lib/` and update `composer.json` like that:
```json
{
  "autoload": {
    "psr-4": {
      "Hwlo\\Raccoon\\": "./lib/",
      "Hwlo\\Raccoon\\Custom\\": "./custom-lib/"
    }
  }
}
```

For each new created class, you may need to regenerate the `./vendor/autoload.php` file. For this operation, please run:
```
composer autoload
```

### **[Raccoon](https://github.com/hiwelo/raccoon/)** loves PSR-2 Coding Standards and documentation
When a raccoon work, he likes to make sure his work can be understood by all. So he writes scripts which are conform to standards used by the PHP community.

**[Raccoon](https://github.com/hiwelo/raccoon/)** uses [PSR-2 Coding Standards](http://www.php-fig.org/psr/psr-2/) for its tests.
Each modification must make a successful _PSR-2_ syntax check to be committed.
So, Raccoon have got just a little modification : scripts documentation is required by the `ruleset.xml` for _PHP CS_ validation.

You can run at any time a test control on all PHP & JavaScript files with:
```
composer test
```

Some errors on PHP files can be automatically fixed by `phpcbf`. To do so, run:
```
composer phpcbf
```

So don't forget: to make **[Raccoon](https://github.com/hiwelo/raccoon/)** happy, document your _f**king_ code! :D


## How a raccoon can help you with _WordPress_

### Configuration manifest
To avoid multiple initialization functions, **[Raccoon](https://github.com/hiwelo/raccoon/)** uses a _JSON_ configuration file: `manifest.json`.
In this file you can set all features proposed by _WordPress_ to its themes.

### _WordPress_ theme namespace
With **[Raccoon](https://github.com/hiwelo/raccoon/)**, you can define a specific namespace for this theme.
This namespace will be mainly used by string translation methods like `__()` or `_e()` or `_x()` or `_n()`.

To define a specific namespace, you have to update `manifest.json` like that:
```json
{
  "namespace": "raccoon"
}
```
If empty or undefined, the default namespace will be `raccoon`.

### Theme support
With **[Raccoon](https://github.com/hiwelo/raccoon/)**, you can easily set up all theme features with the `manifest.json` file.

All features described in the [_WordPress_ documentation](https://codex.wordpress.org/Function_Reference/add_theme_support) can be registered with or without arguments.
Considering the JSON format, a feature requires a least a boolean.

Here is the kind of statements that you can set up in the `manifest.json` file:
```json
{
  "theme-support": {
    "title-tag": true,
    "post-thumbnail": true,
    "automatic-feed-links": true,
    "post-formats": [
      "link",
      "quote",
      "audio",
      "video"
    ],
    "html5": [
      "caption",
      "comment-form",
      "comment-list",
      "gallery",
      "search-form"
    ]
  }
}
```

### Theme features
**[Raccoon](https://github.com/hiwelo/raccoon)** has a feature which allows you to disable some Wordpress features like widgets or comments.
In the `manifest.json` file you can easily (de-)activate these features with:
```json
{
  "theme-features": {
    "comments": false,
    "widget": false
  }
}
```

### Navigations
With **[Raccoon](https://github.com/hiwelo/raccoon/)**, you can easily set up navigations with the `manifest.json` file.

Each navigation must have a location and a readable description like in the [_WordPress_ documentation](https://codex.wordpress.org/Function_Reference/register_nav_menu).

For example if you want to register two navigations (a primary navigation and a list of social networks), you have to update `manifest.json` like that:
```json
{
  "navigations": {
    "primary": "Main navigation",
    "social": "Social links"
  }
}
```

### Post Types

#### Create a custom post type
With **[Raccoon](https://github.com/hiwelo/raccoon/)**, you can easily set up custom post types with the `manifest.json` file.

Each custom post type must have a title and an array of arguments. All arguments described in the [_WordPress_ documentation](https://codex.wordpress.org/Function_Reference/register_post_type) can be used in the `manifest.json` file.

For example if you want to register the same post type as the [_WordPress_ documentation](https://codex.wordpress.org/Function_Reference/register_post_type#_edit_link), you have to update `manifest.json` like that:
```json
{
  "post-types": {
    "books": {
      "labels": {
        "name": "Books",
        "singular_name": "Book",
        "menu_name": "Books",
        "name_admin_bar": "Book",
        "add_new": "Add New",
        "add_new_item": "Add New Book",
        "new_item": "New Book",
        "edit_item": "Edit Book",
        "view_item": "View Book",
        "all_items": "All Books",
        "search_items": "Search Books",
        "parent_item_colon": "Parent Books:",
        "not_found": "No books found.",
        "not_found_in_trash": "No books found in Trash."
      },
      "description": "Description.",
      "public": "true",
      "publicly_queryable": "true",
      "query_var": "true",
      "rewrite": {
        "slug": "book"
      },
      "menu_icon": "dashicons-editor-paragraph",
      "supports": [
        "title",
        "editor",
        "author",
        "thumbnail",
        "excerpt",
        "custom-fields",
        "revisions",
        "post-formats"
      ]
    }
  }
}
```
Good to know, you can't declare in `manifest.json` a custom post type named _remove_.

#### Post type unregistration
With **[Raccoon](https://github.com/hiwelo/raccoon/)**, you can alse easily unregister an existing post type with the `manifest.json` file.

Each existing post type, event _post_ or _pages_ can be unregister with this `manifest.json` functionality.
You just have to add in your `manifest.json`:
```json
{
  "post-types": {
    "remove": ["post"]
  }
}
```

For example if you want to register the same post type as the [_WordPress_ documentation](https://codex.wordpress.org/Function_Reference/register_post_type#_edit_link), you have to update `manifest.json` like that:

### Post Status
With **[Raccoon](https://github.com/hiwelo/raccoon/)**, you can easily set up custom post status with the `manifest.json` file.
Each custom post status will be automatically added into each admin panel page inside post status selectboxes.

Each custom post status can have all arguments described for the [WordPress `register_post_status()` method](https://codex.wordpress.org/Function_Reference/register_post_status).

For example, if you want to register a new custom post status, you have to update `manifest.json` like that:
```json
{
  "post-status": {
    "archive": {
      "label": "Archive",
      "exclude_from_search": false,
      "public": false,
      "internal": false,
      "protected": true,
      "private": false,
      "publicly_queryable": false,
      "show_in_admin_all_list": true,
      "show_in_admin_status_list": true,
      "_builtin": true,
      "label_count": [
        "Archive <span class=\"count\">(%s)</span>",
        "Archives <span class=\"count\">(%s)</span>"
      ]
    }
  }
}
```
Good to know, you can't declare in `manifest.json` a custom post status named _remove_.

### Sidebars
With **[Raccoon](https://github.com/hiwelo/raccoon/)**, you can easily set up sidebars with the `manifest.json` file.

Each sidebar must have an array of arguments. All arguments described in the [_WordPress_ documentation](https://codex.wordpress.org/Function_Reference/register_sidebar) can be used in the `manifest.json` file.

For example if you want to register the same sidebar as the [_WordPress_ documentation](https://codex.wordpress.org/Function_Reference/register_sidebar), you have to update `manifest.json` like that:
```json
{
  "sidebars": [
    {
      "name": "Sidebar name",
      "id": "unique-sidebar-id",
      "description": "Description.",
      "class": "sidebarClassName",
      "before_widget": "<li id=\"%1$s\" class=\"widget %2$s\">",
      "after_widget": "</li>",
      "before_title": "<h2 class=\"widget__title\">",
      "after_title": "</h2>"
    }
  ]
}
```

### Widgets
With **[Raccoon](https://github.com/hiwelo/raccoon/)**, you can easily set up widgets with the `manifest.json` file.

Each widget must consist of a specific OOP PHP Class like described in the [_WordPress_ documentation](https://codex.wordpress.org/Widgets_API).
So, for a [widget registration](https://codex.wordpress.org/Function_Reference/register_sidebar) you just have to add the widget classname in the `manifest.json`, with its complete namespace like that:
```json
{
  "widgets": [
    "Hwlo\\Raccoon\\WidgetExample"
  ]
}
```


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

