=== Wordpress Poll ===
Contributors: vinoj.cardoza
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=vinoj%2ecardoza%40gmail%2ecom&currency_code=GBP&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: poll, cardoza, vote, widget, booth, polls, polling, votes, voting
Requires at least: 3.0
Tested up to: 3.6.1
Stable tag: trunk
License: GPLv2 or later

Wordpress Poll is completely ajax powered polling system. This poll plugin supports both single and multiple selection of answers.

== Description ==
This poll has the following features which uses both ajax and jquery.

1. Administrator have options to display more than one poll in the widget area.
2. Administrator can specify how many polls to be displayed in the archive page.
3. Administrator can view the poll statistics by date wise.
4. User logs can be seen in the admin section.
5. Image polls available.
6. Poll access can be locked by cookies, IP address or user id.
7. Translation enabled.

= Note =
1. If you find any bugs, please report in the following link, so that it will be fixed as quick as possible.
2. If you think any feature adding to this plugin can improve its features, please recommend it in the following link.
3. You can add any poll into your post or page by using the shortcode. For information how to do it, please go to Frequently asked questions.

= Donations =

Thanks for downloading and installing my plugin. You can show your appreciation and support future development by donating.

For more visit: http://www.vinojcardoza.com/blog/cardoza-wordpress-poll/

= Translation =
Some of the translation were done using a translation software. I am not sure about the correctness of the translation. If you find some mistakes in the translation in your language, please let me know through the following link comments section. Also, if you couldn't find your language and you can do it, please let me know.

= Translated Languages =
* Croatian (Translated by Bojan KS)
* Czech (Translated by Vaclav Cadek st.)
* Danish (Translated by Henrik van der Buchwald)
* Dutch (Translated by Niels de Bruin)
* French (Translated by Laurent Verpeet)
* German (Translated by Peter Kaulfuss)
* Greek (Translated by Kontosis Konstantinos)
* Italian (Translated by Roberto Bani)
* Polish (Translated by Robert Szymanski)
* Portuguese-Brazilian (Translated by Rodrigo)
* Romanian (Translated by Dachin Raul)
* Russian (Translated by Boris & Levatti)
* Slovak (Translated by Branco Radenovich)
* Spanish (Translated by Diego Silva Opazo)

== Installation ==

1. Download the plugin.
2. Upload to your blog (/wp-content/plugins/).
3. Activate it.
4. Click the 'Poll' menu.
6. Fill in the options.
a. Create new poll by clicking Add new poll.
b. You can setup the widget options by clicking 'Widget Options'.
c. Poll options should be filled and saved before starting to display the first poll.
d. Use 'Manage polls' to edit and delete polls.
7. Then go to widget and drag and drop Wordpress Poll in the area you want to display the plugin.

Important Note: It is mandatory to save all the mandatory field options in this plugin.

= Creating archive poll page =
1. Go to WP-Admin -> Pages -> Add New.
2. Type any title you like in the post's title area and paste the following shortcode in the page content [cardoza_wp_poll_archive]
3. WordPress will generate the link to the page. Copy the link.
4. Go to poll options in 'Poll'
5. Click the Poll options tab.
6. Paste the archive page link in 'Poll archive URL'.
7. Save the options.

You're done!

Uninstalling is as simple as deactivating the plugin.

== Screenshots ==

1. screenshot-1.gif
2. screenshot-2.gif
3. screenshot-3.gif
4. screenshot-4.gif
5. screenshot-5.gif
6. screenshot-6.gif
7. screenshot-7.gif

== Frequently Asked Questions ==

= How do I display a particular poll in a page or post? =
Its very simple to add poll in any page or post in your site.

Copy the following shortcode and paste it in your post or page for displaying the poll.

[cardoza_wp_poll id=poll_id]

poll_id - this the ID of the poll which you can find it on the 'Manage Polls' of your poll plugin page.

For eg: If you want to display the poll where the ID is 19 then the shortcode will be
[cardoza_wp_poll id=19]

That's it. Check your page or post now.

= How do I check the results without polling myself? =
1. Go to Polls in your Wordpress backend.
2. Click the Manage Polls tab.
3. Now you can see the list of polls.
4. The last colum of the table will have a button 'View Result'.
5. Click 'View Result'.
6. Once clicked it will display the poll result.

= How do I add images poll? =
1. Add the images in your media of wordpress backend.
2. Copy the 'File Url' of your uploaded image.
3. Paste the Url in the Answers of 'Add New Image Poll'.
4. Done

if you have any queries post it at http://www.vinojcardoza.com/blog/cardoza-wordpress-poll

== Change Log ==
= Version 35.0 =
* Security vulnerabilities removed.

= Version 34.06 =
* Russian translation and security improved.

= Version 34.05 =
* Ajax referer and nonce added to improve security (Recommended by Marcela Benetrix).

= Version 34.04 =
* Admin template changed from tabbed structure to  normal wordpress menu style.
* Polish translation added (Translated by Robert Szymanski)
* Provision for custom CSS code included in the plugin.

= Version 34.03 =
* Admin css conflict with other themes and plugins is rectified now.

= Version 34.02 =
* All the notices on the debug mode were rectified (Thanks to Amir for notifying)

= Version 34.01 =
* Slovak translation added (Translated by Branco Radenovich)

= Version 34.0 =
* Footer display in Wordpress 3.5 Version updated for poll plugin

= Version 33.9 =
* Romanian translation added (Translated by Dachin Raul).

= Version 33.8 =
* Plugin campatibility ensured for Wordpress 3.5 release.

= Version 33.7 =
* Czech translation added (Translated by Vaclav Cadek st.).

= Version 33.6 =
* Important security vulnerability updated. (Suggested by Ryan Satterfield, Planet Zuda, LLC, http://planetzuda.com)  
* Croatian translation added (Translated by Bojan KS).
* Greek translation added (Translated by Kontosis Konstantinos)

= Version 33.5 =
* Donation request for adding new image poll is removed and available for free to everyone.

= Version 33.4 =
* New feature - Image poll included in the plugin.

= Version 33.3 =
* Javascript function for identifying max no of answers changed.

= Version 33.2 =
* A major bug multiple choice polls do not lock out more choices is sorted out now.

= Version 33.1 =
* Polls archive page modified.
* Polls archive page option amended for the display.
* New (Not yet opened) status added for the poll.

= Version 33.0 =
* Italian translation added (Translated by Roberto Bani).
* Spanish translation modified (Translated by Diego Silva Opazo).

= Version 32.9 =
* Danish translation added (Translated by Henrik van der Buchwald).

= Version 32.8 =
* Portuguese-Brazilian translation added (Translated by Rodrigo).
* All the Markup Validation done.

= Version 32.7 =
* Russian translation added (Translated by Boris).
* Some of the Markup Validation done.

= Version 32.6 =
* Poll Vote button height and width fixed and not restricted.

= Version 32.5 =
* Minor bug fixed to display the vote percentage.

= Version 32.4 =
* Date picker added for the start and end date of add new poll.
* Some animation effects added to the tab styles.
* Minor modification was done to the Dutch translation.

= Version 32.3 =
* The accuracy of result percentage calculation was recoded.
* Translation in Dutch released. (Translated by Niels de Bruin)
* Translation in German released. (Translated by Peter Kaulfuss)

= Version 32.2 =
* 'View Result' -toggle option enabled in the widget and shortcode.

= Version 32.01 =
* French translation updated (Translated by Laurent Verpeet).

= Version 32.0 =
* Translation enabled.

= Version 1.4 =
* Cardoza Wordpress Poll renamed to Wordpress Poll
* Note:If you don't see the widget after updating the plugin, please check you widget in the admin section whether it is enabled.

= Version 1.3 =
* A minor default option for Lock by amended in the plugin coding.

= Version 1.2 =
* New option for locking the poll added to the plugin.
* Now site administrators can select the option to lock the poll by either cookies or IP address.
* New feature added to view the list of users polled.

= Version 1.1 =
* Minor tweaks added to the code (Suggested by Paul Bearne).

= Version 1.0 =
* A minor bug fixed on displaying the polls for the logged in user.

= Version 0.9 =
* As requested by lots of people, the user logged in access for polling is enabled.
* Now if you configure to allow only logged in users to poll, then the polling will be manipulated by user id.

= Version 0.8 =
* A minor bug fixed while voting on multiple option answers.

= Version 0.7 =
* Previous plugin update had some bugs on creating polls and fixed now.(Thanks to Bill Ford)

= Version 0.6 =
* Total Votes modified for multiple type answers in displaying the results. (Suggested by HR)
* Default value for no of polls in Widget is set to 1 if the option was not set by the administrator.
* Random answer display of answers bug fixed (Suggested by Bill Ford).
* Mandatory fields(*) notification given in the user interface (Suggested by Keith).

= Version 0.5 =
* Poll Statistical analysis implemented such that the administrator can see the poll logs in a graph format.
* This poll statistics feature will be only available to those who have installed or updated after 24th February 2012. 
* Code structure formalised again.
* Database modified to get the poll logs.

= Version 0.4 =
* Poll archive page displays the poll id, poll duration and poll status (Suggested by : Bill Ford, Founder of JoshuaTreeStar.com)
* New option 'View Result' added to the 'Manage Polls' page to enable the administrator to see the poll results from Wordpress backend (Suggested by: Amber).
* 'See Previous Polls changed' to 'See polls & results'.

= Version 0.3 =
* New feature added - Shortcode can be added on any page or post to display a particular poll.
* Minor-bug for displaying poll results as soon as voted fixed.

= Version 0.2 =
* Pagination on the archive poll page validated.
* A seperate function file created to manipulate the front end functions.

