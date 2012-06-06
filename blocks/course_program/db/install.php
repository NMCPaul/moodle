<?PHP  //$Id: mysql.php,v 1.1 2004/04/18 23:20:29 stronk7 Exp $
//
// This file keeps track of upgrades to Moodle's
// blocks system.
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// Versions are defined by backup_version.php
//
// This file is tailored to MySQL
/**
 *
 * @param int $oldversion
 * @param object $block
 */
function xmldb_block_course_program_install($oldversion=0) {

    global $CFG, $DB;
    
    $dbman = $DB->get_manager();
	// Define table course_sections to be created
    $table1 = new xmldb_table('course_modules');
	if ($dbman->table_exists($table1)) {
        // Adding fields to table course_sections
        $field1 = new xmldb_field('available');
        $field1->set_attributes(XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'groupmode');
		if (!$dbman->field_exists($table1, $field1))
			$dbman->add_field($table1, $field1, $continue=true, $feedback=true);
        $field2 = new xmldb_field('deadline');
        $field2->set_attributes(XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'available');
		if (!$dbman->field_exists($table1, $field2))
			$dbman->add_field($table1, $field2, $continue=true, $feedback=true);		
	}
	// Define table course_sections to be created
    $table1 = new xmldb_table('course_sections');
	if ($dbman->table_exists($table1)) {
        // Adding fields to table course_sections
        $field1 = new xmldb_field('available');
        $field1->set_attributes(XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'visible');
		if (!$dbman->field_exists($table1, $field1))
			$dbman->add_field($table1, $field1, $continue=true, $feedback=true);
        $field2 = new xmldb_field('deadline');
        $field2->set_attributes(XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'available');
		if (!$dbman->field_exists($table1, $field2))
			$dbman->add_field($table1, $field2, $continue=true, $feedback=true);			
	}
    // $dbman->execute_sql_arr(array(
    // "ALTER TABLE {course_modules} ADD available INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER groupmode;",
	// "ALTER TABLE {course_modules} ADD deadline INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER available;",
	// "ALTER TABLE {course_sections} ADD available INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER visible;",
	// "ALTER TABLE {course_sections} ADD deadline INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER available;",
    // ));
    $result = TRUE;
    return $result;
}
    

