=== Picasa for Wordpress ===
Contributors: Pierre Sudarovich
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9931621
Tags: Picasa, Images, Gallery, Slideshow
Requires at least: 2.0
Tested up to: 3.0
Stable tag: trunk

A plugin that will let you add slideshow widget(s) of Picasa albums from the given RSS URL.

== Description ==

Give the RSS url of your Picasa album(s) to the Picasa for Wordpress widget to display them in your sidebar(s).

Features:
--------
* add slideshow widget(s) in the sidebar(s) section of your page,
* you'll can change the width of your slideshow (the height will be automatically adjusted), the background color, the autoplay feature, if only registered users can see it, if you want to show the legend,
* internationalization of the plugin,
* XHTML conform,
* tested and working on IE6, IE7, IE8, Firefox, Opera, Chrome, Safari,


== Installation ==

1. Upload the folder `picasa-for-wordpress` to your `/wp-content/plugins/` directory,
2. Activate the plugin through the "Plugins" menu in WordPress,
3. Go to the widget interface to add your slideshow(s) to your sidebar(s)
 

== Frequently Asked Questions ==

In some explanations, i ask to edit a php file. Be careful to edit them with a good editor like [Notepad++](http://notepad-plus.sourceforge.net/ "") and open each file with the format "UTF-8 without BOM".

= I've entered the url from `Link to this album`, but nothing appears. What's wrong ? =
Be careful, the url than you have to fill is from the **RSS link**.

= I'd like to get the `Picasa for Wordpress` interface in my native language. How can i do that? =
Edit the file `picasa-en_US.po` by using a PO file editor such as :
KBabel (Linux) should be available as a package for your Linux distribution, so install the package.
poEdit (Linux/Windows) available from http://www.poedit.net/.
For french in this example, save it as `picasa-fr_FR.po` a `picasa-fr_FR.mo` will be automatically generated.
Put the `picasa-xx_XX.mo` file in the `lang` folder (under `picasa-for-wordpress`), the PO file is just here to generate the MO translation file...

= Ok, i've done what you explain above, but the `Picasa for Wordpress` interface is still in english How to make it works? =
Open your `wp-config.php` file (at the root of your blog) and search for : `define ('WPLANG', 'xx_XX');` where xx_XX is your language. If this line doesn't exist add it in your file. Save your modifications and re-upload the wp-config on your server.

= Does `Picasa for Wordpress` works with WP-MU? =
Yes


== Screenshots ==
1. Example of Picasa for Wordpress in the sidebar of my blog.



== Changelog ==

= 1.6.1 =
* nothing really important, just the update of my blog url ;)

= 1.6 =
* favor to the use of `define('WP_DEBUG', true);` some menage have been done in the code :)

= 1.5.1 =
* Just a very little modification to set the default values to 100% during the creation of a slideshow ;).

= 1.5 =
* (23 Nov 2009) - First Release.
