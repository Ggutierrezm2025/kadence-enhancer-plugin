# Kadence Enhancer Plugin

## Description
The Kadence Enhancer Plugin is designed to enhance the functionality of websites built with the Kadence theme. It includes a YouTube carousel feature specifically designed for Relaxed Axolotl channel.

## Features
- **Admin Enhancements**: Configure YouTube API settings and carousel options
- **YouTube Carousel**: Display latest videos from Relaxed Axolotl channel with time limits
- **Widget Support**: Add YouTube carousel to any widget area
- **Kadence Integration**: Seamless integration with Kadence theme styling
- **Core Functions**: Centralized functions for YouTube API and caching

## Installation
1. Upload the `kadence-enhancer-plugin` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure any settings as needed in the admin panel.

## Usage
1. Configure your YouTube API key and channel settings in **Kadence Enhancer** admin menu
2. Use the shortcode `[kadence_youtube_carousel]` in any page or post
3. Add the YouTube widget to any widget area
4. Customize parameters: `[kadence_youtube_carousel max_videos="3" time_limit="60"]`

## YouTube Features
- Displays latest 5 videos from Relaxed Axolotl channel
- 30-second preview limit with subscription prompt
- Responsive carousel with Swiper.js
- Automatic caching for better performance
- Customizable channel name and description

## Uninstallation
To uninstall the plugin, simply deactivate it from the 'Plugins' menu. The `uninstall.php` file will handle the cleanup of any options and data created by the plugin.

## Support
For support, please open an issue on the plugin's repository or contact the developer directly.