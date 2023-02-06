// This script adds functionality to a button so that it can open and close the side navigation bar
// (when the viewport is too small)

const sidenav_toggle = document.getElementsByClassName('btn--side-nav')[0];
const sidenav = document.getElementsByClassName('side-nav')[0];

// The 'auto' width of the side navigation bar is first calculated because when setting the width directly to 'auto',
// there is no animation.
sidenav.style.width = 'auto';
const auto_width = sidenav.clientWidth;
sidenav.style.width = '0';

let is_sidenav_open = false;

sidenav_toggle.onclick = function onClick() {
    if (is_sidenav_open == true) {
        sidenav.style.width = '0px';
        is_sidenav_open = false;
    } else {
        sidenav.style.width = auto_width + 'px';
        is_sidenav_open = true;
    }
};
