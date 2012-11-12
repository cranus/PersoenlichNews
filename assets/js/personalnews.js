/*
 * neoPersonalNews.php - Plugin for Stud.IP
 * Copyright (c) 2012  Johannes Stichler / johannes.stichler@hfwu.de
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 */
function hiddennews(newsid) {
    alert(newsid);
}

$(document).ready(function() {
    $('.pnhiddenews').button();
});
