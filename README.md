# event-notifier
Send notifications when WordPress hooks are fired.

### About ###
Event Notifier is a diagnostic tool, aimed at providing email notifications when WordPress hooks / event occur. It was created out of the need to be notified when "fail" hooks are triggered, thereby informing us of the event.

Since certain hooks fire frequently, the amount of notifications can be significant. So, be sure to only setup events on the exact hook that is needed.

**Pro Tip:**
If you are a developer, implement your own hooks in your plugins so that you can fire off events for specific notifications.
