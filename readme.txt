=== WP-PageNavi ===
Contributors: Viewplex
Donate link: 
Tags: broadcast, text broadcast, live broadcasting, online broadcasting
Requires at least: 3.8  
Tested up to: 3.9.1  
Stable tag: 0.9  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

Simple WordPress plugin for live text broadcasting directly from your site.

== Description ==

Add and manage live  text broadcasts of any events directly from your website with the plugin vpbroadtsastlite. Main features:

- Own page for each broadcast
- Insert broadcast into any post, page or text widget with [single_broadcast] shortcode
- Insert anywhere list with all broadcast avaliable on your site with [broadcast_list] shortcode

= Development =
* [https://github.com/viewplexnet/vpBroadcastLite](https://github.com/viewplexnet/vpBroadcastLite "https://github.com/viewplexnet/vpBroadcastLite")

== Installation ==

You can either install it automatically from the WordPress admin, or do it manually:

1. Unzip the archive and put the `vp-broadcast-lite` folder into your plugins folder (/wp-content/plugins/).
1. Activate the plugin from the Plugins menu.

= Usage =

Go to **WP-Admin -> Broadcasts -> All broadcasts** and start your first broadcast with **Add new** button.

Configure options, add some greeting text, setup broadcast status - **Coming soon** if your broadcast will starts in nearly future or **Live**

Use **[broadcast_list]** or **[single_broadcast]** shortcodes anywhere to show your visitors list of avaliable broadcasts or some single broadcast

== Screenshots ==

== Frequently Asked Questions ==

= Page with single Broadcast not displaying correctly =

You need to set up **Add HTML markup for opening page wrappers** and **Add HTML markup for closing page wrappers** options at **WP-Admin -> Settings -> vpBroadcast Lite**. 

To loacte this wrappers - you need to open in editor template page.php or index.php from your theme. Next you need to find the loop. The loop usually starts with a:

`<?php if ( have_posts() ) :`

and usually ends with:

`<?php endif; ?>`

But this varies between themes. Opening and closing wrappers - it's HTML markup before and after these code parts.

If you can't correctly setup opening and closing page wrappers - you may use shortcode **[single_broadcast]** to display your broadcast at any post or page.

== Changelog ==

= 0.9 =
* Initial release
