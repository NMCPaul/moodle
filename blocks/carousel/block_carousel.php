<?php

require_once('lib.php');

class block_carousel extends block_base {
    public function init() {
        $this->title = get_string('carousel', 'block_carousel');
    }

    public function specialization() {
        if (!empty($this->config->title)) {
            $this->title = $this->config->title;
        }
    }

    public function get_content() {
        if ($this->content !== NULL) {
            return $this->content;
        }
        if (empty($this->instance)) {
            return null;
        }

        $this->content = new stdClass;
        if (empty($this->config->imagecount)) {
            $this->content->text = null;
        } else {
            global $CFG, $OUTPUT, $DB;
            $imagecount = $this->config->imagecount;
            $fs = get_file_storage();
            $image_list = '<div id="carousel_container">';
            $image_list .= '<ul id="carousel">';
            for($count=0;$count<$imagecount;$count++) {
                $titlenum = "title".$count;
                $descriptionnum = "description".$count;
                $imagenum = "image".$count;
                $linknum = "link".$count;
                $image_info = $fs->get_file_by_hash($this->config->$imagenum);
                if($image_info) {
                    $url2 = file_rewrite_pluginfile_urls('@@PLUGINFILE@@/'.$image_info->get_filename(), 'pluginfile.php', $image_info->get_contextid(), 'block_carousel','attachment', $image_info->get_itemid());
                    $image_list .= '<li><a href="'.$this->config->$linknum.'">';
                    $image_parameters = 'image='.$url2.'&title='.$this->config->$titlenum.'&description='.$this->config->$descriptionnum;
                    $image_list .= '<img src="/blocks/carousel/view.php?'.urlencode($image_parameters).'" \>';
                    //$image_list .= '<img src="data:image/png;base64,'.block_carousel_create_overlay($url2, $this->config->$titlenum, $this->config->$descriptionnum).'" \>';
                    $image_list .= '</a></li>';
                }
            }
            $image_list .= '</ul>';
            $image_list .= '</div>';
            $image_list .= '<script type="text/javascript">
                                var animation = new homepageAnimation("carousel", {speed:8000});
                            </script>';
            $this->content->text   = $image_list;
        }
        //$this->content->footer = 'Footer here...';
    
        return $this->content;
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function instance_config_save($data) {
        $config = clone($data);
        $config->contextid = $this->context->id;
        $fs = get_file_storage();
        for($count=0;$count < $config->imagecount; $count++) {
            $filename = 'filename'.$count;
            $draft = "draftid".$count;
            $draftid = $this->config->$draft;
            $fileid = $config->$filename;
            $text = file_get_drafarea_files($fileid);
            file_save_draft_area_files($draftid, $config->contextid, 'block_carousel', 'attachment', $fileid, array('subdirs' => 0, 'maxfiles' => 1));
            $content = $fs->get_area_files($this->context->id, 'block_carousel', 'attachment', $fileid, null, false);
            //print_object($content);
            foreach($content as $k => $v) {
                $image = "image".$count;
                $data->$image = $k;
            }
        }
        return parent::instance_config_save($data);
    }

    public function hide_header() {
        return true;
    }
}
