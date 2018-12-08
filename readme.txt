=== Social Sharing Plugin - Kiwi ===

Contributors: machothemes, silkalns
Tags: social media button, social share button, social floating bar, social share bar, facebook share, social sharing icons, twitter share, woocommerce sharing, share buttons, pinterest share, google plus share, social share counters
Requires at least: 3.8
Tested up to: 5.0
Stable tag: 2.0.14
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This is by far the best free WordPress share plugin. It is simple yet does exactly what it should with plenty of customisation options.
== Description ==

This is by far the best & easiest to use WordPress social media share plugin. A WordPress share plugin with custom icons built-in.


= Plugin Options =

* Social networks available: Facebook, Twitter, LinkedIN, Pinterest, Google Plus and Flint.
* Turn on/off each of them, individually.
* Great UI with intuitive & simple options.
* Built for the regular user.
* Change the style of the article bar group.
* Display the number of counts next to the social network icon.
* Enable/disable the floating bar.
* Change the shape of the floating bar’s social buttons.
* Add social identities.
* Add a "Click to tweet" button in WordPress editor.
* Google Analytics tracking.
* Four different Skins.
* The default, square style.
* The more futuristic approach, shift style.
* The friendly approach, pill (rounded corners) style.
* The eye-catching approach, leaf style (like a leaf in the wind).
* Display social icons: before content, after content or both.
* Amazing loading speed.

= WordPress Social Media Widget (for website) =

A WordPress Social Media Widget solution is coming soon, stay tuned.

**About us:**

We are a young team of WordPress aficionados who love building WordPress plugins & <a href="https://www.machothemes.com/" rel="friend" target="_blank" title="Premium WordPress themes">Premium WordPress themes</a> over on our theme shop. We’re also blogging and wish to help our users find the best <a rel="friend" href="https://www.machothemes.com/blog/cheap-wordpress-hosting/" target="_blank" title="Cheap WordPress Hosting">Cheap WordPress hosting</a> & the best <a href="https://www.machothemes.com/blog/best-free-wordpress-bootstrap-themes/" title="Bootstrap WordPress themes" target="_blank" rel="friend">Bootstrap WordPress Themes</a>.


== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the whole contents of the folder `kiwi-social-share` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy using it :)


== Screenshots ==

1. Screenshot of the back-end User Interface. All options are displayed above the fold (no scrolling, no fiddling around). Really easy to use.

== Frequently Asked Questions ==

Available filters (used for frontend rendering)
Article Bar Filters
    kiwi_article_bar_list_custom_class
    kiwi_before_article_bar
    kiwi_before_first_article_bar_item
    kiwi_after_last_article_bar_item
    kiwi_after_article_bar

Floating Bar Filters
    kiwi_floating_bar_list_custom_class
    kiwi_before_floating_bar
    kiwi_before_first_floating_bar_item
    kiwi_after_last_floating_bar_item
    kiwi_after_floating_bar

== Changelog ==

= 2.014 =
* Remove uninstall feedback

= 2.0.13 =
* Fixed "Trying to get property ‘post_content’ when there is no id, like 404"

= 2.0.11 =
* Improve performance
* Fixed security issue

= 2.0.10 =
* Update CMB2
* Improved Admin UI

= 2.0.9 =
* Minor Fixes

= 2.0.8 =
* Added Fintel network

= 2.0.7 =
* added uninstall feedback

= 2.0.6 =
* WP 4.9 compatibility
* Remove url encode on twitter button
* WhatsApp button didn't look alright on fit

= 2.0.4 =
* Removed box shadow in the admin window
* Changed how URLS are encoded for text
* WhatsApp icon visibility is now handled with CSS ( wp_is_mobile function would not work on websites with cache )

= 2.0.3 =
* Saving settings would cause errors on some servers

= 2.0.2 =
* Ajax request failed on license activation

= 2.0.1 =
* Minor bugs

= 2.0.0 =
* Major upgrade

= 1.0.4 =
* When checked, the Google+ icon wasn’t showing up. Fix provided via GitHub by @smartinsalmeida

= 1.0.3 =
* Increased the weight on the front-end selectors so that margin & padding are harder to get overwritten by themes. Feels like some themes don't want to play nice

= 1.0.2 =
* Fixed a small bug where content wasn't being returned if kiwi was turned off on posts and pages.
* Added share bar margins top/bottom.
* Fixed a small rendering bug in the back-end for images.
* Fixed a small bug where content wasn't being returned on !is_singular() pages (archives)

= 1.0.1 =
* Replaced Kiwi Logo with Dashicons icon
* Fixed a small bug with the notifications bar overlapping the form badge
* Enhanced the way we control sortables. When you disable a radio within a sortable, that sortable gets it’s opacity lowered.
* Properly formatted JS code with PHPStorm formatting.
* Small assets/back-end/images/ re-organization. Now, social icons have been renamed and moved into their own folder.
* Slightly re-worked the sortable/draggable field to support icons. No more CSS background images

= 1.0.0 =
* Initial release