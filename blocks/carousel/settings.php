<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_carousel'),
            get_string('descconfig', 'block_carousel')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'carousel/Allow_HTML',
            get_string('labelallowhtml', 'block_carousel'),
            get_string('descallowhtml', 'block_carousel'),
            '0'
        ));
