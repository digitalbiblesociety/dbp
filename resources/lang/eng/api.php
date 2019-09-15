<?php

return [

    'success'                                => 'success',
    'errors_401'                             => 'You do not have permission for this action',
    'errors_404'                             => '404 Not Found.',
    'chapter_title_prefix'                   => 'Chapter',

    // Utility Routes
    'auth_permission_denied'                 => 'You do not have permission for this action',
    'auth_password_reset_success'            => 'Password successfully reset',
    'auth_password_reset_token_failed'       => 'The provided password reset token could not be found.',
    'auth_errors_twitter_stateless'          => 'Twitter does not support stateless Authentication',
    'auth_key_validation_failed'             => 'No Authentication Provided or invalid Key',
    'auth_wiki_validation_failed'            => 'You do not have permission to edit the wiki',
    'auth_user_validation_failed'            => 'You do not have permission to edit this user',
    'email_send_successful'                  => 'Email sent successfully',

    // Bibles Routes
    'bibles_errors_404'                      => 'The specified bible_id `:bible_id` could not be found',
    'bible_equivalents_errors_404'           => 'No equivalents for the specified parameters could be found',
    'bible_fileset_errors_404'               => 'The specified fileset id `:id` could not be found.',
    'bible_fileset_errors_404_asset'         => 'No Fileset Found for the :asset_id Asset for the provided params',
    'bible_fileset_errors_401'               => 'The Fileset exists, but no Permissions were found for your current key',
    'bible_filesets_errors_checkback'        => 'The data for this Fileset is still being updated, please check back later',
    'bible_filesets_delete_200'              => 'The fileset `:id` has been successfully deleted',
    'bible_books_errors_404'                 => 'No book found for the given ID',
    'bible_file_errors_404'                  => 'The specified file id `:id` could not be found.',
    'file_errors_404_size'                   => 'No video files found for the current resolution',

    // Wiki Routes
    'wiki_authorization_failed'              => 'Your account does not have Archivist level permissions',
    'alphabets_errors_404'                   => 'No alphabet could be found that has the given id',
    'languages_errors_404'                   => 'Unable to locate language',
    'countries_errors_404'                   => 'No Country for the provided id :id Could be Found',
    'numerals_errors_404'                    => 'Alphabet Numbering System for the provided script `:script` could not be found',
    'numerals_range_error'                   => 'Your given range of :num is outside the max size of 2000',

    // Community Routes
    'users_errors_404'                       => 'No user for the provided param could be found',
    'users_errors_404_email'                 => 'No user with the email `:email` could be found',
    'users_errors_422_email_disposable'      => 'Email is disposable',
    'users_errors_422_email_nonexistent_host'=> 'The provided email host does not exist',
    'users_errors_401_project'               => 'The user given is not a user of the project_id provided.',
    'users_errors_428_password'              => 'Please reset your password',
    'users_errors_404_highlights'            => 'Highlight not found',

    'users_highlights_create_200'            => 'Highlight successfully created',
    'users_highlights_update_200'            => 'Highlight successfully updated',
    'users_highlights_delete_200'            => 'Highlight successfully deleted',

    'user_creation_permission_failed'        => 'You do not have permission to create users',
    'user_notes_store_200'                   => 'Note successfully created',

    'projects_404'                           => 'Project Not found',
    'projects_401'                           => 'You don\'t have permission to alter projects',
    'projects_created_200'                   => 'You have successfully created a new project',
    'projects_updated_200'                   => 'You have successfully updated this project',
    'projects_users_404'                     => 'The specified user is not a member of the project_id provided.',
    'projects_destroy_200'                   => 'Project successfully deleted',
    'projects_destroy_401'                   => 'You must be an admin to delete a project',
    'projects_developer_not_a_member'        => 'The project ID provided is not associated with your developer key',
    'projects_users_not_connected'           => 'This user needs to be connected with one of your projects.',
    'projects_users_error_404'               => 'This user either does not exist',
    'projects_users_needs_to_connect'        => 'This user needs to be connected with this project. A verification email has been sent to them',

    'organizations_relationship_members_404' => 'No membership connection found.',
    'organizations_errors_404'               => 'No record for the organization id found',

    'articles_show_404'                      => 'The specified article id `:id` could not be found.',
    'articles_edit_permission_failed'        => 'You do not have permission to edit the articles',



    // API_DOCS
    'docs' => [

        'paths' => [
            'v2_text_search_group' => [
                'summary'          => 'Run a text search on a specific fileset',
                'description'      => 'This method allows the caller to perform a full-text search within the text of a volume, returning the count of results per book. If the volume has a complementary testament, the search will be performed over both testaments with the results ordered in Bible book order.',
                'param_query'      => 'The text that the caller wishes to search for in the specified text. Multiple words or phrases can be combined with \'+\' for AND and \'|\' for OR. They will be processed simply from left to right. So, `Saul+Paul|Ruth` will evaluate as (Saul AND Paul) OR Ruth.',
                'param_dam_id'     => 'The DAM ID the caller wishes to search in.'
            ],

            'v4_alphabets' => [
                'all' => [
                    'summary' => 'La list de countires'
                ]
            ],
        ],

        'params' => [

        ]

    ],

];
