<?php
// This file is executed when the plugin is uninstalled.
// It should clean up any data or options created by the plugin.

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Clear YouTube carousel options
delete_option('kep_youtube_api_key');
delete_option('kep_youtube_channel_id');
delete_option('kep_youtube_channel_name');
delete_option('kep_youtube_channel_desc');
delete_option('kep_youtube_max_videos');
delete_option('kep_youtube_time_limit');
delete_option('kep_youtube_slides_per_view');
delete_option('kep_youtube_primary_color');
delete_option('kep_youtube_secondary_color');
delete_option('kep_youtube_bg_color');

// Clear transients (cache)
delete_transient('kep_youtube_videos_5');
delete_transient('kep_youtube_videos_9');
delete_transient('kep_youtube_videos_15');
?>