<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
$showsidepre = $hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT);
$showsidepost = $hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT);


$bodyclasses = array();
if ($showsidepre && !$showsidepost) {
    $bodyclasses[] = 'side-pre-only';
} else if ($showsidepost && !$showsidepre) {
    $bodyclasses[] = 'side-post-only';
} else if (!$showsidepost && !$showsidepre) {
    $bodyclasses[] = 'content-only';
}
    require_once('header.php');
    ?>
        <div id="page-content">
          <div id="region-main-box">
            <div id="region-post-box">
              <div id="region-main-wrap">
                <div id="region-main">
                  <div class="region-content">
                    <h2 class="headingblock">
                        <?php echo $PAGE->heading;?>
                    <?php
                      if( is_role_switched($COURSE->id) ) {
                        $rolename = '';
                        $context = get_context_instance(CONTEXT_COURSE, $COURSE->id);
                        $role = $DB->get_record('role', array('id'=>$USER->access['rsw'][$context->path]));
                        $rolename = format_string($role->name);
                        $loggedinas = get_string('loggedinas', 'moodle', $username).$rolename." (<a href=\"$CFG->wwwroot/course/view.php?id=$course->id&amp;switchrole=0&amp;sesskey=".sesskey()."\">".get_string('switchrolereturn').'</a>)';
                        echo('<div id="rolechanged">ROLE: '.$rolename.'</div>');
                        //echo('<div id="rolechanged">ROLE: '.$loggedinas.'</div>');
                    }
                    ?>
                    </h2>
                    <?php echo $OUTPUT->main_content() ?>
                  </div>
                </div>
              </div>

              <?php if ($hassidepre) { ?>
                <div id="region-pre" class="block-region">
                  <div class="region-content">
                    <?php echo $OUTPUT->blocks_for_region('side-pre') ?>
                  </div>
                </div>
              <?php } ?>

              <?php if ($hassidepost) { ?>
                <div id="region-post" class="block-region">
                  <div class="region-content">
                    <?php echo $OUTPUT->blocks_for_region('side-post') ?>
                  </div>
                </div>
              <?php } ?>

            </div>
          </div>
        </div>
    <?php
        require_once('footer.php');
    ?>
  </body>
</html>
