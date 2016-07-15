=== Components Local Development ===
Contributors: automattic
Tags: themes
Requires at least: 4.5.3
Tested up to: 4.6-beta2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Helps you develop Components locally.

== Description ==

Enables testing a local copy of Components on a local copy of http://components.underscores.me/.

== Installation ==

To install:

e.g.

1. Place a local copy of [Components]( https://github.com/Automattic/theme-components) in the root of your WordPress install.
2. Upload `theme-components-local-dev` directory to the `/wp-content/plugins/` directory.
3. Make sure you have the [Components site theme](https://github.com/Automattic/components.underscores.me) active.
4. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Why do I need a local copy of Components in the root of my WordPress install? =

Without a local copy specifically in that location, this plugin won't work.

= Why do I need to have the Components site theme active? =

This plugin integrates with that theme. If the theme isn't active, the plugin will just create a zip file from your local local copy of Components.

== Changelog ==

= 1.0 =
* Initial release of plugin.

== Upgrade Notice ==

= 1.0 =
Initial release of Components Local Development plugin.