<?PHP // $Id: display.php,v 2.0 2012/02/01 03:30:26 moodler Exp $
      // Displays course_program settings

	require('../../config.php');
    require_once("$CFG->dirroot/course/lib.php");
    require_once("displaylib.php");
	include_once("$CFG->dirroot/lib/datalib.php");

	$action = required_param('action', PARAM_ACTION);
	$id = required_param('courseid', PARAM_INTEGER);
  
    if (! $course = $DB->get_record("course", array("id"=> $id))) {
        print_error("Course ID was incorrect");
    }
	
    require_login( $course->id);
	$PAGE->set_url('/course_program/display.php', array('courseid'=>$id, 'action'=>$action));   

	$context = get_context_instance(CONTEXT_COURSE, $course->id);
    if (!has_capability('moodle/course:manageactivities',$context)){ 
        print_error("Only teachers can use this page!");
    }

	if (!$sections = $DB->get_records("course_sections", array("course"=>"$course->id"), "section", 
				   "section, id, course, summary, sequence, visible, available, deadline")) {
		print_error("Could not find sections!");
	}
	if (!$mods = get_course_mods($course->id)) {
		print_error("Could not find mods!");
	}

	if ($action == "view") {			
    	$OUTPUT->header("$course->fullname", "$course->fullname", "<a href=$CFG->wwwroot/course/view.php?courseid=$course->id>".
				 "$course->shortname</a> -> ".get_string('pluginname', "block_course_program"),
                 "", "", true);
		$OUTPUT->box(get_string('clicktoprogram', "block_course_program"), "center");
		
		echo "<form name=\"formname\" method=\"post\" action=\"display.php?courseid=$course->id&action=process\">\n";
		echo "<input type=\"hidden\" name=\"dontdisplaymodsin\" value=\"-1\"\">";
		echo "<input type=\"hidden\" name=\"displaymodsin\" value=\"-1\">";
		echo "<br><center><input type=\"submit\" value=\"".get_string('commitchanges', "block_course_program")."\"></center><br>";
		echo "<table cellpadding=\"10\" align=\"center\">";
		
		echo "<tr align=\"center\"><td></td><td>".
			 "<td>".get_string('day', "block_course_program")."</td>".
			 "<td>".get_string('month', "block_course_program")."</td>".
			 "<td>".get_string('year', "block_course_program")."</td>".
			 "<td>".get_string('hour', "block_course_program")."</td>".
			 "<td>".get_string('min', "block_course_program")."</td>".
			 "</tr>";

		$count = 0;
		$modcount = 0;
    	$modinfo = unserialize($course->modinfo);
		$firstsec = current($sections);
		foreach ($sections as $section) {
			if ($section == $firstsec) {
				continue;
			}
			if ($count%2 == 0) {
				echo "<tr style=\"background-color:#CFCFCF;\"><td>";
			} else {
				echo "<tr style=\"background-color:#999999;\"><td>";
			}
			if (isset($SESSION->moddisplay[$section->section])) {
				echo "<a href=\"javascript:document.formname.dontdisplaymodsin.value=$section->section; document.formname.submit();\">";	
			} else {
				echo "<a href=\"javascript:document.formname.displaymodsin.value=$section->section; document.formname.submit();\">";	
			}
		
			$section->summary = display_summary_cat($section->summary);
			
			echo $count+1;
			echo " ".$section->summary;
			echo "</a><input type=\"hidden\" name=\"secid[$count]\" value=\"$section->section\">".
			     "<input type=\"hidden\" name=\"id[$count]\" value=\"$section->id\"></td>";
			
			if (!display_print_options("sec", $section->available, $section->deadline, $count)) {
				print_error("Could not print options");
			}
			
			// display mods if necessary, or advance our array pointer.
			if (isset($SESSION->moddisplay[$section->section])) {
				$sectionmods = explode(",", $section->sequence);

				foreach ($sectionmods as $modnumber) {
					if (empty($mods[$modnumber])) {
						continue;
					}
					$mod = $mods[$modnumber];
	                $instancename = urldecode($modinfo[$modnumber]->name);
					
					if ($modcount%2 == 0) {
						echo "<tr style=\"background-color:#AAAAFF;\">";
					} else {
						echo "<tr style=\"background-color:#A2B5CD;\">";
					}

					echo "<td style=\"padding-left:50px;\">$instancename<input type=\"hidden\" name=\"modid[$modcount]\" value=\"$mod->id\"></td>";
			
					if (!display_print_options("mod", $mod->available, $mod->deadline, $modcount)) {
						print_error("Could not print mod options");
					}
										
					$modcount++;
				}
			}
			$count++;

			if ($count == $course->numsections) {
				break;
			}
		}
		echo "</table>";
		echo "<br><center><input type=\"submit\" value=\"".get_string('commitchanges', "block_course_program")."\"></center>";
		echo "</form>";

	} elseif ($action == "process") {
		$form = data_submitted();
		$count = 0;
		// process all the sections
		while (isset($form->secid[$count])) {
			unset($newdisplay);
			if (( $form->secavailableday[$count] != 0 && $form->secavailablemonth[$count] != 0 && $form->secavailableyear[$count] != 0) ||
				( $form->secdeadlineday[$count] != 0 && $form->secdeadlinemonth[$count] != 0 && $form->secdeadlineyear[$count] != 0   )) { 

				$newdisplay->id = $form->id[$count];
				
				if ( $form->secavailableday[$count] != 0 && $form->secavailablemonth[$count] != 0 && $form->secavailableyear[$count] != 0) {
					$newdisplay->available = make_timestamp($form->secavailableyear[$count], 
						$form->secavailablemonth[$count], $form->secavailableday[$count], $form->secavailablehour[$count], 
						$form->secavailableminute[$count]);
				} else {
					$newdisplay->available = 0;
				}
		
				if ( $form->secdeadlineday[$count] != 0 && $form->secdeadlinemonth[$count] != 0 && $form->secdeadlineyear[$count] != 0 ) {
					$newdisplay->deadline = make_timestamp($form->secdeadlineyear[$count], 
						$form->secdeadlinemonth[$count], $form->secdeadlineday[$count], $form->secdeadlinehour[$count], 
						$form->secdeadlineminute[$count]);
				} else {
					$newdisplay->deadline = 0;
				}
				
				if (!$DB->update_record("course_sections", $newdisplay)) {
					print_error("Could not update section dates");
				}
			} else {
				if ($sections[$form->secid[$count]]->available != 0 || $sections[$form->secid[$count]]->deadline != 0) {
					$newdisplay->id = $form->id[$count];
					$newdisplay->available = 0;
					$newdisplay->deadline = 0;
					if (!$DB->update_record("course_sections", $newdisplay)) {
						print_error("Could not update section dates");
					}
				}
			}
			
			$count++;
		
		}
		// now process all the mods (might not be any)
		$count = 0;
		// process all the mods
		while (isset($form->modid[$count])) {
			unset($newdisplay);
			if (( $form->modavailableday[$count] != 0 && $form->modavailablemonth[$count] != 0 && $form->modavailableyear[$count] != 0) ||
			    ( $form->moddeadlineday[$count] != 0 && $form->moddeadlinemonth[$count] != 0 && $form->moddeadlineyear[$count] != 0)) {
				
				$newdisplay->id = $form->modid[$count];
				
				if ( $form->modavailableday[$count] != 0 && $form->modavailablemonth[$count] != 0 && $form->modavailableyear[$count] != 0) {
					$newdisplay->available = make_timestamp($form->modavailableyear[$count], 
						$form->modavailablemonth[$count], $form->modavailableday[$count], $form->modavailablehour[$count], 
						$form->modavailableminute[$count]);
				} else {
					$newdisplay->available = 0;
				}

				if ( $form->moddeadlineday[$count] != 0 && $form->moddeadlinemonth[$count] != 0 && $form->moddeadlineyear[$count] != 0) {
					$newdisplay->deadline = make_timestamp($form->moddeadlineyear[$count], 
						$form->moddeadlinemonth[$count], $form->moddeadlineday[$count], $form->moddeadlinehour[$count], 
						$form->moddeadlineminute[$count]);
				} else {
					$newdisplay->deadline = 0;
				}

				if (!$DB->update_record("course_modules", $newdisplay)) {
					print_error("Could not update module dates");
				}
			} else {
				if ($mods[$form->modid[$count]]->available != 0 || $mods[$form->modid[$count]]->deadline != 0) {
					$newdisplay->id = $form->modid[$count];
					$newdisplay->available = 0;
					$newdisplay->deadline = 0;
					if (!$DB->update_record("course_modules", $newdisplay)) {
						print_error("Could not update module dates");
					}
				}
			}
			$count++;
		}	
	
	
		if ($_POST['displaymodsin'] != -1) {
			$SESSION->moddisplay[$_POST['displaymodsin']] = 1;
			$redirect = "display.php?courseid=$course->id&action=view";
			$redirecttext = get_string('expanding', "block_course_program");
		} elseif ($_POST['dontdisplaymodsin'] != -1) {
			unset($SESSION->moddisplay[$_POST['dontdisplaymodsin']]);
			$redirect = "display.php?courseid=$course->id&action=view";
			$redirecttext = get_string('closing', "block_course_program");
		} else {
			unset($SESSION->moddisplay);
			$redirect = "$CFG->wwwroot/course/view.php?id=$course->id";
			$redirecttext = get_string('changessaved', "block_course_program");
		}
		redirect($redirect, $redirecttext);
	} else {
		print_error("Invalid Action");
	}
?>