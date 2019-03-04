# Database Changelog
All notable changes to this project will be documented in this file.

##### Unreleased
Tables that have been created but aren't being actively used... yet
* Created *dbp_users.activations* table
* Created *dbp_users.articles* and *dbp_users.article_translations* tables
* Created *dbp_users.archivist_activity* (Currently laravel_logger_activity)
* Created *dbp_users.2_step_authentication* (Currently laravel_logger_activity)
* Created *dbp_users.profiles*
* Created *dbp.videos*
* Created *dbp.video_translations*
* Created *dbp.video_tags*
* Created *dbp.video_sources*



##### language_id reliance (2018-08-16)

* Removed **iso** field from **bibles**
* Removed **iso** field from **bible_translations**
* Removed **iso** field from **bible_size_translations**
* Added **language_id** field to **bible_size_translations**
* Removed **language_iso** field from **organization_translations**
* Removed **iso** field from **resource_translations**


##### Inital Log (2018-08-14)

###### Connected Numbers tables to Alphabet tables and renamed them.
    * renamed `number_values` to `numeral_system_glyphs` and added `numeral_written` field and timestamps
    * renamed `numbers` to `numeral_systems` and added `notes` field and timestamps
    * Created `alphabet_numeral_systems` to connect `numeral_systems` to different alphabets

###### Separated user tables from the main dbp database including:
        - access_group_keys
        - activations
        - article_tags
        - article_translations
        - articles
        - laravel_logger_activity
        - laravel2step
        - migrations
        - password_resets
        - permission_role
        - permission_user
        - permissions
        - profiles
        - project_members
        - project_oauth_providers
        - projects
        - role_user
        - roles
        - sessions
        - social_logins
        - user_accounts
        - user_highlights
        - user_keys
        - user_note_tags
        - user_notes
        - user_organizations
        - users
        
###### Miscellaneous changes
* Removed **random_order** from **books** table
* Added **slug** field to **resources** table