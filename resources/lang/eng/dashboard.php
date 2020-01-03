<?php

return [

    'metadata'     => 'Metadata',
    'links'        => 'Links',
    'translations' => 'translations',
    'books'        => 'books',

    'bibles_create_title' => 'Add a New Bible Entry',
    'bibles_scope'        => 'Scope',
    'bibles_id'           => 'id',
    'bibles_scope'        => 'Scope',
    'bibles_derived'      => 'Derived',
    'bibles_copyright'    => 'Copyright',
    'bibles_in_progress'  => 'In Progress',
    'bibles_date'         => 'Published Date',

    'languages_create_title'    => 'Create a New Language Entry',

    'home' => [
        'title' => 'Home'
    ],

    'form' => [
        'choose_file' => 'Choose File...',
        'description' => 'Description'
    ],

    'projects' => [
        'index' => [
            'title' => 'Projects'
        ],
        'create' => [
            'title' => 'Create Project'
        ],
        'form' => [
            'url_site'              => 'Website Url',
            'name'                  => 'Name',
            'icon'                  => 'Icon',
            'logo'                  => 'Logo',
            'sensitive_description' => 'This project is sensitive and should be hidden by default',
        ]
    ],

/*
|--------------------------------------------------------------------------
| Laravel Logger Language Lines - Global
|--------------------------------------------------------------------------
*/
'userTypes' => [
    'guest'      => 'Guest',
    'registered' => 'Registered',
    'crawler'    => 'Crawler',
],

    'verbTypes' => [
    'created'    => 'Created',
    'edited'     => 'Edited',
    'deleted'    => 'Deleted',
    'viewed'     => 'Viewed',
    'crawled'    => 'crawled',
],

    'tooltips' => [
    'viewRecord' => 'View Record Details',
],

        'logger_title'                           => 'Activity Log',
        'logger_subtitle'                        => 'Events',
        'logger_label_id'                        => 'Id',
        'logger_label_time'                      => 'Time',
        'logger_label_description'               => 'Description',
        'logger_label_user'                      => 'User',
        'logger_label_method'                    => 'Method',
        'logger_label_route'                     => 'Route',
        'logger_label_ipAddress'                 => 'Ip <span class="hidden-sm hidden-xs">Address</span>',
        'logger_label_agent'                     => '<span class="hidden-sm hidden-xs">User </span>Agent',
        'logger_label_deleteDate'                => '<span class="hidden-sm hidden-xs">Date </span>Deleted',
        'logger_menu_alt'                        => 'Activity Log Menu',
        'logger_menu_clear'                      => 'Clear Activity Log',
        'logger_menu_show'                       => 'Show Cleared Logs',
        'logger_menu_back'                       => 'Back to Activity Log',
        'logger_drilldown_title'                 => 'Activity Log :id',
        'logger_drilldown_title-details'         => 'Activity Details',
        'logger_drilldown_title-ip-details'      => 'Ip Address Details',
        'logger_drilldown_title-user-details'    => 'User Details',
        'logger_drilldown_title-user-activity'   => 'Additional User Activity',
        'logger_buttons_back'                    => '<span class="hidden-xs hidden-sm">Back to </span><span class="hidden-xs">Activity Log</span>',
        'logger_userRoles'                       => 'User Roles',
        'logger_userLevel'                       => 'Level',
        'logger_list-group_labels_id'            => 'Activity Log ID:',
        'logger_list-group_labels_ip'            => 'Ip Address',
        'logger_list-group_labels_description'   => 'Description',
        'logger_list-group_labels_userType'      => 'User Type',
        'logger_list-group_labels_userId'        => 'User Id',
        'logger_list-group_labels_route'         => 'Route',
        'logger_list-group_labels_agent'         => 'User Agent',
        'logger_list-group_labels_locale'        => 'Locale',
        'logger_list-group_labels_referrer'      => 'Referrer',
        'logger_list-group_labels_methodType'    => 'Method Type',
        'logger_list-group_labels_createdAt'     => 'Event Time',
        'logger_list-group_labels_updatedAt'     => 'Updated At',
        'logger_list-group_labels_deletedAt'     => 'Deleted At',
        'logger_list-group_labels_timePassed'    => 'Time Passed',
        'logger_list-group_labels_userName'      => 'Username',
        'logger_list-group_labels_userFirstName' => 'First Name',
        'logger_list-group_labels_userLastName'  => 'Last Name',
        'logger_list-group_labels_userFulltName' => 'Full Name',
        'logger_list-group_labels_userEmail'     => 'User Email',
        'logger_list-group_labels_userSignupIp'  => 'Signup Ip',
        'logger_list-group_labels_userCreatedAt' => 'Created',
        'logger_list-group_labels_userUpdatedAt' => 'Updated',
    'modals' => [
    'shared' => [
        'btnCancel'     => 'Cancel',
        'btnConfirm'    => 'Confirm',
    ],
    'clearLog' => [
        'title'     => 'Clear Activity Log',
        'message'   => 'Are you sure you want to clear the activity log?',
    ],
    'deleteLog' => [
        'title'     => 'Permanently Delete Activity Log',
        'message'   => 'Are you sure you want to permanently DELETE the activity log?',
    ],
    'restoreLog' => [
        'title'     => 'Restore Cleared Activity Log',
        'message'   => 'Are you sure you want to restore the cleared activity logs?',
    ],
],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Flash Messages
    |--------------------------------------------------------------------------
    */

   'messages' => [
    'logClearedSuccessfuly'   => 'Activity log cleared successfully',
    'logDestroyedSuccessfuly' => 'Activity log deleted successfully',
    'logRestoredSuccessfuly'  => 'Activity log restored successfully',
],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Cleared Dashboard Language Lines
    |--------------------------------------------------------------------------
    */

    'dashboardCleared' => [
    'title'     => 'Cleared Activity Logs',
    'subtitle'  => 'Cleared Events',

    'menu'      => [
        'deleteAll'  => 'Delete All Activity Logs',
        'restoreAll' => 'Restore All Activity Logs',
    ],
],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Pagination Language Lines
    |--------------------------------------------------------------------------
    */
    'pagination' => [
    'countText' => 'Showing :firstItem - :lastItem of :total results <small>(:perPage per page)</small>',
],

    ];
