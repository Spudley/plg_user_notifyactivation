Notify Activation
-----------------

A Joomla plugin to add notes to user accounts when the account is activated.

If you like this extension, please review it at the Joomla Extensions Directory: https://extensions.joomla.org/extensions/extension/clients-a-communities/user-management/notify-activation

Introduction
------------

This is a plugin for Joomla that triggers the system to create a user note when an account is activated.

The generated user note will allow site administrators to see at a glance when and how a user account was activated.

This can be useful for reference and for resolving disputes and queries about account activation.

There are numerous ways that an account can be activated, depending on the setting of the User component config setting "New User Account Activation".

####Activation setting 0: "None"
* Immediate activation: Site is configured to activate accounts as soon as they are registered.

####Activation setting 1: "Self"
* Self activation: User clicks on the activation link provided in his welcome email after registration.

####Activation setting 2: "Administrator"
* Self verification: User clicks on the verification link provided in his welcome email after registration. This does not activate the account, but triggers a further email to be sent to the site administrators.
* Admin activation: One of the site administrators then clicks on the link in their email to activate the account.

####Account activation via the admin panel.
* Admin-created accounts: An account is created by a site administrator from within the admin panel. Accounts created this way are active immediately on creation.
* Manual activation: An admin user clicks the tick icon in the 'Activated' column of the user list to activate an account manually.

All six of these events are recorded by the plugin, giving different messages in the user note that is generated. The text of the notes can be configured in the plugin's config page.

Where the action is carried out by someone other than the account holder, the notes try to include information about who that person was. This applies to the actions done by admin users. In the case of the two actions done on the admin panel, the system can work this out by getting the user details for the logged in admin user. However in the case of the admin activation link, the admin user does not need to be logged in for it to work. All admin users are sent the same link, so for this event we cannot tell which admin user completed the activation; just that it was done by one of them. The other events done by admin users do require the admin user to be logged in, and thus the plugin can report who did them.

In addition, as of version 2.1, the plugin can also send users an email notifying them of their account being created or activated by an administrator. And in 2.2, the site administrators can be notified by email as well.


Version History
----------------
* 1.0.0     2016-07-03: Initial release.
* 1.0.1     2016-07-14: Bug fixes: Now works in all three scenarios.
* 2.0.0     2016-12-13: Made it work with the "Administrator" option for new account activation.
* 2.1.0     2017-01-30: Added ability to send activation emails.
* 2.2.0     2017-02-06: Activation emails can now go to either the end user or to admin users (or both).


Installation
----------------
This is a standard Joomla plugin. Installation is via Joomla's extension manager.


Usage
----------------
The plugin has several parameters:

* User note category - This parameter allows you to specify which user notes category the generated notes should be created in. Leave blank for the default 'Uncategorised'.

* Several note text fields - these fields hold the text 

* Subject Text - this is the text that will be used for the for the subject text on the user notes.

There is also a second configuration tab, for emails. This tab has three toggle switches that allow you to set the plugin to send emails to the end user when their account is activated by the administrator, either by the administator creating the account manually, activating the account via an email link, or directly in the admin panel. You can also use these switches to send notification emails to all the admin users of the system.

Note: You should only enable these switches if your system does not already send emails to notify the user of account activation in these three cases. Depending on your Joomla config and other plugins, you may not need all of these switches enabled.

The email that is sent to users in response to these three events uses the same text as Joomla's built-in account activation notification. This default text is copied from the standard language translation files provided with Joomla, but independant translations are provided by the plugin. If you wish to override it, the relevant translation IDs can be found in the en-GB.plg_user_notifyactivation.ini file provided with this plugin.


Limitations
----------------
The plugin is obviously only going to be useful if your Joomla site requires user accounts to be activated. If you set the site to not require account activation, the plugin will still function but will produce erroneous user notes.

The feature to send an activation email to the user is only available for activation events that are triggered by the admin. In addition, some of these events may trigger an email to the user already, depending on your site config. It is also possible that future Joomla updates or other plugins may add similar functionality which could also result in emails being sent twice. It is up to the site administrator to ensure that your site is configured to only send one email for each event.


Motivation
----------------
This plugin was written from scratch after attempting to resolve a user query over account activation. The account had been activated but the user claimed not to have done so himself and none of the site admins had done it either. We were unable to answer the question. Having the functionality in this plugin would have helped to resolve this.


To Do
-----

* Move the message strings into a language translation file rather than having them as config settings.
* Add text to the end of the notes stating that they were generated by the plugin.


Caveats
-------

* When upgrading, if the note text does not appear, or appears incorrectly, try going to the plugin config page and hitting 'Save'.
* With regard to the note generated when an admin user clicks an email link to activate an account: The plugin cannot know which admin user does this, so it can't specify them in the note text as it does for other events done by an admin user.


License
----------------
As with all Joomla extensions, this plugin is licensed under the GPLv2. The full license document should have been included with the source code.
