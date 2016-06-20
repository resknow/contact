# Contact Form Plugin

### v1.2.0

- Added AJAX form event callbacks, you can now hook in to the contact form on the frontend with the following events: `contact.success`, `contact.fail` and `contact.error`. All 3 pass in the `response` object received from the server.

### v1.1.0

__Important:__ Boilerplate version `1.5.6` or higher is required for the plugin to work as it uses new features. _Check your version before updating!_

- Filters updated to use static class method
- New Triggers and Filters added, full guide will to be added to Repo, see comments in the code for now
- New `logo` option allows you to add a logo to the top of emails sent
- Use `add_script` and `add_stylesheet` instead of overriding the default arrays
- Minified versions of `CSS` & `JS` added for production sites, these will be used automatically if the site enrivonment is set to anything other than `dev`
- `legacy-mail.php` removed as no longer needed
- Form HTML functions updated to use `get()` instead of `global` variables
