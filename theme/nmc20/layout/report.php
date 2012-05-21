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

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
  <head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
  </head>
  <body id="<?php echo $PAGE->bodyid ?>" class="<?php echo $PAGE->bodyclasses.' '.join(' ', $bodyclasses) ?>">

    <div id="nmclogo">
      <a href="http://www.nmc.edu">
        <img alt="NMC" src="https://elearning.nmc.edu/theme/NMC_REDEUX/images/default/img-nmclogo.gif">
      </a>
    </div>
    <?php echo $OUTPUT->standard_top_of_body_html() ?>

    <div id="surround">
      <div id="page">
        <?php if ($hasheading || $hasnavbar) { ?>
          <div id="page-header" class="clearfix">
            <div id="logo">
              <a href="https://elearn.nmc.edu">NMC eLearning</a>
            </div>
            <div class="userinfo">
              <div class="profilename">
              <?PHP

              function get_content () {
              global $USER, $CFG, $SESSION, $COURSE;
              $wwwroot = '';
              $signup = '';}

              if (empty($CFG->loginhttps)) {
              $wwwroot = $CFG->wwwroot;
              } else {
              $wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
              }


              if (!isloggedin() or isguestuser()) {
              echo '<a href="'.$wwwroot.'/login/index.php">'.get_string('loggedinnot').'</a>';
              } else {
              if(session_is_loggedinas()) {
                $realuser = session_get_realuser();
                $fullname = fullname($realuser, true);
                $realuserinfo = "[<a href=\"$CFG->wwwroot/course/loginas.php?id=$USER->realuser&amp;sesskey=".sesskey()."\">$fullname</a>]";
                echo $realuserinfo.' logged in as [<a href="'.$wwwroot.'/user/view.php?id='.$USER->id.'&amp;course='.$COURSE->id.'">'.$USER->firstname.' '.$USER->lastname.'</a>]';
              } elseif(empty($USER->realuser)){
              echo '<a href="'.$wwwroot.'/user/view.php?id='.$USER->id.'&amp;course='.$COURSE->id.'">'.$USER->firstname.' '.$USER->lastname.'</a>';
              }else{
              echo preg_replace('/\(.*\)/', '', user_login_string($COURSE, $USER));
              }
              }
              ?>

              </div>
              <div class="profileoptions">
              <?PHP

              if (!isloggedin() or isguestuser()) {
              // do something some day...
              }else{
              echo '<ul>';
              echo '<li><a href="'.$wwwroot.'/user/edit.php?id='.$USER->id.'&amp;course='.$COURSE->id.'">'.get_string('updatemyprofile').'</a></li>';
              echo '<li><span style="color: #999; font-size: 10px; ">|</li>';
              echo '<li><a href="'.$wwwroot.'/login/logout.php?sesskey='.sesskey().'">'.get_string('logout').'</a></li>';
              echo '</ul>';

              }
              ?>
              </div>
              <div class="profilepic">
              <?PHP
              if(!isloggedin() or isguestuser()) {
                echo '<a href="'.$wwwroot.'/user/view.php?id='.$USER->id.'&amp;course='.$COURSE->id.'"><img src="'.$wwwroot.'/user/pix.php?file=/'.$USER->id.'/f1.jpg" width="80px" height="80px" title="Not logged in" alt="Not logged in" /></a>';
              } else {
                echo '<a href="'.$wwwroot.'/user/view.php?id='.$USER->id.'&amp;course='.$COURSE->id.'"><img src="'.$wwwroot.'/user/pix.php?file=/'.$USER->id.'/f1.jpg" width="80px" height="80px" title="'.$USER->firstname.' '.$USER->lastname.'" alt="'.$USER->firstname.' '.$USER->lastname.'" /></a>';
              }

              ?>
              </div>
            </div>
            <?php if ($hasheading) { ?>
            <?php } ?>
            <?php if ($hasnavbar) { ?>
              <div class="navbar clearfix">
                <div id="top_menu">
                  <div id="top_menu_date">
                    <a href="https://elearn.nmc.edu/calendar/view.php"><?php echo(date('j M Y (l)')); ?></a>
                  </div>
                  <ul>
                    <li class="home"><a href="<?php echo($CFG->wwwroot); ?>">Home</a></li>
                    <?php if(isloggedin()) { ?>
                      <li><a href="<?php echo($CFG->wwwroot."/my/"); ?>">My Moodle</a></li>
                    <?php } ?>
                    <li><a href="#">Tech Help</a></li>
                    <li><a href="#">New To Moodle?</a></li>
                    <li><a href="http://google.nmc.edu" target="_blank">NMC Student Email</a></li>
                    <li><a href="#">Copyright Info</a></li>
                    <li><a href="#">Feedback</a></li>
                  </ul>
                </div>
                <?php if(@!$PAGE->layout_options['nobreadcrumb']): ?>
                  <div class="breadcrumb"><?php echo($OUTPUT->nmc20_navbar()); ?></div>
                <?php endif; ?>
                <div class="navbutton"><?php echo $PAGE->button; ?></div>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
        <!-- END OF HEADER -->
        <div id="page-content">
          <div id="region-main-box">
            <div id="region-post-box">
              <div id="region-main-wrap">
                <div id="region-main">
                  <div class="region-content">
                    <?php //echo core_renderer::MAIN_CONTENT_TOKEN ?>
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

        <!-- START OF FOOTER -->
        <?php if ($hasfooter) { ?>
          <div id="page-footer" class="clearfix">
            <p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
            <?php
              echo $OUTPUT->login_info();
              #echo $OUTPUT->home_link();
              echo $OUTPUT->standard_footer_html();
            ?>
            <div class="rounded-corner bottom-left"></div>
            <div class="rounded-corner bottom-right"></div>
          </div>
        <?php } ?>
      </div>
    </div>
    <?php echo $OUTPUT->standard_end_of_body_html(); ?>
    <?php
      include_once $CFG->dirroot . '/local/globalmessage/lib/base.php';
      moo_globalmessage::show_message();
    ?>
  </body>
</html>
