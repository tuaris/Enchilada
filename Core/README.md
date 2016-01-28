# Enchilada Core Objects

At the moment the only functional object is the EnchiladaEmail class.

## Example

Several features are supported, look through the code for full capabilities.  The example below sends out a message in both plain text and HTML.

```PHP
// Load the class library
include 'EnchiladaEmail.class.php';

// Create an new Email
$mailer = new EnchiladaEmail();

// Configure it
$mailer->setSender("me@somewhere.here");
$mailer->setSubject("Hello World");
$mailer->addRecpt("someone@somewhere.out", "Someone Out There");
$mailer->addReplyTo("anyone@outthere.no");

// Add content
$mailer->addContent("This is a test message to nobody.");
$mailer->addContent("<h1>In HTML</h1><p>This is a test message to <strong>nobody</strong>.</p>", 'text/html', 'UTF-8', '8bit', array('Content-Disposition: inline'));

// Send it
$mailer->send();

// Print the full email source code
echo $mailer->raw();
```
