<?php
// This file contains functions that enhance the admin interface of the Kadence theme.
// It may include hooks and filters to modify the admin experience.

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Example function to add a custom admin notice.
 */
function kep_add_admin_notice() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Kadence Enhancer Plugin is active!', 'kadence-enhancer-plugin' ); ?></p>
    </div>
    <?php
}
add_action( 'admin_notices', 'kep_add_admin_notice' );

/**
 * Example function to enqueue admin scripts and styles.
 */
function kep_enqueue_admin_scripts( $hook ) {
    if ( 'toplevel_page_kadence-enhancer-plugin' !== $hook ) {
        return;
    }
    wp_enqueue_style( 'kep-admin-style', plugin_dir_url( __FILE__ ) . 'css/admin-style.css' );
    wp_enqueue_script( 'kep-admin-script', plugin_dir_url( __FILE__ ) . 'js/admin-script.js', array( 'jquery' ), null, true );
}
add_action( 'admin_enqueue_scripts', 'kep_enqueue_admin_scripts' );

/**
 * Example function to add a custom menu page.
 */
function kep_add_admin_menu() {
    add_menu_page(
        __( 'Kadence Enhancer', 'kadence-enhancer-plugin' ),
        __( 'Kadence Enhancer', 'kadence-enhancer-plugin' ),
        'manage_options',
        'kadence-enhancer-plugin',
        'kep_admin_page_content',
        'dashicons-admin-generic',
        100
    );
}
add_action( 'admin_menu', 'kep_add_admin_menu' );

/**
 * Generate unique shortcode
 */
function kep_generate_unique_shortcode() {
    $videos = get_option('kep_youtube_videos_per_window', 1);
    $time_limit = get_option('kep_youtube_time_limit', 30);
    $rows = get_option('kep_youtube_rows', 1);
    $columns = get_option('kep_youtube_columns', 1);
    $unique_id = uniqid();
    
    return '[kadence_youtube_carousel videos="' . $videos . '" time_limit="' . $time_limit . '" rows="' . $rows . '" columns="' . $columns . '" id="' . $unique_id . '"]';
}

/**
 * Callback function for the admin menu page.
 */
function kep_admin_page_content() {
    if (isset($_POST['submit'])) {
        update_option('kep_youtube_api_key', sanitize_text_field($_POST['kep_youtube_api_key']));
        update_option('kep_youtube_channel_id', sanitize_text_field($_POST['kep_youtube_channel_id']));
        update_option('kep_youtube_videos_per_window', intval($_POST['kep_youtube_videos_per_window']));
        update_option('kep_youtube_time_limit', intval($_POST['kep_youtube_time_limit']));
        update_option('kep_youtube_rows', intval($_POST['kep_youtube_rows']));
        update_option('kep_youtube_columns', intval($_POST['kep_youtube_columns']));
        
        // Generar shortcode único
        $new_shortcode = kep_generate_unique_shortcode();
        update_option('kep_current_shortcode', $new_shortcode);
        
        echo '<div class="notice notice-success"><p>Configuración guardada. Shortcode generado.</p></div>';
    }
    
    $api_key = get_option('kep_youtube_api_key', 'AIzaSyBFZDwEknsw4-JGg-i6COYTf0d_nymmJVw');
    $channel_id = get_option('kep_youtube_channel_id', 'UCP4H6Oww8HKQtJIdehoflNA');
    $videos_per_window = get_option('kep_youtube_videos_per_window', 1);
    $time_limit = get_option('kep_youtube_time_limit', 30);
    $rows = get_option('kep_youtube_rows', 1);
    $columns = get_option('kep_youtube_columns', 1);
    ?>
    <div class="wrap">
        <h1>Kadence Enhancer Settings</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row">YouTube API Key</th>
                    <td><input type="text" name="kep_youtube_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">YouTube Channel ID</th>
                    <td><input type="text" name="kep_youtube_channel_id" value="<?php echo esc_attr($channel_id); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">Videos por Ventana</th>
                    <td>
                        <select name="kep_youtube_videos_per_window">
                            <option value="1" <?php selected($videos_per_window, 1); ?>>1 video</option>
                            <option value="2" <?php selected($videos_per_window, 2); ?>>2 videos</option>
                            <option value="3" <?php selected($videos_per_window, 3); ?>>3 videos</option>
                            <option value="4" <?php selected($videos_per_window, 4); ?>>4 videos</option>
                            <option value="5" <?php selected($videos_per_window, 5); ?>>5 videos</option>
                            <option value="6" <?php selected($videos_per_window, 6); ?>>6 videos</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Límite de Tiempo (segundos)</th>
                    <td><input type="number" name="kep_youtube_time_limit" value="<?php echo esc_attr($time_limit); ?>" min="10" max="300" /></td>
                </tr>
                <tr>
                    <th scope="row">Filas</th>
                    <td>
                        <select name="kep_youtube_rows">
                            <option value="1" <?php selected($rows, 1); ?>>1 fila</option>
                            <option value="2" <?php selected($rows, 2); ?>>2 filas</option>
                            <option value="3" <?php selected($rows, 3); ?>>3 filas</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Columnas</th>
                    <td>
                        <select name="kep_youtube_columns">
                            <option value="1" <?php selected($columns, 1); ?>>1 columna</option>
                            <option value="2" <?php selected($columns, 2); ?>>2 columnas</option>
                            <option value="3" <?php selected($columns, 3); ?>>3 columnas</option>
                        </select>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit" class="button-primary" value="Guardar Configuración" />
            </p>
        </form>
        <h2>Tu Shortcode</h2>
        <div style="background:#f0f0f1;padding:15px;border-radius:5px;margin:10px 0;">
            <p><strong>Copia este shortcode:</strong></p>
            <code style="background:#fff;padding:8px;border-radius:3px;display:block;font-size:14px;"><?php echo esc_html(get_option('kep_current_shortcode', kep_generate_unique_shortcode())); ?></code>
            <p><small>Este shortcode es único y cambia cada vez que guardas la configuración.</small></p>
        </div>
    </div>
    <?php
}
?>