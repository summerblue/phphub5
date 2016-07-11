# jquery.customscroll

Facebook like custom scrollbars (OSX inspired).

## Demo

Check out http://marcojetson.github.com/jquery-customscroll/

## Installation

Include script after jQuery

    <script src="jquery.customscroll.js"></script>

Include stylesheet

    <link rel="stylesheet" href="customscroll.css">

## License

Dual licensed under the MIT or GPL Version 2 licenses.
- http://www.opensource.org/licenses/mit-license.php
- http://www.gnu.org/licenses/gpl-2.0.html

## Usage

    $(selector).customscroll(options)

Selected element must have height defined in CSS rules (and overflow-y: hidden to avoid screen flashes)

## Options

### show

#### on
Event when track is shown, default 'mouseenter scrollstart'

#### effect
Effect used to show track, default 'fadeIn'

#### speed
Effect speed to show track, default 250

#### delay
Show delay, default 0

### hide

#### on
Event when track is hidden, default 'mouseleave scrollstop'

#### effect
Effect used to hide track, default 'fadeOut'

#### speed
Effect speed to hide track, default 250

#### delay
Hide delay, default 750

### grow
Change size on hover, use null for disable

#### size
Size to grow in pixels, default 3

#### speed
Grow animation speed, default 100

### pageUpnDown

#### speed
Speed animation when clicking on the track