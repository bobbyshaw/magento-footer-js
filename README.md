# Magento Footer JS

A common web development technique to improve page load times is to move JavaScript to the end of the page.  A common frustration for frontend Magento developers is the inability to do this due to the amount of inline JavaScript found in templates.

This extension observes for the `http_response_send_before` event, removes any javascript it finds and appends it to a block at the end of the page for the root block.

Tested against:

- CE 1.9
- EE 1.14 (inc FPC)
 
# Usage

Enable the extension in `System > Configuration > Advanced > Developer > JavaScript Settings > Move JavaScript to Footer`.

# License

Copyright (C) 2015 Tom Robertshaw

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>
