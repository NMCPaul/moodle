Course Program block by mko_san, original by Mark Neilson (Humboldt State University)

For Moodle 2.x, tested with 2.1

This block makes it possible to show/hide course sections (topics or weeks) or activities within sections automatically, based on the date and time.
Includes english and polish localizations.

Installation:
- copy to block folder
- install the block normally via notifications
- make manual change to format.php files:

In file:
/course/format/topics/format.php

Find:
/// Print Section 0 with general activities

Add after:

// section hiding mod================================
if ($blockid = $DB->get_field("block", "id", array("name" => "course_program"))) {
	$blocks = $DB->get_records('block', array(), "name ASC");
	foreach ($blocks as $singleblock) {
		if ($singleblock->id == $blockid) {
			require_once($CFG->dirroot."/blocks/course_program/displaylib.php");
			$sections = display_sections_visibility($sections, $course->id);
			$mods = display_mods_visibility($mods, $course->id);			
			break;
		}
	}
}
//====================================================

In file:
/course/format/weeks/format.php

Find:
/// Print Section 0 with general activities

Add after:

// section hiding mod================================
if ($blockid = $DB->get_field("block", "id", array("name" => "course_program"))) {
	$blocks = $DB->get_records('block', array(), "name ASC");
	foreach ($blocks as $singleblock) {
		if ($singleblock->id == $blockid) {
			require_once($CFG->dirroot."/blocks/course_program/displaylib.php");
			$sections = display_sections_visibility($sections, $course->id);
			$mods = display_mods_visibility($mods, $course->id);			
			break;
		}
	}
}
//====================================================


Original mod forum topic by Michael Penney:
http://moodle.org/mod/forum/discuss.php?d=12339