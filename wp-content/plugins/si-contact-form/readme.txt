=== Fast Secure Contact Form ===
Contributors: Mike Challis
Author URI: http://www.642weather.com/weather/scripts.php
Donate link: http://www.FastSecureContactForm.com/donate
Tags: Akismet, captcha, contact, contact form, form, mail, email, spam, multilingual, wpmu
Requires at least: 3.4.2
Tested up to: 3.8
Stable tag: trunk

An easy and powerful form builder that lets your visitors send you email. Blocks all automated spammers. No templates to mess with.

== Description ==

Easily create and add forms to WordPress. Fields are easy to add, remove, and re-order. The contact form will let the user send emails to a site's admin, and also send a meeting request to talk over phone or video.

Features: easy form edit, multiple forms, confirmation emails, no templates to mess with, and an option to redirect visitors to any URL after the message is sent. Includes CAPTCHA and Akismet support to block spammers. Spam is no longer a problem. You can add extra fields of any type: text, textarea, checkbox, checkbox-multiple, radio, select, select-multiple, attachment, date, time, hidden, password, and fieldset.

* [FastSecureContactForm.com](http://www.fastsecurecontactform.com/)
* [Download WordPress Plugin Version](http://downloads.wordpress.org/plugin/si-contact-form.zip)
* [Download PHP Script Version](http://www.fastsecurecontactform.com/download-php-script)

= Help Keep This Plugin Free =

If you find this plugin useful to you, please consider [__making a small donation__](http://www.fastsecurecontactform.com/donate) to help contribute to my time invested and to further development. Thanks for your kind support! - [__Mike Challis__](http://profiles.wordpress.org/users/MikeChallis/)


Features:
--------
 * All new improved 4.xx version code base.
 * New user interface with tabs.
 * New 'Fields' tab where you can re-order the display sequence of all the fields via a drag and drop interface.
 * Forms are easy to add, remove, label, and edit and preview. Add as many as you need.
 * Comes with standard fields of (Name, Email, Subject, Message). Any of the standard fields can be disabled.
 * Fields are easy to add, remove, and re-order.
 * Add extra fields of any type: text, textarea, checkbox, checkbox-multiple, radio, select, select-multiple, attachment, date, time, hidden, password, fieldset(box). [See FAQ](http://www.fastsecurecontactform.com/how-to-add-extra-fields)
 * File attachments are supported, see here for details: http://wordpress.org/support/topic/416371
 * Backup/restore tool. You can backup/restore all your forms or single forms and settings.[See FAQ](http://www.fastsecurecontactform.com/backup-restore-forms)
 * Easy to hide subject and message fields for use as a newsletter signup.
 * Send mail to single or multiple contacts.
 * Optional - redirect to any URL after message sent.
 * Optional - posted data can be sent as a query string on the redirect URL. [See faq](http://www.fastsecurecontactform.com/sending-data-by-query-string)
 * Optional - confirmation email message.[See FAQ](http://www.fastsecurecontactform.com/tip-add-email-autoresponder)
 * Valid coding for HTML, XHTML, HTML STRICT, Section 508, and WAI Accessibility.
 * Reloads form data and warns user if user forgets to fill out a field.
 * CAPTCHA can be turned off or hidden from logged in users and or admins.
 * Auto form fill for logged in users.
 * Customizable form field labels.
 * Customizable CSS style.
 * New PHP Sessions are no longer enabled by default allowing for best compatibility with servers, caching, themes, and other plugins. This should resolve any PHP sessions related issues some users had.
 * New added filter hooks for 3rd party plugins and custom modifications.
 * New improved validation of time fields.
 * New improved CAPTCHA images.
 * New improved select options setting.
 * New more optimized HTML indents when view source, added ID tags to form elements.
 * New setting on the Advanced tab to enable a "view / print message" button after message sent. This feature will be skipped if the "redirect after the message sends" is also enabled.
 * New Default CSS style for 'labels on top' is now responsive(note:your theme style has to be responsive also).
 * New improved Styles tab with internal or external CSS Style feature, you choose what you want.
 * New easier to use field labels, tags, and field options. You no longer have to escape comas in form labels and options.
 * New feature: for select, radio, checbox-miltiple, select-multiple field types: If you add options as a key==value set (use == to separate) the value will show on the form and the key will show in the email.
 * New field Setting: "Hide label" check this setting if you want to hide the field label on the form.
 * New field setting: "Default as placeholder" Check this setting if you want the default text to be a placeholder inside the form field. The placeholder is a short hint that is displayed in the input field before the user enters a value. Works with the following input types only: name, email, subject, message, text, textarea, url, and password.
 * New tags capability for fields.
 * New 'Reset Form' button to Tools tab.
 * New 'Delete Form' function to Tools tab.
 * New 'Reset Styles on all forms' button to the Tools tab.
 * New setting to skip names of non-required and unfilled-out fields in emails.
 * Sends Email with UTF-8 character encoding for US and International character support.
 * Pre-fill in form fields from a URL query string. [See FAQ](http://www.fastsecurecontactform.com/query-string-parameters)
 * Save emails to the WordPress database, or export to CSV or Excel. [See FAQ](http://www.fastsecurecontactform.com/save-to-database)
 * I18n language translation support. [See FAQ](http://www.fastsecurecontactform.com/how-to-translate)
 
Online Scheduling, Appointment Booking and Free Invoicing via vCita:
-------------------------------------------
* Add an online scheduling button to your form, or at the bottom of every page 
* Display your up-to-date availability on your website, based on your existing calendar (Google, Outlook, etc)
* Invite leads and clients to schedule a phone call, book an appointment or request a service
* Automated confirmations and reminders will be sent to your clients, including meeting details (time, location or phone number)
* Scheduled appointments will be added to your calendar 
* Built-in phone conference service, and easy integration with Skype, Google Hangout, and other online meetings. 
* Collect payments online before the appointment or invoice and bill your clients for your time and services
* Learn more about vCita [Online Scheduling Software](http://www.vcita.com/software/online_scheduling)

Security:
---------
 * Akismet spam protection support.
 * Spam checks: prevents spammer forcing to:, cc:, bcc:, newlines, and other email injection attempts to spam the world.
 * Makes sure the contact form was posted from your blog domain name only.
 * Secure input validation.
 * Email message footer shows blog username(if logged on), Date/Time timestamp, IP address, and user agent (browser version) of user who contacted you.

CAPTCHA Image Support:
---------------------
 * Uses Open-source free PHP CAPTCHA library by www.phpcaptcha.org (customized version included)
 * Abstract backgrounds with multi colored, angled, distorted, text
 * Arched lines through text
 * Refresh button to reload CAPTCHA
 * CAPTCHA can be disabled on the 'Security' tab.

Requirements/Restrictions:
-------------------------
 * Works with Wordpress 3.4.2+ and WPMU (Wordpress 3.6+ is highly recommended)
 * PHP5 

== Installation ==

1. Install automatically through the `Plugins`, `Add New` menu in WordPress, or upload the `si-contact-form` folder to the `/wp-content/plugins/` directory. 

2. Activate the plugin through the `Plugins` menu in WordPress. Look for the Settings link to configure the Options. 

3. Add the shortcode `[si-contact-form form='1']` in a Page, Post, or Text Widget. Here is how: Log into your blog admin dashboard. Click `Pages`, click `Add New`, add a title to your page, enter the shortcode `[si-contact-form form='1']` in the page, uncheck `Allow Comments`, click `Publish`. 

4. Test an email from your form.

5. Updates are automatic. Click on "Upgrade Automatically" if prompted from the admin menu. If you ever have to manually upgrade, simply deactivate, uninstall, and repeat the installation steps with the new version.


= I just installed this and do not get any email from it, what could be wrong? =


[See FAQ page: How to troubleshoot mail delivery](http://www.fastsecurecontactform.com/email-does-not-send)


== Screenshots ==

1. screenshot-1.gif is the contact form.

2. screenshot-2.gif is the contact form showing the inline error messages.

3. screenshot-3.gif is the `Contact Form options` tab on the `Admin Plugins` page.

4. screenshot-4.gif adding the shortcode `[si-contact-form form='1']` in a Page.

4. screenshot-5.png Schedule an appointment feature.


== Credits ==

* [Mike Challis](http://www.642weather.com/weather/scripts.php) - Plugin Author / Lead programmer
* [Ken Carlson](http://kencarlsonconsulting.com/) - Plugin programmer


== Frequently Asked Questions ==

[See the official FAQ at FastSecureContactForm.com](http://www.fastsecurecontactform.com/faq-wordpress-version)

= I just installed this and do not get any email from it, what could be wrong? =

[See FAQ page: How to troubleshoot email delivery](http://www.fastsecurecontactform.com/email-does-not-send)

= If I upgrade from version 3.xx, will my forms and settings be lost? =
No, it will automatic import of settings from versions 2.5.6 up to 3.xx. As long as you do not use the delete button when deactivating the plugin.
You can and should [make a backup of your forms](http://www.fastsecurecontactform.com/backup-restore-forms).

= I upgraded from version 3.xx, to 4.xx and my forms and settings did not import =

The forms should have imported. In some rare cases, they don't import. Sorry for any inconvenience. 
Update to the latest version, click the button on the Tools tab "Import forms from 3.xx version". 

More help is on this help page:
[I upgraded to 4.xx version and my forms did not import](http://www.fastsecurecontactform.com/forms-did-not-import)

= What happens during upgrade from 3.xx, where are the settings stored? =

The upgrade is run automatically only once after installing or upgrading the 4.xx version over a 3.xx versions.
The 4.xx version uses different wp options settings than 3.xx
The options settings are rows in the wp_options database table.

4.xx wp_options:
fs_contact_global,
fs_contact_form1,
fs_contact_form2,
fs_contact_form3,

3.xx wp_options:
si_contact_gb,
si_contact_form,
si_contact_form2,
si_contact_form3,
si_contact_form4,

During 4.xx install, the installation looks to see if 4.xx options are not present(first time install), and if 3.xx options are present(3.xx was installed previously), if it passes both those tests, then it runs the import code in
class-fscf-import.php

= How do I backup or restore my forms? =

On the Tools settings tab is a backup / restore tool.
The backup / restore feature can be used for backups or as a site to site transfer. You can back up ALL forms and transfer ALL forms to the same or new site using the restore feature. Or you can back up individual forms and restore them to the the same or new site replacing any one form selected during the restore. Please consider that restoring one form or ALL forms makes permanent replacements to the forms already on the site you restore them to.
Read [more about backups](http://www.fastsecurecontactform.com/backup-restore-forms)

= Is this plugin available in other languages? =

Yes. To use a translated version, you need to obtain or make the language file for it.
At this point it would be useful to read [Installing WordPress in Your Language](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") from the Codex. You will need an .mo file for this plugin that corresponds with the "WPLANG" setting in your wp-config.php file. Translations are listed below -- if a translation for your language is available, all you need to do is place it in the `/wp-content/plugins/si-contact-form/languages` directory of your WordPress installation. If one is not available, and you also speak good English, please consider doing a translation yourself (see the next question).

The following translations are included in the download zip file:

* Albanian (sq_AL) - Translated by [Romeo Shuka](http://www.romeolab.com)
* Arabic (ar) partial translation - Translated by Jasmine Hassan
* Bulgarian (bg_BG) - Translated by [Dimitar Atanasov](http://chereshka.net)
* Chinese (zh_CN) - Translated by [Awu](http://www.awuit.cn/) 
* Danish (da_DK) - Translated by [GeorgWP](http://wordpress.blogos.dk/wpdadkdownloads/)
* Farsi(Persian)(fa_IR) partial translation - Translated by Ramin Firooz
* Finnish (fi) - Translated by [Mikko Vahatalo](http://www.guimikko.com/) 
* French (fr_FR) - Translated by [Pierre Sudarovich](http://pierre.sudarovich.free.fr/)
* German (de_DE) - Translated by [Sebastian Kreideweiss](http://sebastian.kreideweiss.info/)
* Greek (el) - Translated by [Ioannis](http://www.jbaron.gr/)
* Hebrew, Israel (he_IL) - Translated by Asaf Chertkoff FreeAllWeb GUILD
* Hungarian (hu_HU) - Translated by [Jozsef Burgyan](http://dmgmedia.hu)
* Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")
* Japanese (ja) - Translated by [Ichiro Kozuka]
* Norwegian Bokmal (nb_NO) - Translated by [Tore Johnny Bratveit](http://punktlig-ikt.no)
* Polish (pl_PL) - Translated by [Pawel Mezyk]
* Portuguese (pt_PT) - Translated by [AJBFerreira Blog](http://pws.op351.net/)
* Portuguese Brazil (pt_BR) - Translated by [Rui Alao]
* Romanian (ro_RO) - Translated by [Anunturi Jibo](http://www.jibo.ro)
* Russian (ru_RU) - Translated by [Iflexion](http://www.iflexion.com/)
* Spanish (es_ES) - Translated by [Valentin Yonte Rodriguez](http://www.activosenred.com/)
* Swedish (sv_SE) - Translated by [Daniel Persson](http://walktheline.boplatsen.se/)
* Traditional Chinese, Taiwan (zh_TW) - Translated by [Cjh]
* Turkish (tr_TR) - Translated by [Tolga](http://www.tapcalap.com/)
* Ukrainian (uk_UA) - Translated by [Wordpress.Ua](http://wordpress.ua/)
* More are needed... Please help translate.

= Can I provide a translation? =

Yes! 
How to translate Fast Secure Contact Form for WordPress
http://www.fastsecurecontactform.com/how-to-translate

= Is it possible to update the translation files for newest version? =

How to update a translation of Fast Secure Contact Form for WordPress
http://www.fastsecurecontactform.com/how-to-update-translation


For more help... [See the official FAQ at FastSecureContactForm.com](http://www.fastsecurecontactform.com/faq-wordpress-version)

= What is the "Schedule an appointment" button on my contact form? = 

You can extend your contact form to let your users to schedule appointments based on your availability.
Learn more about [Why should I add Online Scheduling to my website](http://www.vcita.com/software/online_scheduling)

You can enable or disable this option in the "Scheduling" tab of your contact form plugin settings page.

If you have additional questions visit [vCita Support Page](http://support.vcita.com)

== Changelog ==


= 4.0.18 =
- (07 Dec 2013) - Bug fix: query into hidden field type was not working.
- Bug fix: Standard field labels did not translate in email message.
- Bug fix: Standard field labels changed on the Labels tab did not change in email message.
- Bug fix: fixed HTML validation errors for datepicker css and for textarea.
- Update German, French, and Turkish Languages.

= 4.0.17 =
- (17 Nov 2013) - Bug fix: calendar js conflict when two forms are on same page with date fields.
- Bug fix: "Notice: Undefined index: subject" error if subject field is disabled.
- added filter hook for modifying redirect URL.

= 4.0.16 =
- (25 Oct 2013) - Bug fix: some fields would not validate if value was zero.
- Bug fix: tags for time field types were not working in the confirmation email or subject.
- Bug fix: admin css improvements to avoid conflicting plugins.
- Added ip_address as an available data send / export field.
- Added setting to the Advanced tab to enable and HTML anchor tag on the form POST URL.
- updated German (de_DE) translation. 

= 4.0.15 =
- (12 Oct 2013) - allow HTML in "Your message has been sent, thank you." custom label.
- Bug fix: the attrubutes setting was not working on name, email, subject, fields.
- Bug fix: schedule a meeting button could show when not activated.

= 4.0.14 =
- (11 Oct 2013) - Bug fix: view /print button did not work with some plugins.
- Bug fix: schedule a meeting button user preference default problem.
- updated German (de_DE) translation. 

= 4.0.13 =
- (08 Oct 2013) - Bux fix: some forms would fail to import when label had some Non-US-ASCII or Chinese characters.
- Bug fix: Form save error when tag had some Non-US-ASCII or Chinese characters.  
- Bug fix: button on the Tools tab "Import forms from 3.xx version" did not always work correctly.

= 4.0.11 & 4.0.12 =
- (06 Oct 2013) - Improved placeholder text to work on older browsers.
- Added new style setting for "Placeholder text" so you can change placeholder text color if you want.
- Note: if you are using the External CSS setting you should import the new placeholder css, click "View custom CSS" on the Styles tab to see it.
- added new setting to Advanced tab "Enable to have the email labels on same line as values".
- updated German (de_DE) translation.
- Bug fix: copy styles was not copying all of the style settings.
- Bug fix: button on the Tools tab "Import forms from 3.xx version" did not work correctly.

= 4.0.10 =
- (03 Oct 2013) - Bug fix: Activation generates "unexpected output" notice to admin.
- Bug fix: Could not select "Block spam messages" for Akismet.
- Bug fix: Field Regex was always validating as if required.
- Bug fix: Atachment file types, and file size labels were not working properly.
- Bug fix: Field default text was not showing in form.

= 4.0.9 =
- (02 Oct 2013) - Bug fix: PHP method of calling form display was not working.
- Bug fix: time validation did not work on 24 hour format.

= 4.0.7 & 4.0.8 =
- (01 Oct 2013) - Added a button on the Tools tab "Import forms from 3.xx version" for those who might be troubled by an import failure.
- Bug Fix: fixed a couple problems with importing settings from 3.xx version.
- Bug fix: none of the language translations were working.
- Bug fix: there were some ui image 404 errors from includes/images
- Bug fix: vCita setting error.
- other minor bug fixes.

= 4.0.6 =
- (29 Sep 2013) - Version 4.0.6 is fiinally released after one year of programming by Mike Challis and Ken Carlson and 6 weeks of beta testing.
- Most notable changes:
- All new code base with better use of class structure.
- New user interface with tabs.
- New 'Fields' tab where you can re-order the display sequence of all the fields via a drag and drop interface.
- The standard fields (name,email,subject,message) can now be manipulated and re-ordered along with the extra fields in the new 'Fields' tab.
- Forms are easier to add, remove, label, and select for edit or preview.
- Fields are easier to add, remove, and re-order.
- Easier to use field labels, tags, and field options. You no longer have to escape comas in form labels and options.
- Automatic import of settings from version 2.5.6 and all 3.xx versions.
- You can restore your backed up forms from version 2.8 and newer with 'Restore Settings' on the new 'Tools' tab.
- Updated Online Meeting Scheduler by vCita on the new 'Scheduler' tab.
- Updated 'Constant Contact' plugin integration for the new 'Newsletter' settings tab.
- PHP Sessions are no longer enabled by default allowing for best compatibility with servers, caching, themes, and other plugins. This should resolve any PHP sessions related issues some users had.
- Added filter hooks for 3rd party plugins and custom modifications.
- Improved validation of time fields.
- Improved CAPTCHA images.
- More optimized HTML indents when view source.
- New setting on the Advanced tab to enable a "view / print message" button after message sent. This feature will be skipped if the "redirect after the message sends" is also enabled.
- Default CSS style for 'labels on top' is now responsive(note:your theme style has to be responsive also).
- New feature: for select, radio, checbox-miltiple, select-multiple field types: If you add options as a key==value set (use == to separate) the value will show on the form and the key will show in the email.
- New field Setting: "Hide label" check this setting if you want to hide the field label on the form.
- New field setting: "Default as placeholder" Check this setting if you want the default text to be a placeholder inside the form field. The placeholder is a short hint that is displayed in the input field before the user enters a value. Works with the following input types only: name, email, subject, message, text, textarea, url, and password.
- New tags capability for fields.
- New 'Reset Form' button to Tools tab.
- New 'Delete Form' function to Tools tab.
- New 'Reset Styles on all forms' button to the Tools tab.
- Lots of work on the Style tab:
- Added more style settings for Style of labels, field inputs, buttons, and text.
- Separated style sections into "Alignment DIVs", and "Style of labels, field inputs, buttons, and text".
"Alignment DIVs" settings are for adjusting the alignments of the form elements.
You can also check "reset the alignment" to return to defaults and make the "labels on top" or "labels on left".
"Style of labels, field inputs, buttons, and text" are for setting style of the form labels, field inputs, buttons, and text.
This is a great way to change label or field colors. You can add color:red; any style attributes you want.
You can also check "reset the styles" to return to defaults.
- New setting on the Style tab: "Select the method of delivering the form style":
"Internal Style Sheet CSS (default)"
"External Style Sheet CSS (requires editing theme style.css)"
By default, the FSCF form styles are editable below when using "Internal Style Sheet CSS". The style is included inline in the form HTML.
CSS experts will like the flexibility of using their own custom style sheet by enabling "External Style Sheet CSS", then the FSCF CSS will NOT be included inline in the form HTML, and the custom CSS below must be included in the style.css of the theme. Be sure to remember this if you switch your theme later on.
Premium themes can now add support for Fast Secure Contact Form style in the theme's CSS. Select "External Style Sheet CSS" when instructed by the theme's installation instructions.
- New "Reset Styles on all forms" button to the Tools menu, and you should click it once after upgrading from version 3.xx to acquire the many changes/improvements to the default styles.
- Editorial change: E-mail is now Email, and e-mail is now email.
- Includes all the recent improvements from the 3.xx versions.
- Hundreds of bug fixes and code improvements.

= 4.0.5 Beta 5 =
- (27 Sep 2013) - added ability to use "Default as placeholder" setting with "Enable double email entry" setting enabled. The "Default" setting should be in this example format: "Email==Re-enter Email". Separate words with == separators.
- added ability to use "Default as placeholder" setting with "First Name, Last Name" setting enabled. The "Default" setting should be in this example format: "First Name==Last Name". Separate words with == separators.
- When using "Default as placeholder" setting with "First Name, Middle Name, Last Name" setting enabled. The "Default" setting should be in this example format: "First Name==Middle Name==Last Name". Separate words with == separators.
- added a warning message if placeholder is enabled with empty Default text.
- added a warning message if double email setting is enabled with Default text in wrong format.
- added a warning message if "First Name, Last Name" is enabled with Default text in wrong format.
- Bug Fix: left a diagnostic print statement in the code in the placeholder feature.
- added new feature for select, radio, checbox-miltiple, select-multiple field types: If you add options as a key==value set (use == to separate) the value will show on the form and the key will show in the email.
- added two new settings to fields:
"Hide label" check this setting if you want to hide the field label on the form.
"Default as placeholder" Check this setting if you want the default text to be a placeholder inside the form field. The placeholder is a short hint that is displayed in the input field before the user enters a value. Works with the following input types only: name, email, subject, message, text, textarea, url, and password.
- added a "Reset Styles on all forms" button to the Tools menu, and I suggest clicking it each time you replace the plugin files to test the beta because I have been making many changes/improvements to the default styles.
- added new css element "Field Pre-Follow DIV" (fscf-div-field-prefollow) to properly set fields just to before the follow fields to the proper with while allowing wider labels for other fields.
- changed the CSS for the "Field Left DIV" (fscf-div-field-left) on 'labels on top' setting to fix labels not wide enough problem.
- Fix bug: option value of '0' was deleted.
- got rid of the additional sentence "Enter your email again." and the fields are now "Email", and "Re-Enter Email", just like Facebook signup has it.
- On Fields tab changed 'Name' setting to 'Label' as it perfectly relates to the actual form element.
- renamed the 'Border Style' setting to 'Fieldset Box' as it perfectly relates to the actual form element.
- also the external CSS element 'fscf-border' was renamed to 'fscf-fieldset'
- added width:99%; max-width:250px; to the Field Follow DIV (labels on top) so follow fields are matching width
- added a separate style for fieldset field, so now there is "Form Fieldset Box" (fscf-fieldset) for the form fieldset and "Field Fieldset Box" (fscf-fieldset-field) for fieldset field types.


= 4.0.4 Beta 4 =
- (24 Sep 2013) - added a setting on the Advanced tab to enable a "view / print message" button after message sent. This feature will be skipped if the "redirect after the message sends" is also enabled.
- added vCita Online Appointment scheduler.
- added a couple more style settings for vCita.
- integrated vcita with external style CSS feature.
- added mode Save Changes buttons on settings pages.
- Optimized default styles some more. To aquire all the new style changes, you have to click the checkboxes to reset the styles on the Style settings tab.
- Default CSS for 'labels on top' is now responsive(note:your theme has to be also).
- Removed settings for text field size, textarea cols and rows, because this is now controlled by CSS instead.
- Adjusted CAPTCHA fonts larger.
- All time field selects default to blank, then you select them.
- Improved the time validation: if a time field is not required and you select hour but not day, it will fail validation with message: "The time selections are incomplete, select all or none."
- the * prefix is really not necessary for single selections in the email, so I removed it,
- you should only have a ' * ' separating fields with multiple selected options from now on.
- Fix bug: time fields now obey required, not required.
- added new setting to Advanced tab: "Enable to skip names of non-required and unfilled-out fields in emails."
- Fix bug: required field checkbox was stuck on required on every field when double email field was enabled.
- added more ID tags to form HTML.


= 4.0.3 Beta 3 =
- (16 Sep 2013) - added more ID tags to form HTML.
- More work on the Style tab:
- added new setting to "Select the method of delivering the form style":
- "Internal Style Sheet CSS (default)"
- "External Style Sheet CSS (requires editing theme style.css)"
- By default, the FSCF form styles are editable below when using "Internal Style Sheet CSS". The style is included inline in the form HTML.
- CSS experts will like the flexibility of using their own custom style sheet by enabling "External Style Sheet CSS", then the FSCF CSS will NOT be included inline in the form HTML, and the custom CSS below must be included in the style.css of the theme. Be sure to remember this if you switch your theme later on.
- Premium themes may have added support for Fast Secure Contact Form style in the theme's style.css. Select "External Style Sheet CSS" when instructed by the theme's installation instructions.
- Note: if you use the setting "reset the alignment styles to labels on left(or top)", or "Reset the styles of labels, field inputs, buttons, and text", then the custom CSS below will reflect the changes. You would have to edit your custom CSS again to see the changes on the form.
- "Required field" will also be set when double email is enabled in the Email Address field settings.
- added Save Changes button to field details on the Fields tab
- added (standard field) note next to standard field names on the Fields tab.
- added note "Standard field names can be changed on the Labels tab." to the field details on the Fields tab
- added standard field note will indicate if a (standard field name was changed on the Labels tab).
- added a couple more filters.
- If you have changed a standard field label, it will display the changed label in bold on the Fields tab.
- Optimized code for email from name when name field is disabled
- Fix big: date validation failed if date field was emptied, even if date not required.
- Added "After form message" setting to the "Advanced' tab, you can use this to add any HTML after the form.
- Adjusted CAPTCHA difficulty down slightly.
- Fix big: now uses correct Return-path address setting for the confirmation email
- Fix bug: confirmation email might send to admin if email field is disabled
- updated http://www.fastsecurecontactform.com/how-to-add-extra-fields
- Editorial changes to field instructions.
- Fix bug: the tools tab lost focus when submitting a tool option.

= 4.0.2 Beta 2 =
- (30 Aug 2013) - Lots of work on the Style tab:
- Added more style settings for Style of labels, field inputs, buttons, and text.
- Separated style sections into "Alignment DIVs", and "Style of labels, field inputs, buttons, and text".
- "Alignment DIVs" settings are for adjusting the alignments of the form elements.
- You can also check "reset the alignment" to return to defaults and make the "labels on top" or "labels on left".
- "Style of labels, field inputs, buttons, and text" are for setting style of the form labels, field inputs, buttons, and text.
- This is a great way to change label or field colors. You can add color:red; any style attributes you want.
- You can also check "reset the styles" to return to defaults.
- Fix bug: The donate box div did not minify.
- Fix bug: The Label CSS and Field CSS field options did not work on all field types.
- Fix bug: max_forms_num could get out of sync when deleting forms.
- Fix bug: setting was ignored "Enable sender information in email footer"
- Fix bug: Custom Label CSS was ignored for checkbox, checkbox-multiple, and radio fields.
- Fix bug: CSS setting 'labels on left' messed up checkbox, checkbox-multiple, and radio fields.
- Fix bug: CSS setting 'labels on left' messed up HTML before/after form field position.
- Fix bug: Field Label setting for the Reset button adds onclick= to the label.
- Fix bug: When viewing a form preview, changing the form select switches back to Edit mode.
- Fix Bug: Reply-To email header was set to incorrect address.
- "Email From" setting renamed to the more accurate "Return-path address".
- Fixed and added more error label settings.
- Moved "Enable PHP sessions" setting to the 'Advanced' tab.
- Split 'Styles/Labels' tab into a 'Styles' tab and a 'Labels' tab.
- Optimize backup file download then test with IE, FF, Chrome, Opera.
- Added form_number to the 'fsctf_mail_sent' action hook object array
- Added 'Domain Protect Settings' to the 'Security' settings tab
- Added setting for "Additional allowed domain names(optional)" to the 'Security' settings tab.
- Added show/hide details labels to field settings toggle buttons.
- Added focus to new field with message when adding New Field.
- Added setting: CSS style for form checkbox, checkbox-multiple, and radio labels. (useful to change colors).
- Updated admin and form stylesheets.
- Edited some settings labels.
- More optimized HTML indents when view source.
- Minor UI changes.

= 4.0.1 Beta 1 =
- (15 Aug 2013) - After one year of hard work, Mike Chalis and Ken Carlson have redeveloped the whole plugin.
- All new codebase with better use of class structure.
- New user interface with tabs.
- New 'Fields' tab where you can re-order the display sequence of all the fields via a drag and drop interface.
- The standard fields (name,email,subject,message) can now be manipulated and re-ordered along with the extra fields in the new 'Fields' tab.
- Forms are easier to add, remove, label, and select for edit or preview.
- Fields are easier to add, remove, and re-order.
- Easier to use field labels, tags, and field options. You no longer have to escape comas in form labels and options.
- A 'Reset Form' and 'Delete Form' button has been added to the new 'Tools' tab.
- Automatic import of settings from older version 2.5.6 and newer.
- You can restore your backed up forms from version 2.8 and newer with 'Restore Settings' on the new 'Tools' tab.
- Updated Meeting Scheduler - by vCita is still being developed for the new 'Meeting' settings tab.
- Updated 'Constant Contact' plugin integration for the new 'Newsletter' settings tab.
- PHP Sessions are no longer enabled by default allowing for best compatibility with servers, caching, themes, and other plugins. This should resolve many sessions related issues some users had.
- Added filter hooks for 3rd party plugins.
- Removed HTML before/after field divs.
- Relocated some email settings from 'Basic Settings' to 'Advanced' tab.
- Editorial change: E-mail is now Email, e-mail is email
- Includes all the recent improvements from the 3.xx versions.
- Many bug fixes and code improvements.

= 3.1.9 =
- (15 Aug 2013) - Added announcement of Fast Secure Contact Form Version 4.0 Beta was released August, 15 2013. Please help test it!
- [Download and test the 4.0 Beta](http://www.fastsecurecontactform.com/beta)

= 3.1.8.6 =
- (13 Aug 2013) - fixed label style for checkbox, checkbox-multiple, and radio field types.
- removed divs for HTML before/after field settings.
- minor bug fixes.

= 3.1.8.5 =
- (18 Jul 2013) - added new settings: "Submit button input attributes" and "Form action attributes". These can be used for Google Analytics tracking code.
- added captcha font randomization.
- fixed date does not have to be required.
- fixed date error message translation.

= 3.1.8.4 =
- (07 Jul 2013) - Fixed CAPTCHA PHP warning on some servers.
- Added better date input validation.

[Fast Secure Contact Form - WordPress changelog archive](http://www.fastsecurecontactform.com/changelog-archive)

