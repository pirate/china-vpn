=== Plugin Name ===
Contributors: blobfolio
Donate link: http://www.blobfolio.com/donate.html
Tags: security, login, password, cats, generator, wp-content, PHP, malware, exploit
Requires at least: 3.4
Tested up to: 3.8.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple, light-weight collection of tools to help protect wp-admin, including password strength requirements and brute-force log-in prevention.

== Description ==

Apocalypse Meow provides several tools to help you lock down the wp-admin area:

  * Brute-force log-in protection: temporarily disable and replace the log-in form after a specified number of failures are detected.
  * Specify minimum password requirements for users to ensure nobody chooses something stupid like "password123".  :)
  * See a complete history of log-in attempts, successes, and bans; optionally downloadable in CSV format.
  * Disable the "generator" meta tag, which betrays which version of WordPress you are running (thereby making exploits more easily targetted).
  * Prevent the direct execution of PHP scripts in `wp-content/`.
  * Rename the default "admin" user.
  * Disable the theme/plugin editor.

== Installation ==

1. Unzip the archive and upload the entire `apocalypse-meow` directory to your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Review and change the settings by selecting 'Apocalypse Meow' in the 'Settings' menu in WordPress.

== Frequently Asked Questions ==

= Is this plugin compatible with WPMU? =

The plugin is only meant to be used with single-site WordPress installations.  Some features may still work under multi-site environments, however it would be safer to use some other plugin that is specifically marked WPMU-compatible instead.

= I have accidentally banned myself, what can I do? =

If you have accidentally banned yourself, you have a few options: A) wait until the defined time has elapsed; B) log-in from a different network IP (like from a friend's house); C) delete the `apocalypse-meow` plugins directory via FTP to force uninstallation of the plugin.

Remember: You can whitelist one or more IP addresses via the settings page to prevent just this sort of thing!

= When exactly is the Apocalypse triggered and how long will it last? =

There are three relevant settings to consider:

1. Number of failures allowed - If this number is exceeded within the time period, the Apocalypse is triggered for the offending individual.
2. Do successful log-in's reset the failure count? - If "yes", the failures counted in #1 must also occur after the most recent successful log-in attempt the offender has made, if any.
3. The length of time to look at - This could be thought of as the time it takes for a failure to expire from relevance, thus it is only within this window that failures are counted against a person, and if the Apocalypse is triggered, it will last until the oldest of the applicable failures expires.  This means the actual length of banishment can vary depending on how spread out the failed log-in attempts are.  If the limit is reached in rapid succession, the Apocalypse will last more or less the entirity of the window.  If, however, the failures are spaced evenly across the window, the Apocalypse may only last a minute.

Here is an example to illustrate the above point:  Say the failure limit is 2, we don't reset on success, and the window is 2 hours.  If an evildoer messes up the log-in at 10:01, 10:02, and 10:03, the Apocalypse is triggered and lasts until 12:01.  If the evildoer were to immediately re-mess up the log-in once more, he/she would again trigger the Apocalypse (failures at 10:02, 10:03, and 12:01), but this time only for one minute, because at 12:02 the 10:02 failure will expire, leaving just 2 failures within the window.

= What are reasonable log-in protection settings? =

The default values are pretty reasonable, if I do say so myself:

1. The failure limit is set to 5 - five failures for fat fingers and forgetfulness should be plenty.
2. Yes, reset fail count after successful log-in - if you can't trust logged-in users, who can you trust?
3. The failure window is set to 43200 seconds (12 hours) - this is long enough to make most evildoers give up, while not being so long as to ruin the life of a legitimate user accidentally caught up in it all.

= The CSV link doesn't work; I just get a generic "404 Not Found" page. =

The WordPress permalinks system is kinda finicky.  Go to Settings > Permalinks and re-save your configuration.

= Can I see the passwords people tried when logging in? =

Of course not!  Haha.  Apocalypse Meow only records the following information with each log-in attempt:

1. WP username
2. IP address
3. Browser (this is self-reported, so take it with a grain of salt)
4. Status (e.g. success or failure)

= The kitten graphic is stupid and unprofessional.  Can I change the Apocalypse page? =

Yes, you can change both the page title and content via the Settings > Apocalypse Meow page.

= What do the different log-in statuses mean on the Log-in History page? =

* Success: the log-in was successful;
* Failure: the log-in was a big, fat failure;
* Apocalypse: the Apocalypse page was displayed instead of the log-in form;

= Wait... how do plugins and themes work if direct PHP execution is disabled?! =

WordPress themes and plugins are made up of PHP scripts that *should* only be executed indirectly through the WordPress engine. Of course, some plugins and themes are poorly coded and do not fully exist within the WP framework and so might break if direct PHP execution is disabled. But hey, if things break, simply disable this option. ;)

== Fail2ban setup ==

Some robots are so dumb they'll continue trying to submit credentials even after the login form is replaced, wasting system resources and clogging up the log-in history table.  One way to mitigate this is to use a program like Fail2ban to watch for excessive Apocalypseness and ban the culprits via the firewall.  Check your access logs for requests for wp-login.php returning a 403 status code, like:
> `failregex =  ^<HOST> -.*"(GET|POST).*/wp\-login\.php.*\ 403\ .*$`

== Screenshots ==

1. All options are easily configurable via a settings page.
2. The handy record of log-in attempts, optionally downloadable as a CSV file.
3. Pretty log-in statistics!

== Changelog ==

= 1.4.5 =
* Warn administrators on settings page of potential proxy/intranet-type issues;
* Only show .htaccess options on Apache servers;
* Use wp_die() for Apocalypse screen;
* Database maintenance on by default;

= 1.4.4 =
* Minor update, file clean up.

= 1.4.3 =
* Minor bug fix ensures variables are declared at activation;

= 1.4.2 =
* Replace deprecated $wpdb->escape() with esc_sql();

= 1.4.1 =
* Minor update, replacing a couple functions that are deprecated as of PHP 5.5.0;

= 1.4.0 =
* Added log-in jail page to view currently banned IPs;
* Added ability to temporarily pardon a banned IP;
* Log-in history now displayed in viewer's timezone;

= 1.3.6 =
* Bug fix: call-time pass-by-reference warning/error in PHP 5.3+;

= 1.3.5 =
* Changed the fail window unit from seconds to minutes;
* More efficient logging of Apocalypse triggers;
* Simplified Apocalypse page options;
* Bug fix: database upgrade procedure skipped;

= 1.3.4 =
* Lowered data retention minimum to 10 days;
* Option to manually clear data;
* Uninstallation now removes all plugin data/settings;
* Option to disable theme/plugin editor;
* Prevent installation on WPMU blogs;
* Use $_SERVER instead of getenv() as it is compatible with more server environments;
* Miscellaneous small bug fixes;

= 1.3.3 =
* Added log-in statistics;
* Storing UA string with log-in attempt is now optional (default disabled);
* Log-in protection settings now hidden if log-in protection is disabled;
* Database maintenance settings now hidden if maintenance is disabled;

= 1.3.2 =
* Code clean up: use existing WP CSS for log-in history table;
* Set 403 status header when displaying Apocalypse screen;

= 1.3.1 =
* Compatibility with 3.5;
* Code clean up: all queries now run through $wpdb;

= 1.3.0 =
* Added the ability to rename the default WordPress user to something less predictable;
* Minor bug fixes;

= 1.2.0 =
* Added the ability to disable the direct execution of PHP scripts in wp-content/;
* Re-organized the settings page;

= 1.1.0 =
* Added customizeable page title and content for the Apocalypse page;
* Added Apocalypse page display logging;
* Improved timestamp handling;
* Un-embedded kitten graphic for improved support with older browsers;

= 1.0.0 =
* Apocalypse Meow is born!

== Upgrade Notice ==

= 1.4.5 =
Various UX improvements, mostly in the form of clearer warnings and explanations.

= 1.4.4 =
Minor update, file clean up.

= 1.4.3 =
Minor update, fixes a small bug.

= 1.4.2 =
Minor update, replace deprecated $wpdb->escape() with esc_sql().

= 1.4.1 =
Minor update, replacing a couple functions that are deprecated as of PHP 5.5.0.

= 1.4.0 =
New features added.

= 1.3.6 =
Bug fix affecting PHP 5.3+.

= 1.3.5 =
Minor improvements and bug fixes.

= 1.3.4 =
New features and bug fixes.

= 1.3.3 =
Added a stats page and more settings.

= 1.3.2 =
Minor tweaks and optimizations.

= 1.3.1 =
This release contains a bit of code clean up.

= 1.3.0 =
An additional security feature and minor bug fixes.

= 1.2.0 =
This release adds a new feature and cleans up the settings page, which is getting kinda long.

= 1.1.0 =
This release provides more accurate timestamp handling and new features.