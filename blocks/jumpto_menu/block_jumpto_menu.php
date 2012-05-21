<?php

/**
 * block class for jump to navigation menu
 *
 * @package   block_jumpto_menu
 * @copyright 2010 Tim Williams (tmw@autotrain.org)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
global $CFG;
require_once($CFG->dirroot."/blocks/jumpto_menu/lib.php");

class block_jumpto_menu extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_jumpto_menu');
    }

    function has_config() {
        return true;
    }

    function applicable_formats() {
        return array('all' => true);
    }

    function get_content () {
        global $CFG, $COURSE, $PAGE;
        $this->content = new stdClass;
        $this->content->footer = '';

        if ($this->page->cm)
            $main=block_jumpto_menu_navmenu($COURSE, true , $this->page->cm, "window", $CFG->block_jumpto_menu_jsmove, $this->instance->id);
        else
            $main=block_jumpto_menu_navmenu($COURSE, false, NULL           , "window", $CFG->block_jumpto_menu_jsmove, $this->instance->id);

        $this->content->text=$main;
//$CFG->custommenuitems=$main;
        return $this->content;
    }

    function hide_header() {
        global $PAGE;
        return !$PAGE->user_is_editing();
    }

    function html_attributes() {
        global $PAGE, $CFG;
        $b="";
        if ($PAGE->user_is_editing() || !$CFG->block_jumpto_menu_hide_borders)
         $b='  block';

        $attributes = array(
            'id' => 'inst' . $this->instance->id,
            'class' => 'block_' . $this->name().$b
        );
        if ($this->instance_can_be_docked() && get_user_preferences('docked_block_instance_'.$this->instance->id, 0)) {
            $attributes['class'] .= ' dock_on_load';
        }
        return $attributes;
    }

}


?>