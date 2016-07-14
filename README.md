Notify Activation
-----------------
This is a plugin for Joomla that triggers the system to create a user note when an account is activated.

The generated user note will allow site administrators to see at a glance when and how a user account was activated.

This can be useful for reference and for resolving disputes and queries about account activation.

There are three ways that an account can be activated. These are all recorded by the plugin using different messages in the generated user note:

* Self activation: User clicks on the activation link provided in his welcome email after registration.
* Admin activation: A site administrator activates the account from the user management section of the admin panel.
* Immediate activation: The account is created by a site administrator from within the admin panel, and is active immediately on creation.


Version History
----------------
* 1.0.0     2016-07-03: Initial release.
* 1.0.1     2016-07-14: Bug fixes: Now works in all three scenarios.


Installation
----------------
This is a standard Joomla plugin. Installation is via Joomla's extension manager.


Usage
----------------
The plugin has several parameters:

* User note category - This parameter allows you to specify which user notes category the generated notes should be created in. Leave blank for the default 'Uncategorised'.

* Admin Activation Note - This is the text that will appear in the user note if the account is activated by a site administrator.

* Self Activation Note - This is the text that will appear in the user note if the account is activated by the user via the activation email.

* Immediate Activation Note - This is the text that will appear in the user note if the account is active immediately from when it is created by an admin user.

* Subject Text - this is the text that will be used for the for the subject text on the user notes.


Limitations
----------------
The plugin is obviously only going to be useful if your Joomla site requires user accounts to be activated. If you set the site to not require account activation, the plugin will still function but will produce erroneous user notes.


Motivation
----------------
This plugin was written from scratch after attempting to resolve a user query over account activation. The account had been activated but the user claimed not to have done so himself and none of the site admins had done it either. We were unable to answer the question. Having the functionality in this plugin would have helped to resolve this.


License
----------------
As with all Joomla extensions, this plugin is licensed under the GPLv2. The full license document should have been included with the source code.
