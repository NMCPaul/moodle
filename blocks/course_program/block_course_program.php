<?PHP 

class block_course_program extends block_list {

    function init() {
        $this->title = get_string('pluginname', "block_course_program");
        $this->version = 2012020100;
    }    
    function get_content() {
		global $USER, $CFG, $THEME;
        $this->content_type = BLOCK_TYPE_LIST;
        $this->course = $this->page->course;
        require_once($CFG->dirroot.'/mod/forum/lib.php');
		
        if($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';
        
		$context = get_context_instance(CONTEXT_COURSE, $this->course->id);
        if (has_capability('moodle/course:manageactivities',$context) && $this->course->format != "social") {  // only teachers who are editing can see this block
			if ($USER->editing) {
				$this->content->items[]='<a href="'.$CFG->wwwroot.'/blocks/course_program/display.php?courseid='.$this->course->id.'&action=view">Course Program...</a>';
				$this->content->items[]='';
			}
        } 

		return $this->content;
    }
}

?>
