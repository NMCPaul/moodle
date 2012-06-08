<?php
 
class block_carousel_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
        global $COURSE, $CFG, $SESSION, $DB, $USER;
//$data = new stdClass();
//$options = array('subdirs'=>1, 'maxbytes'=>$CFG->userquota, 'maxfiles'=>1, 'accepted_types'=>'*', 'return_types'=>FILE_INTERNAL);
//file_prepare_standard_filemanager($data, 'files', $options, $context, 'user', 'private', 0);

//$mform = new block_private_files_form(null, array('data'=>$data, 'options'=>$options));

        $context = get_context_instance(CONTEXT_BLOCK, $this->block->context->id);
        $mform->addElement('header', 'configheader', get_string('blockgeneralsettings', 'block_carousel'));

        $mform->addElement('text', 'config_imagecount', get_string('blocknumimages', 'block_carousel')); 
        $mform->setDefault('config_imagecount', 4);
        $mform->setType('config_imagecount', 'text');
        if(!($this->block->config->imagecount)) {
            $imagecount = 4;
        } else {
            $imagecount = (int)$this->block->config->imagecount;
        }
        for($count=0;$count<$imagecount;$count++) {
            $mform->addElement('header', 'sub_header'.$count, get_string('blockimageheader', 'block_carousel') . ($count + 1));

            // Title for the image
            $mform->addElement('text', 'config_title'.$count, get_string('blocktitle', 'block_carousel'));

            // Description for the image
            $mform->addElement('text', 'config_description'.$count, get_string('blockdescription', 'block_carousel'));
            
            // Link for the image
            $mform->addElement('text', 'config_link'.$count, get_string('blocklink', 'block_carousel'));

            $mform->addElement('filemanager', 'config_filename'.$count, get_string('blockimage', 'block_carousel'), null, array('maxfiles'=>1, 'subdirs'=>0, 'accepted_types' => array('images'))); 
            //$mform->setType('config_filename'.$count, 'file');

            $draftid_editor = file_get_submitted_draft_itemid('config_filename'.$count);
            $mform->addElement('hidden', 'draftid'.$count, $draftid_editor);
        }
    }

    function set_data( $defaults ) {
        $block_config = new Object();
        for($count=0;$count < $this->block->config->imagecount; $count++) {
            $draftid = 'draftid'.$count;
            $block_config->$draftid = file_get_submitted_draft_itemid( 'config_filename'.$count );
        }
        unset( $this->block->config->text );
        parent::set_data( $defaults );
        $this->block->config = $block_config;
    }

}
