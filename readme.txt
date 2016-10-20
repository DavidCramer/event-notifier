=== Event Notifier ===
Contributors: Desertsnowman
Tags: event, hooks, notification, event notifications, slack notifications, slack
Requires at least: 4.6.0
Tested up to: 4.6.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send notifications when WordPress hooks are fired.

== Description ==
Event Notifier is a diagnostic tool, aimed at providing notifications when WordPress hooks / event occur. It was created out of the need to be notified when "fail" hooks are triggered, thereby informing us of the event.

Currently it supports Email and Slack notifications. Hopefully I'll add more as I get time.

Since certain hooks fire frequently, the amount of notifications can be significant. So, be sure to only setup events on the exact hook that is needed.

If you are a developer, implement your own hooks in your plugins so that you can fire off events for specific notifications.


== Installation ==
1. Activate the plugin
2. Go to Tools > Event Notifier
3. Click "Add Event"
4. Fill in the details for the event
5. Click "Add"
6. That's it.


== Frequently Asked Questions ==
none yet. feel free to ask.

== Changelog ==

= 1.0.1 - October 2016 =
* fixed a issue that made a duplicate plugin show in the plugins list.

= 1.0.0 - October 2016 =
* Initial Release
