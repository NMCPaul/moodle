<?php

class theme_nmc20_block_navigation_renderer extends plugin_renderer_base {

    public function navigation_tree(global_navigation $navigation, $expansionlimit, array $options = array()) {
        $navigation->add_class('navigation_node');
        $content = $this->navigation_node(array($navigation), array('class'=>'block_tree list'), $expansionlimit, $options);
        if (isset($navigation->id) && !is_numeric($navigation->id) && !empty($content)) {
            $content = $this->output->box($content, 'block_tree_box', $navigation->id);
        }
        return $content;
    }
    protected function navigation_node($items, $attrs=array(), $expansionlimit=null, array $options = array(), $depth=1) {

        // exit if empty, we don't want an empty ul element
        if (count($items)==0) {
            return '';
        }

        // array of nested li elements
        $lis = array();
        foreach ($items as $item) {
            if (!$item->display && !$item->contains_active_node()) {
                continue;
            }
            $content = $item->get_content();
            $title = $item->get_title();

            $isexpandable = (empty($expansionlimit) || ($item->type > navigation_node::TYPE_ACTIVITY || $item->type < $expansionlimit) || ($item->contains_active_node() && $item->children->count() > 0));
            $isbranch = $isexpandable && ($item->children->count() > 0 || ($item->has_children() && (isloggedin() || $item->type <= navigation_node::TYPE_CATEGORY)));

            $hasicon = ((!$isbranch || $item->type == navigation_node::TYPE_ACTIVITY )&& $item->icon instanceof renderable);

            if ($hasicon) {
                $icon = $this->output->render($item->icon);
                $content = $icon.$content; // use CSS for spacing of icons
            }
            if ($item->helpbutton !== null) {
                $content = trim($item->helpbutton).html_writer::tag('span', $content, array('class'=>'clearhelpbutton'));
            }

            if ($content === '') {
                continue;
            }

            $attributes = array();
            if ($title !== '') {
                $attributes['title'] = $title;
            }
            if ($item->hidden) {
                $attributes['class'] = 'dimmed_text';
            }
            if (is_string($item->action) || empty($item->action) || ($item->type === navigation_node::TYPE_CATEGORY && empty($options['linkcategories']))) {
                $attributes['tabindex'] = '0'; //add tab support to span but still maintain character stream sequence.
                $content = html_writer::tag('span', $content, $attributes);
            } else if ($item->action instanceof action_link) {
                //TODO: to be replaced with something else
                $link = $item->action;
                $link->attributes = array_merge($link->attributes, $attributes);
                $content = $this->output->render($link);
                $linkrendered = true;
            } else if ($item->action instanceof moodle_url) {
                $content = html_writer::link($item->action, $content, $attributes);
            }
            // this applies to the li item which contains all child lists too
            $liclasses = array($item->get_css_type(), 'depth_'.$depth);
            if ($item->has_children() && (!$item->forceopen || $item->collapse)) {
                $liclasses[] = 'collapsed';
            }
            if ($isbranch) {
                $liclasses[] = 'contains_branch';
            } else if ($hasicon) {
                $liclasses[] = 'item_with_icon';
            }
            if ($item->isactive === true) {
                $liclasses[] = 'current_branch';
            }
            $liattr = array('class'=>join(' ',$liclasses));
            // class attribute on the div item which only contains the item content
            $divclasses = array('tree_item');
            if ($isbranch) {
                $divclasses[] = 'branch';
            } else {
                $divclasses[] = 'leaf';
            }
            if ($hasicon) {
                $divclasses[] = 'hasicon';
            }
            if (!empty($item->classes) && count($item->classes)>0) {
                $divclasses[] = join(' ', $item->classes);
            }
            $divattr = array('class'=>join(' ', $divclasses));
            if (!empty($item->id)) {
                $divattr['id'] = $item->id;
            }
            $content = html_writer::tag('p', $content, $divattr);
            if ($isexpandable) {
                $content .= $this->navigation_node($item->children, array(), $expansionlimit, $options, $depth+1);
            }
            if (!empty($item->preceedwithhr) && $item->preceedwithhr===true) {
                $content = html_writer::empty_tag('hr') . $content;
            }
            $content = html_writer::tag('li', $content, $liattr);
            $lis[] = $content;
        }

        if (count($lis)) {
            return html_writer::tag('ul', implode("\n", $lis), $attrs);
        } else {
            return '';
        }
    }

}
/*class theme_nmc20_block_settings_renderer extends block_settings_renderer {
    public function navigation_node(navigation_node $node, $attrs=array()) {
        global $USER, $CFG;
        $parent = parent::navigation_node($node, $attrs);
        // no need to intervene for web services
        if (!is_siteadmin($USER->id) ||
             !has_capability('moodle/webservice:createtoken', get_system_context()) ) {
            // strip out the security keys link
            $keys = get_string('securitykeys', 'webservice');
            $search = '()';
            $parent = preg_replace($search, '', $parent);
        }
        return $parent;
    }
}
*/

class theme_nmc20_core_renderer extends core_renderer {
  
  protected function render_custom_menu(custom_menu $menu) {
    #$mycourses = $this->page->navigation->get('mycourses');
  }

  function nmc20_navbar() {
    $nav = $this->page->navbar->get_items();
    $htmlblocks = array();
    $itemcount = count($nav);
    $separator = get_separator();
    for ($i=0;$i < $itemcount;$i++) {
      $item = $nav[$i];
      if($item->action) {
        $item->hideicon=TRUE;
        if($i === 0) {
          $content = html_writer::tag('li', $this->render($item));
        } elseif($item->check_if_active()) {
          $item->action = NULL;
          $content = html_writer::tag('li', $separator.$this->render($item));
        } else {
          $content = html_writer::tag('li', $separator.$this->render($item));
        }
        $htmlblocks[] = $content;
      }
    }
    $navbarcontent = html_writer::tag('span', get_string('pagepath'), array('class'=>'accesshide'));
    $navbarcontent .= html_writer::tag('ul', join('', $htmlblocks));

    return($navbarcontent);
  }

    /**
     * Prints a nice side block with an optional header.
     *
     * The content is described
     * by a {@link block_contents} object.
     *
     * @param block_contents $bc HTML for the content
     * @param string $region the region the block is appearing in.
     * @return string the HTML to be output.
     */
    function block($bc, $region) {

        $bc = clone($bc); // Avoid messing up the object passed in.
        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        }
        if ($bc->collapsible == block_contents::HIDDEN) {
            $bc->add_class('hidden');
        }
        if (!empty($bc->controls)) {
            $bc->add_class('block_with_controls');
        }

        $skiptitle = strip_tags($bc->title);
        if (empty($skiptitle)) {
            $output = '';
            $skipdest = '';
        } else {
            $output = html_writer::tag('a', get_string('skipa', 'access', $skiptitle), array('href' => '#sb-' . $bc->skipid, 'class' => 'skip-block'));
            $skipdest = html_writer::tag('span', '', array('id' => 'sb-' . $bc->skipid, 'class' => 'skip-block-to'));
        }

        $output .= html_writer::start_tag('div', $bc->attributes);
        
        /** Rounded corners **/
        $output .= html_writer::start_tag('div', array('class'=>'corner-box'));
        $output .= html_writer::start_tag('div', array('class'=>'rounded-corner top-left')).html_writer::end_tag('div');
        $output .= html_writer::start_tag('div', array('class'=>'rounded-corner top-right')).html_writer::end_tag('div');

        $controlshtml = $this->block_controls($bc->controls);

        $title = '';
        if ($bc->title) {
            $title = html_writer::tag('h2', nmc20_ucwords($bc->title));
        }

        if ($title || $controlshtml) {
            $output .= html_writer::tag('div', html_writer::tag('div', html_writer::tag('div', '', array('class'=>'block_action')). $title . $controlshtml, array('class' => 'title')), array('class' => 'header'));
        }

        $output .= html_writer::start_tag('div', array('class' => 'content'));
        if (!$title && !$controlshtml) {
            $output .= html_writer::tag('div', '', array('class'=>'block_action notitle'));
        }
        $output .= $bc->content;

        if ($bc->footer) {
            $output .= html_writer::tag('div', $bc->footer, array('class' => 'footer'));
        }

        $output .= html_writer::end_tag('div');

                /** Four rounded corner ends **/
        $output .= html_writer::start_tag('div', array('class'=>'rounded-corner bottom-left')).html_writer::end_tag('div');
        $output .= html_writer::start_tag('div', array('class'=>'rounded-corner bottom-right')).html_writer::end_tag('div');
        $output .= html_writer::end_tag('div');

        $output .= html_writer::end_tag('div');

        if ($bc->annotation) {
            $output .= html_writer::tag('div', $bc->annotation, array('class' => 'blockannotation'));
        }
        $output .= $skipdest;

        $this->init_block_hider_js($bc);

        return $output;
    }

}
