<?php
/**
 * Form for Timeline records.
 */
class BulkUsers_Form_Add extends Omeka_Form
{
    public function init()
    {
        parent::init();

        $this->setMethod('post');
        $this->setAttrib('id', 'bulkusers-add-form');

        // Description
        $this->addElement('textarea', 'emails', array(
            'label'       => __('User Emails'),
            'description' => __('Emails for each of your users, separated by comma or new line.'),
            'attribs'     => array('rows' => '15')
        ));

        // Public/Not Public
        $this->addElement('select', 'role', array(
            'label'        => __('Role'),
            'description'  => __("Roles describe the permissions a user has. See <a href='http://omeka.org/codex/User_Roles' target='_blank'>documentation</a> for details."),
            'multiOptions' => get_user_roles(),
            'required' => true
          ));

        // Submit
        $this->addElement('submit', 'submit', array(
            'label' => __('Submit')
        ));

        // Group the title, description, and public fields
        $this->addDisplayGroup(
            array('emails','role'),
            'bulkuser_info'
        );

        // Add the submit to a separate display group.
        $this->addDisplayGroup(array('submit'), 'bulkuser_submit');
    }

}
