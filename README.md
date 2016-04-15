# Magento Footer JS

A common web development technique to improve page load times is to move JavaScript to the end of the page.  A common frustration for frontend Magento developers is the inability to do this due to the amount of inline JavaScript found in templates.

This extension observes for the `core_block_abstract_to_html_after` event, removes any javascript it finds and appends it to the end of the body.

Tested against:

- CE 1.9
- EE 1.14 (inc FPC)

# Usage

Enable the extension in `System > Configuration > Advanced > Developer > JavaScript Settings > Move JavaScript to Footer`.

Add name of blocks with JS which must not be moved to footer in `System > Configuration > Advanced > Developer > JavaScript Settings > FooterJS Excluded Blocks`.
Enter block names separated with a comma. Please note, JS libraries are moved to footer so be careful excluding JS which has dependencies.

Also there is another way to exclude some JS from moving to the footer - add attribute `data-footer-js-skip="true"` to `script` tag.
Currently there is no way to exclude JS added using addJs/addItem in layout.

# License

Copyright (C) 2015 Tom Robertshaw

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>
