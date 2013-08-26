<?php

if (!defined('BULKUSERS_PLUGIN_DIR')) {
    define('BULKUSERS_PLUGIN_DIR', dirname(__FILE__));
}

class BulkUsersPlugin extends Omeka_Plugin_AbstractPlugin {

    // Hooks and Filters
    protected $_hooks = array(
        'define_acl'
    );

    protected $_filters = array(
        'admin_navigation_main'
    );

    /**
     *  define_acl hook
     */
    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];

        $acl->addResource('BulkUsers_Index');

        // Specifically deny Admin users.
        $acl->deny('admin', 'BulkUsers_Index');

    }

    /**
     * Adds a button to the admin's main navigation.
     *
     * @param array $nav
     * @return array
     */
    public function filterAdminNavigationMain($nav)
    {

        $nav[] = array(
            'label' => __('Bulk Users'),
            'uri' => url('bulk-users'),
            'resource' => 'BulkUsers_Index'
        );

        return $nav;

    }

}
