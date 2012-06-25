        <!-- START OF FOOTER -->
        <?php if ($hasfooter) { ?>
          <div id="page-footer" class="clearfix">
            <p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
            <?php
              #echo $OUTPUT->login_info();
              #echo $OUTPUT->home_link();
                echo('<script type="text/javascript" src="'.$CFG->wwwroot.'/blocks/jumpto_menu/dropdown.js"></script>');
                require_once($CFG->dirroot."/blocks/jumpto_menu/lib.php");
                echo block_jumpto_menu_html();
                /// Provide some performance info if required
                $performanceinfo = '';
                if ($CFG->adminonlyperfinfo and is_siteadmin($USER)) {
                if (defined('MDL_PERF') || (!empty($CFG->perfdebug) and $CFG->perfdebug >= 7)) {
                        $perf = get_performance_info();
                        if (defined('MDL_PERFTOLOG') && !function_exists('register_shutdown_function')) {
                            error_log("PERF: " . $perf['txt']);
                        }
                        if (defined('MDL_PERFTOFOOT') || debugging() || $CFG->perfdebug >= 7) {
                            echo($perf['html']);
                        }
                    }

                }
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
