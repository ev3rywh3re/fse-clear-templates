
# Full Site Editor (FSE) - Trash Template Changes

**Please Note!** This is an experimental WordPress plugin for Full Site Editing features. There really is no support. You are welcome to the idea.

This plugin allows users to clear the Full Site Editor (FSE) template modifications, forcing WordPress to regenerate them. This is particularly useful for troubleshooting theme compatibility issues with FSE.

## Description

When working with the Full Site Editor, sometimes template changes can cause conflicts or unexpected behavior, especially when switching between themes or during theme development. This plugin provides a simple way to reset the FSE templates to their default state, allowing you to start fresh and resolve any potential issues.

**This should force WordPress to reload the files from your theme directory "templace" and "parts" folders. The .html files should be reloaded into WordPress for future Full SIte Editing modifications.** 

## Features

* Clears all FSE templates and template parts.
* Provides a simple button in the WordPress admin bar for easy access.
* Includes a settings page under the Tools menu for clearing templates.
* Designed for administrators and users with `manage_options` capability.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

Once activated, you'll find a new link in the WordPress admin bar labeled "Clear FSE Templates". Clicking this link will clear all FSE templates and template parts, forcing WordPress to regenerate them.

Alternatively, you can navigate to the plugin's settings page under Tools > Clear FSE Templates and click the "Clear FSE Templates" button.

## Support

I can not offer support, but you are welcome to contact me.

## License

This plugin is released under the GPLv3 or later license.
