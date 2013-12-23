# Magento Footer JS

One of the common frustrations for frontend Magento developers is that there is so much inline JavaScript it is impossible to move the scripts from the header to the footer to improve page load speeds.

This is an experimental plugin that observes for the `core_block_abstract_to_html_after` event and removes any javascript it finds and appends it to a block of our own in the footer.

Tested in Chrome Mac OS X and even with issues mentioned below, you can get all the way through the checkout.

An example of the HTML produced can be found in the attached file `product_page_example.html`.

# Known Issues

- The scripts are currently outputted after the closing `</html>` tag.  Trying to attach it to `before_body_end` was causing an issue whereby the obsever was running on the `inline.js` block even after adding exceptions. 

- Currently have to handle IE conditional JS separately to ensure we retain the conditionals once moved.  This should be moved into a function, or one regex to rule them all.


# Ideas

- Write out all inline javascript to a new file and include that.
- Cache this file.
