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
<html <?php echo $OUTPUT->htmlattributes() ?> class="yui3-loading">
  <head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
  </head>
  <body id="<?php echo $PAGE->bodyid ?>" class="<?php echo $PAGE->bodyclasses.' '.join(' ', $bodyclasses) ?>">
    <div id="nmclogo">
      <a id="main_site" href="http://www.nmc.edu">
        <img alt="NMC" src="<?php echo $OUTPUT->pix_url('img-nmclogo', 'theme')?>" />
      </a>
      <div id="top_menu_date">
        <a href="https://elearn.nmc.edu/calendar/view.php"><?php /*echo(date('j M Y (l)'));*/ echo(date('F jS, o (D)')); ?></a>
      </div> 
    </div>
    <?php echo $OUTPUT->standard_top_of_body_html() ?>

    <div id="surround">
      <div id="page">
        <?php if ($hasheading || $hasnavbar) { ?>
          <div id="page-header" class="clearfix">
            <div id="fading_header">
              <div id="logo">
                <a href="https://elearn.nmc.edu">NMC eLearning</a>
              </div>
              <div class="userinfo">
                <div class="profilename">
                <?PHP
                global $USER, $CFG, $SESSION, $COURSE, $DB, $CATEGORY;

                function get_content () {
                $wwwroot = '';
                $signup = '';}

                if (empty($CFG->loginhttps)) {
                $wwwroot = $CFG->wwwroot;
                } else {
                $wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
                }


                if (!isloggedin() or isguestuser()) {
                echo '<a href="'.$wwwroot.'/login/index.php">'.nmc20_ucwords(get_string('click_to_login', 'theme_nmc20')).'</a>';
                } else {
                if(session_is_loggedinas()) {
                  $realuser = session_get_realuser();
                  $fullname = fullname($realuser, true);
                  $realuserinfo = "[<a class=\"adminonly\" href=\"$CFG->wwwroot/course/loginas.php?id=$USER->realuser&amp;sesskey=".sesskey()."\">$fullname</a>]";
                  echo $realuserinfo.' logged in as [<a href="'.$wwwroot.'/user/view.php?id='.$USER->id.'&amp;course='.$COURSE->id.'">'.$USER->firstname.' '.$USER->lastname.'</a>]';
                } elseif(is_siteadmin()) {
                  echo '<a title="You are logged in as an admin user" class="adminonly" href="'.$wwwroot.'/user/view.php?id='.$USER->id.'&amp;course='.$COURSE->id.'">'.$USER->firstname.' '.$USER->lastname.'</a>';
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
              </div>
              <div class="navbar clearfix">
              <?php #if ($hasnavbar) { ?>
                <div class="yui3-g" id="bd">
                    <div class="yui3-u" id="main">
                        <div id="top_menu" class="yui3-menu yui3-menu-horizontal yui3-menubuttonnav">
                            <div class="yui3-menu-content">
                                <ul class="first-of-type level-1">
                                    <li class="yui3-menuitem first-element level-1"><a class="yui3-menuitem-content" href="<?php echo($CFG->wwwroot); ?>/">Home</a></li>
                                    <li class="yui3-menuitem first-element level-1"><a class="yui3-menuitem-content" href="<?php echo($CFG->wwwroot.'/my'); ?>/">My Moodle</a></li>
                                    <li class="level-1">
                                        <a class="yui3-menu-label" href="#quicklinks"><em>Quick Links</em></a>
                                        <div id="quicklinks" class="yui3-menu">
                                            <div class="yui3-menu-content">
                                                <ul class="level-2">
                                                    <li class="yui3-menuitem level-2 first-element"><a class="yui3-menuitem-content" href="https://mail.nmc.edu/" target="_blank" title="Log into your NMC student email">Student Email</a></li>
                                                    <li class="yui3-menuitem level-2"><a class="yui3-menuitem-content" href="http://www.nmc.edu/selfservice" target="_blank" title="Register, pay tuition, check grades, etc.">NMC Self-Service</a></li>
                                                    <li class="yui3-menuitem level-2"><a class="yui3-menuitem-content" href="http://netstorage.nmc.edu/" target="_blank" title="Access your Q &amp; S drives from off-campus">Q &amp; S Drives</a></li>
                                                    <li class="yui3-menuitem level-2"><a class="yui3-menuitem-content" href="https://www.nmc.edu/programs/online-programs/index.html" target="_blank" title="Information about NMC's hybrid course offerings">NMC Online &amp; Hybrid Website</a></li>
                                                    <li class="yui3-menuitem level-2 last-element"><a class="yui3-menuitem-content" href="https://www.nmc.edu/login.html" target="_blank" title="Various other NMC services">Other Logins</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="level-1">
                                        <a class="yui3-menu-label" href="#gettingstarted"><em>Getting Started</em></a>
                                        <div id="gettingstarted" class="yui3-menu">
                                            <div class="yui3-menu-content">
                                                <ul class="level-2">
                                                    <li class="yui3-menuitem level-2 first-element"><a class="yui3-menuitem-content" href="https://elearn.nmc.edu/course/view.php?id=25980" target="_blank" title="Orientation for NMC's eLearning solutions">eLearning Orientation</a></li>
                                                    <li class="yui3-menuitem level-2"><a class="yui3-menuitem-content" href="https://elearn.nmc.edu/mod/page/view.php?id=512718&inpopup=1" target="_blank" title="Which technologies are required for NMC">Technology Requirements</a></li>
                                                    <li class="yui3-menuitem level-2"><a class="yui3-menuitem-content" href="https://elearn.nmc.edu/mod/page/view.php?id=512721&inpopup=1" target="_blank" title="We'll show you how to find your courses">Where's My Course?</a></li>
                                                    <li class="yui3-menuitem level-2 last-element"><a class="yui3-menuitem-content" href="https://www.nmc.edu/programs/online-programs/AreYouReadyChecklist.pdf" target="_blank" title="Online learning is different than classroom learning - we'll explain how">Are You Ready For Online?</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="level-1">
                                        <a class="yui3-menu-label" href="#studentservices"><em>Student Services</em></a>
                                        <div id="studentservices" class="yui3-menu">
                                            <div class="yui3-menu-content">
                                                <ul class="level-2">
                                                    <li class="level-2 sub-heading">
                                                        <a class="yui3-menu-label" href="#library" title="NMC's Library">Library</a>
                                                        <div id="library" class="yui3-menu">
                                                            <div class="yui3-menu-content">
                                                                <ul class="level-3">
                                                                    <li class="yui3-menuitem level-3 first-element">
                                                                        <a class="yui3-menuitem-content" href="https://www.nmc.edu/resources/library/index.html" target="_blank" title="Homepage for NMC's Library">Library Homepage</a>
                                                                    </li>
                                                                    <li class="yui3-menuitem level-3">
                                                                        <a class="yui3-menuitem-content" href="https://www.nmc.edu/resources/library/help/ask-a-librarian.html" target="_blank" title="Got a library-related question?  Chances are, our librarians have the answer.">Ask A Librarian</a>
                                                                    </li>
                                                                    <li class="yui3-menuitem level-3 last-element">
                                                                        <a class="yui3-menuitem-content" href="https://www.nmc.edu/resources/library/help/research-tips/copyright/index.html" target="_blank" title="Copyright information">Copyright</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="level-2 sub-heading">
                                                        <a class="yui3-menu-label" href="#writingcenter" title="NMC's Writing and Reading Center">Writing &amp; Reading Center</a>
                                                        <div id="writingcenter" class="yui3-menu">
                                                            <div class="yui3-menu-content">
                                                                <ul class="level-3">
                                                                    <li class="yui3-menuitem level-3 first-element">
                                                                        <a class="yui3-menuitem-content" href="http://bcs.bedfordstmartins.com/easywriter3e/default.asp?uid=0&rau=0" target="_blank" title="EasyWriter">EasyWriter</a>
                                                                    </li>
                                                                    <li class="yui3-menuitem level-3 last-element">
                                                                        <a class="yui3-menuitem-content" href="https://www.nmc.edu/student-services/writing-center/" target="_blank" title="NMC's Writing and Reading Center Homepage">Writing &amp; Reading Center Homepage</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="yui3-menuitem level-2"><a class="yui3-menuitem-content" href="https://www.nmc.edu/departments/help-desk/computer-labs.html" target="_blank" title="Don't have a computer?  Use ours!">Computer Labs</a></li>
                                                    <li class="yui3-menuitem level-2"><a class="yui3-menuitem-content" href="https://www.nmc.edu/student-services/tutoring-support/" target="_blank" title="NMC offers tutoring services">Tutoring &amp; Support Services</a></li>
                                                    <li class="yui3-menuitem level-2"><a class="yui3-menuitem-content" href="https://www.nmc.edu/student-services/tutoring-support/center-for-learning.html" target="_blank" title="Test Proctoring">Test Proctoring</a></li>
                                                    <li class="yui3-menuitem level-2"><a class="yui3-menuitem-content" href="https://www.nmc.edu/student-services/advising-center/" target="_blank" title="NMC's Advising Center">Advising</a></li>
                                                    <li class="yui3-menuitem level-2 last-element"><a class="yui3-menuitem-content" href="https://www.nmc.edu/student-services/a-z-list.html" target="_blank" title="All NMC's services, listed in alphabetical order">A-Z List</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="level-1">
                                        <a class="yui3-menu-label" href="#studentservices"><em>Need Help?</em></a>
                                        <div id="studentservices" class="yui3-menu">
                                            <div class="yui3-menu-content">
                                                <ul class="level-2">
                                                    <li class="yui3-menuitem level-2 first-element"><a class="yui3-menuitem-content" href="https://www.nmc.edu/departments/help-desk/index.html" target="_blank" title="NMC's Tech Help">Tech Help</a></li>
                                                    <li class="level-2 sub-heading">
                                                        <a class="yui3-menu-label" href="#studentresources" title="Never used Moodle before?  Let us help.">Student Resources</a>
                                                        <div id="studentresources" class="yui3-menu">
                                                            <div class="yui3-menu-content">
                                                                <ul class="level-3">
                                                                    <li class="yui3-menuitem level-3 first-element">
                                                                        <a class="yui3-menuitem-content" href="http://docs.moodle.org/22/en/Student_FAQ" target="_blank" title="Commonly asked questions about Moodle by students">Moodle FAQs</a>
                                                                    </li>
                                                                    <li class="yui3-menuitem level-3 last-element"><a class="yui3-menuitem-content" href="https://elearn.nmc.edu/course/view.php?id=25980" target="_blank" title="Walkthroughs and How-Tos for various student-related Moodle tasks">Tutorials</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="level-2 sub-heading">
                                                        <a class="yui3-menu-label" href="#instructorresources" title="Never used Moodle before?  Let us help.">Instructor Resources</a>
                                                        <div id="instructorresources" class="yui3-menu">
                                                            <div class="yui3-menu-content">
                                                                <ul class="level-3">
                                                                    <li class="yui3-menuitem level-3 first-element">
                                                                        <a class="yui3-menuitem-content" href="http://docs.moodle.org/22/en/About_Moodle_FAQ" target="_blank" title="Commonly asked questions about Moodle by instructors">Moodle FAQs</a>
                                                                    </li>
                                                                    <li class="yui3-menuitem level-3 last-element"><a class="yui3-menuitem-content" href="https://elearn.nmc.edu/course/view.php?id=25964" target="_blank" title="Walkthroughs and How-Tos for various instructor-related Moodle tasks">Instructor Tutorials</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="yui3-menuitem level-2 last-element"><a class="yui3-menuitem-content" href="https://www.nmc.edu/resources/library/help/research-tips/copyright/index.html" target="_blank" title="While we're all about sharing here at NMC, there are still some rules...">Copyright Questions?</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
              <?php } ?>
                  <?php if(@!$PAGE->layout_options['nobreadcrumb']): ?>
                    <div class="breadcrumb"><?php echo($OUTPUT->nmc20_navbar()); ?></div>
                  <?php endif; ?>
                  <?php if ($PAGE->button){ ?><div class="navbutton"><?php echo $PAGE->button; ?></div> <?php } ?>
            </div>
          </div>

        <?php #} ?>
        <!-- END OF HEADER -->
        <div id="page-content">
          <div id="region-main-box">
            <div id="region-post-box">
              <div id="region-main-wrap">
                <div id="region-main">
                  <div class="region-content">
                    <h2 class="headingblock"><?php echo $PAGE->heading;?></h2>
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
              #echo $OUTPUT->login_info();
              #echo $OUTPUT->home_link();
                //echo('<script type="text/javascript" src="'.$CFG->wwwroot.'/blocks/jumpto_menu/dropdown.js"></script>');
                //require_once($CFG->dirroot."/blocks/jumpto_menu/lib.php");
                //echo block_jumpto_menu_html();
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
    <script type="text/javascript">
        var element = document.getElementById('top_menu');
        if (typeof(element) != 'undefined' && element != null)
        {
            YUI().use('node-menunav',function(Y){var menu=Y.one("#top_menu");menu.plug(Y.Plugin.NodeMenuNav, {submenuShowDelay: 500});menu.get("ownerDocument").get("documentElement").removeClass("yui3-loading");});
        }
    </script>

  </body>
</html>
