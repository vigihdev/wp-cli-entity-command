# WP-CLI Entity Command

A WP-CLI extension package that provides command-line management of WordPress entities including posts, pages, custom post types, taxonomies, terms, users, comments, and more.

## Overview

This package enhances WP-CLI with additional commands to efficiently manage various WordPress entities directly from the command line. It's especially useful for developers, system administrators, and automation script writers who need to perform bulk operations or manage WordPress content without using the graphical interface.

## Features

- List posts, categories, menus and other WordPress entities
- Extensible architecture to support additional entity types
- Compatible with custom post types and taxonomies
- Follows WP-CLI standards and conventions

## Requirements

- PHP 8.1 or higher
- WordPress 5.0 or higher
- WP-CLI 2.0 or higher

## Installation

### Installing as a WP-CLI package

The recommended way to install this package is via WP-CLI's package manager:

## Main Entry Point

The package's main entry point is the `entity-command.php` file, which is responsible for registering all commands with WP-CLI. This file defines the namespace mappings for WP-CLI commands, where each namespace corresponds to a specific entity type.

The command registration mechanism uses an array configuration that maps WP-CLI command syntax to specific implementation classes. All command implementations are located in the `src/` directory, following the PSR-4 autoloading standard under the `Vigihdev\WpCliEntityCommand` namespace.

## Core Commands

Currently, the package supports the following core commands:
- `post:list` - List WordPress posts
- `category:list` - List categories
- `menu:list` - List menus

More commands will be added in future releases to provide comprehensive management of all WordPress entities.

## Architecture

The package follows a plugin-style architecture based on WP-CLI's command registration mechanism. It uses PSR-4 namespace autoloading (mapping the src/ directory) and object-oriented design organized under the `Vigihdev\WpCliEntityCommand` namespace.