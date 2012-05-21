<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Border color setting
    $name = 'theme_nmc20/bordercolor';
    $title = get_string('bordercolor','theme_nmc20');
    $description = get_string('bordercolordesc', 'theme_nmc20');
    $default = '#E4B150';
    $previewconfig = array('selector' => '#admin-bordercolor .currentcolour', 'style' => 'backgroundColor');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $settings->add($setting);

    // Block header text color setting
    $name = 'theme_nmc20/blockheadertextcolor';
    $title = get_string('blockheadertextcolor','theme_nmc20');
    $description = get_string('blockheadertextcolordesc', 'theme_nmc20');
    $default = '#204360';
    $previewconfig = array('selector' => '#admin-blockheadertextcolor .currentcolour', 'style' => 'backgroundColor');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $settings->add($setting);

    // Nav bar color setting
    $name = 'theme_nmc20/navbarcolor';
    $title = get_string('navbarcolor','theme_nmc20');
    $description = get_string('navbarcolordesc', 'theme_nmc20');
    $default = '#356F9D';
    $previewconfig = array('selector' => '#admin-navbarcolor .currentcolour', 'style' => 'backgroundColor');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $settings->add($setting);

    // Link color normal
    $name = 'theme_nmc20/linknormalcolor';
    $title = get_string('linknormalcolor','theme_nmc20');
    $description = get_string('linknormalcolordesc', 'theme_nmc20');
    $default = '#9B3718';
    $previewconfig = array('selector' => '#admin-linknormalcolor .currentcolour', 'style' => 'backgroundColor');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $settings->add($setting);

    // Link color visited
    $name = 'theme_nmc20/linkvisitedcolor';
    $title = get_string('linkvisitedcolor','theme_nmc20');
    $description = get_string('linkvisitedcolordesc', 'theme_nmc20');
    $default = '#D54F21';
    $previewconfig = array('selector' => '#admin-linkvisitedcolor .currentcolour', 'style' => 'backgroundColor');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $settings->add($setting);
}
