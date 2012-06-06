<?php
require_once("$CFG->dirroot/config.php");
include_once("$CFG->dirroot/lib/datalib.php");
require_once("$CFG->dirroot/course/lib.php");

function display_print_options($prefix, $available, $deadline, $count) {
		if ($available == 0) {
			$available = NULL;
		}
		if ($deadline == 0) {
			$deadline = NULL;
		}
		
		echo "<td align=\"right\">".get_string("available", "lesson").":</td>";
		display_print_date_selector($prefix."availableday[$count]", $prefix."availablemonth[$count]", $prefix."availableyear[$count]", $available);
		display_print_time_selector($prefix."availablehour[$count]", $prefix."availableminute[$count]", $available);
		echo "</tr>";
		if ($prefix == "sec") {
			if ($count%2 == 0) {
				echo "<tr style=\"background-color:#CFCFCF;\">";
			} else {
				echo "<tr style=\"background-color:#999999;\">";
			}
		} else {
			if ($count%2 == 0) {
				echo "<tr style=\"background-color:#AAAAFF;\">";
			} else {
				echo "<tr style=\"background-color:#A2B5CD;\">";
			}
		}
		echo "<td></td><td align=\"right\">".get_string("deadline", "lesson").":</td>";
		display_print_date_selector($prefix."deadlineday[$count]", $prefix."deadlinemonth[$count]", $prefix."deadlineyear[$count]", $deadline);
		display_print_time_selector($prefix."deadlinehour[$count]", $prefix."deadlineminute[$count]", $deadline);
		echo "</tr>";
		
		return true;
}

function display_print_date_selector($day, $month, $year, $currenttime=0) {
// Currenttime is a default timestamp in GMT
// Prints form items with the names $day, $month and $year
    
	if (!$currenttime) {
        $currentdate['mday'] = 0;
		$currentdate['mon'] = 0;
		$currentdate['year'] = 0;
    } else {
		$currentdate = usergetdate($currenttime);
	}
    
	$days[0] = "N/A";
	for ($i=1; $i<=31; $i++) {
        $days[$i] = "$i";
    }
	
	$months[0] = "N/A";
    for ($i=1; $i<=12; $i++) {
        $months[$i] = userdate(gmmktime(12,0,0,$i,1,2000), "%B");
    }
    
	$years[0] = "N/A";
	for ($i=date("Y"); $i<=date("Y")+5; $i++) {
        $years[$i] = $i;
    }
    echo "<td>";
	echo html_writer::select($days,   $day,   $currentdate['mday'], "");
	echo "</td><td>";
    echo html_writer::select($months, $month, $currentdate['mon'],  "");
	echo "</td><td>";
    echo html_writer::select($years,  $year,  $currentdate['year'], "");
	echo "</td>";
}

function display_print_time_selector($hour, $minute, $currenttime=0, $step=5) {
// Currenttime is a default timestamp in GMT
// Prints form items with the names $hour and $minute

    if (!$currenttime) {
		$currentdate['hours'] = 0;
		$currentdate['minutes'] = 0;
    } else {
	    $currentdate = usergetdate($currenttime);
	}
    if ($step != 1) {
        $currentdate['minutes'] = ceil($currentdate['minutes']/$step)*$step;
    }
    for ($i=0; $i<=23; $i++) {
        $hours[$i] = sprintf("%02d",$i);
    }
    for ($i=0; $i<=59; $i+=$step) {
        $minutes[$i] = sprintf("%02d",$i);
    }
	echo "<td>";
    echo html_writer::select($hours,   $hour,   $currentdate['hours'],   "");
	echo "</td><td>";
	echo html_writer::select($minutes, $minute, $currentdate['minutes'], "");
	echo "</td>";
}

function display_sections_visibility($passedsections, $courseid) {
	global $DB;
	if (!$sections = $DB->get_records("course_sections", array("course"=>$courseid), "section", 
				   "section, id, course, summary, sequence, visible, available, deadline")) {
		error("Could not find sections!");
	}

	$timenow = time();
	$firstsec = current($sections);
	foreach ($sections as $section) {
		if ($section == $firstsec) { // dont process first section
			continue;
		}
		
		if ($section->available < $timenow && ($section->deadline > $timenow || $section->deadline == 0) && !$section->visible) {
			set_section_visible($courseid, $section->section, 1);
			$passedsections[$section->section]->visible = 1;
		}
		if ($section->available > $timenow || ($section->deadline < $timenow && $section->deadline != 0) && $section->visible) {
			set_section_visible($courseid, $section->section, 0);
			$passedsections[$section->section]->visible = 0;
		}
	}
	return $passedsections;
}
function display_mods_visibility($passedmods, $courseid) {
	if (!$mods = get_course_mods($courseid)) {
		error("Could not find mods!");
	}

	$timenow = time();
	foreach ($mods as $mod) {
		
		if ($mod->available < $timenow && ($mod->deadline > $timenow || $mod->deadline == 0) && !$mod->visible) {
			set_coursemodule_visible($mod->id, true);
			$passedmods[$mod->id]->visible = 1;
		}
		if ($mod->available > $timenow || ($mod->deadline < $timenow && $mod->deadline != 0) && $mod->visible) {
			set_coursemodule_visible($mod->id, false);
			$passedmods[$mod->id]->visible = 0;
		}
				
	}

	return $passedmods;
}

function display_summary_cat($summary) {
	$MAX_LENGTH = 46;
	
	// if you want to split on more tags just add them here. maybe there is a cleaner way to ignore case
	$split_tags = array('<br>','<BR>','<Br>','<bR>','</dt>','</dT>','</Dt>','</DT>','</p>','</P>', '<BR />', '<br />', '<bR />', '<Br />');

	foreach($split_tags as $tag) {
		if (strstr($summary, $tag)) {
			list($summary, $junk) = explode($tag, $summary, 2);
		}
	}
	$summarystrip = strip_tags($summary);
	
	if (strlen($summarystrip) > $MAX_LENGTH) {
		$summarystrip = wordwrap($summarystrip, $MAX_LENGTH, "\n", 1);
		$temp = explode("\n", $summarystrip);
		$summarystrip = $temp[0];
	}
	
	return $summarystrip;
}

?>