<?php

/**
 * Functions needed by the nmc20 theme should be put here.
 *
 * Any functions that get created here should ALWAYS contain the theme name
 * to reduce complications for other theme designers who may be copying this theme.
 */

class nmc20_navbar_class extends navbar {
}

function nmc20_get_homepage() {
  global $CFG;

  if (!empty($CFG->defaulthomepage) && $CFG->defaulthomepage == HOMEPAGE_MY && !isguestuser()) {
    $homepage = $CFG->wwwroot.'/my/';
  } else {
    $homepage = $CFG->wwwroot;
  }
  return($homepage);
}

// Custom NMC navbar
function nmc20_navbar() {
  global $PAGE,$OUTPUT;
  $test = new nmc20_navbar_class($OUTPUT);
  
  return($test->has_items());
}

// Allows us to specify certain words to NOT be title cased
function nmc20_ucwords($str, $exceptions=array('a','an','the','eLearning')) {
  $out = "";
  foreach (explode(" ", $str) as $word) {
    //$out .= (!in_array($word, $exceptions)) ? strtoupper($word{0}) . substr($word, 1) . " " : $word . " ";
    $out .= (!in_array($word, $exceptions)) ? ucfirst($word) . " " : $word . " ";
  }

  return rtrim($out);
}

function nmc20_process_css($css, $theme) {
  global $CFG, $COURSE;
  // Border color
  if(!empty($theme->settings->bordercolor)) {
    $bordercolor = $theme->settings->bordercolor;
  } else {
    $bordercolor = null;
  }
  $css = nmc20_css_replace($css, $bordercolor, '[[settings:bordercolor]]');

  // Block header text
  if(!empty($theme->settings->blockheadertextcolor)) {
    $blockheadertextcolor = $theme->settings->blockheadertextcolor;
  } else {
    $blockheadertextcolor = null;
  }
  $css = nmc20_css_replace($css, $blockheadertextcolor, '[[settings:blockheadertextcolor]]');

  // Nav bar color
  if(!empty($theme->settings->navbarcolor)) {
    $navbarcolor = $theme->settings->navbarcolor;
  } else {
    $navbarcolor = null;
  }
  $css = nmc20_css_replace($css, $navbarcolor, '[[settings:navbarcolor]]');

  // Normal link color
  if(!empty($theme->settings->linknormalcolor)) {
    $linknormal = $theme->settings->linknormalcolor;
  } else {
    $linknormal = null;
  }
  $css = nmc20_css_replace($css, $linknormal, '[[settings:linknormalcolor]]');

  // Visited link color
  if(!empty($theme->settings->linkvisitedcolor)) {
    $linkvisited = $theme->settings->linkvisitedcolor;
  } else {
    $linkvisited = null;
  }
  $css = nmc20_css_replace($css, $linkvisited, '[[settings:linkvisitedcolor]]');

  $category_tree = nmc20_get_course_categories();
  $cat_total = count($category_tree) - 2;
  if($cat_total < 0) {
    $cat_total = 0;
  }
  @$header = nmc20_get_header(strtolower($category_tree[$cat_total]));
//#echo("HEADER: ".$header);
  $css = nmc20_css_replace($css, $header, 'fading_header_placeholder');

  return($css);
}

function nmc20_css_replace($css, $value, $tag) {
  $css = str_replace($tag, $value, $css);

  return($css);
}

function nmc20_get_course_categories() {
  global $DB,$COURSE;

  $category = $DB->get_record('course_categories', array('id' => $COURSE->category) );
  $cats = array();
  if($category) {
    while($category->parent) {
      $category = $DB->get_record("course_categories", array('id' => $category->parent));
      $cats[] = $category->name;
    }
  }

  return($cats);
}

function nmc20_get_header($main_category = NULL) {
  global $COURSE, $CFG;
  
  $coursename = explode(" ", strtolower($COURSE->shortname));
  $main_category = str_replace(" ", "_", $main_category);
  if(file_exists($CFG->dirroot."/theme/nmc20/pix/header/".$coursename[0].".png")) {
    return($coursename[0]);
  } elseif(file_exists($CFG->dirroot."/theme/nmc20/pix/header/".$main_category.".png") && isset($main_category)) {
    return($main_category);
  } else {
    return("default");
  }
}
