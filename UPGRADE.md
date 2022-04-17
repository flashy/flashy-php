# Upgrade Guide

_After all `MINOR` and `MAJOR` version bumps will have upgrade notes posted here._

[2022-03-09] New Repo
---------------------------
### Removed: Create Or Update
We combined the create and update to a single function, you can set if to overwrite the contact information (update) with a parameter.

The following code will create / update contact with a primary key of "email" and will allow tracking (cookie creation).

The last parameter determine if to overwrite the contact.
``` php
$flashy->contacts->create($contact, "email", true, true);
```

### Relocated: List Subscribe
Before you was able to create subscription to a list with the following code:
``` php
$flashy->lists->subscribe($list_id, $contact_info);
```

Since the subscriptions belong to a contact, we decided to take it to the contacts class, you can now create more than one subscription to a contact at once.
``` php
// If you want few lists, the key is the List ID and the value is the status, true for subscribed, false for unsubscribed (boolean)
$lists = [
    156 => true,
    177 => true,
];

// If you need a single list you can also just pass the List ID, and the subscription will be on true
$lists = 156;

$flashy->contacts->subscribe($contact, $lists);

// If you want to subscribe a PHONE contact you can use pass another parameter for the primary key "phone", email is the default.
$flashy->contacts->subscribe($contact, $lists, "phone");
```

### Removed: Unsubscribe From All
If you want to block a contact from all mailing lists, use the new endpoint for Block.

``` php
// Block a contact with email as primary key
$flashy->contacts->block("contact@domain.com");

// If the contact primary key is phone or other field, you can pass the identifier
$flashy->contacts->block("972526845444", "phone");
```


### Relocated: Tracking Events
We have unified our API also for the events tracking, you can use the following code to track events:

``` php
$flashy->events->track("Purchase", [
    "email" => "contact@domain.com",
    "content_ids" => ["456", "789", "999"],
    "value" => 688,
    "currency" => "USD",
    "context" => []
]);
```


### Relocated: Send Email / Send SMS
We have unified our communication channels into one class, so you can easily send Email / SMS.

``` php
// Send Email
$flashy->messages->email($message);

// Send SMS Message
$flashy->messages->sms($message);
```

You can read here about the full $message payload:
https://flashy.app/docs/rest-api/#/reference/0/messages
