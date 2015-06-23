# Inliner Component

## Modules

- **Inliner**: Write your email templates using blade and
do not worry about whether or not they will display correctly. The
inliner service is capable of inlining a specified CSS file into a view.

## Module-specific instructions:

### Inliner

Here is an example of the inliner service in a controller method. In
this example with have set the `inliner.paths.stylesheets` configuration
key to point to a directory where there is a `ink.css` file.

```php
public function getShowEmail(StyleInliner $inliner, Mailer $mailer)
    {
        $inliner->inlineAndSend($mailer, view('mail.signup.verification'), 'ink', function (Message $message) {
                $message->to('ed+contact@chromabits.com', 'Ed')->subject('Welcome!');

                $message->from('no-reply@myapp.com', 'MyApp Account');
            }
        );
        
        // Other stuff
    }
```

For more questions about the configuration file, take a look at `config/inliner.php`
for an example.
