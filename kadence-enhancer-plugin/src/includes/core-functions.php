<?php
// This file contains core functionalities of the Kadence Enhancer Plugin.
// You can define shared functions used across both admin and public files here.

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Example function to enqueue scripts and styles.
 */
function kep_enqueue_scripts() {
    wp_enqueue_style( 'kep-style', plugins_url( 'assets/css/style.css', __FILE__ ) );
    wp_enqueue_script( 'kep-script', plugins_url( 'assets/js/script.js', __FILE__ ), array( 'jquery' ), null, true );
}
add_action( 'wp_enqueue_scripts', 'kep_enqueue_scripts' );

/**
 * Example function to add a custom shortcode.
 */
function kep_custom_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'title' => 'Default Title',
    ), $atts );

    return '<h2>' . esc_html( $atts['title'] ) . '</h2>';
}
add_shortcode( 'kep_shortcode', 'kep_custom_shortcode' );

/**
 * Example function to register a custom post type.
 */
function kep_register_custom_post_type() {
    register_post_type( 'kep_custom_post',
        array(
            'labels' => array(
                'name' => __( 'Custom Posts' ),
                'singular_name' => __( 'Custom Post' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor', 'thumbnail' ),
        )
    );
}
add_action( 'init', 'kep_register_custom_post_type' );

/**
 * Get latest YouTube videos from channel
 */
function kep_get_youtube_videos($api_key, $channel_id, $max_results = 5) {
    if (empty($api_key) || empty($channel_id)) return false;
    
    $transient_key = 'kep_youtube_videos_' . $max_results;
    $videos = get_transient($transient_key);
    if ($videos !== false) return $videos;

    $api_url = "https://www.googleapis.com/youtube/v3/search?key=$api_key&channelId=$channel_id&part=snippet,id&order=date&maxResults=$max_results&type=video";
    $response = wp_remote_get($api_url);
    if (is_wp_error($response)) return false;

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($data['items'])) return false;

    $videos = [];
    foreach ($data['items'] as $item) {
        $videos[] = [
            'videoId' => $item['id']['videoId'],
            'title' => $item['snippet']['title'],
        ];
    }
    set_transient($transient_key, $videos, 60 * 10);
    return $videos;
}

/**
 * YouTube Carousel Shortcode
 */
function kep_youtube_carousel_shortcode($atts) {
    // Generar ID único para este shortcode
    static $instance = 0;
    $instance++;
    $unique_id = 'kep-carousel-' . $instance;
    
    $atts = shortcode_atts([
        'channel_name' => get_option('kep_youtube_channel_name', 'Relaxed Axolotl'),
        'channel_desc' => get_option('kep_youtube_channel_desc', 'Música para meditación, relajación y bienestar.'),
        'max_videos' => get_option('kep_youtube_max_videos', 5),
        'time_limit' => get_option('kep_youtube_time_limit', 30),
        'rows' => get_option('kep_youtube_rows', 1),
        'columns' => get_option('kep_youtube_columns', 1)
    ], $atts);
    
    $primary_color = get_option('kep_youtube_primary_color', '#b86b2b');
    $secondary_color = get_option('kep_youtube_secondary_color', '#e67e22');
    $bg_color = get_option('kep_youtube_bg_color', '#fff4e6');

    $api_key = get_option('kep_youtube_api_key');
    $channel_id = get_option('kep_youtube_channel_id');
    
    if (empty($api_key) || empty($channel_id)) {
        return '<p>Configure YouTube API en el panel de administración.</p>';
    }

    // Calcular total de videos basado en filas x columnas
    $total_videos = $atts['rows'] * $atts['columns'];
    $video_count = max($total_videos, $atts['max_videos']);
    $videos = kep_get_youtube_videos($api_key, $channel_id, $video_count);
    if (!$videos) return '<p>No se pudieron cargar los videos.</p>';

    $num_videos = count($videos);
    $is_grid = ($atts['rows'] > 1 || $atts['columns'] > 1);

    ob_start();
    ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <style>
    .<?php echo $unique_id; ?> {
        background: linear-gradient(135deg, <?php echo esc_attr($bg_color); ?> 0%, <?php echo esc_attr($bg_color); ?>dd 100%);
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(120, 72, 36, 0.10);
        padding: 24px 8px 16px 8px;
        max-width: <?php echo $is_grid ? '1200px' : '650px'; ?>;
        margin: 0 auto 24px auto;
        border: 2px solid <?php echo esc_attr($primary_color); ?>66;
    }
    .<?php echo $unique_id; ?> .kep-channel-info {
        margin-bottom: 18px;
        text-align: center;
    }
    .<?php echo $unique_id; ?> .kep-channel-title {
        font-size: 1.3em;
        color: <?php echo esc_attr($primary_color); ?>;
        font-family: 'Segoe UI', Arial, sans-serif;
        margin: 0 0 2px 0;
        font-weight: bold;
    }
    .<?php echo $unique_id; ?> .kep-channel-desc {
        font-size: 1em;
        color: <?php echo esc_attr($primary_color); ?>aa;
        margin: 0;
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    .<?php echo $unique_id; ?> .swiper {
        width: 100%;
    }
    .<?php echo $unique_id; ?> .swiper-slide {
        text-align: center;
        background: #fffaf5;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(120, 72, 36, 0.07);
        padding: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-sizing: border-box;
    }
    .<?php echo $unique_id; ?> .video-wrapper {
        width: 100%;
        aspect-ratio: 16/9;
        margin-bottom: 12px;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid <?php echo esc_attr($primary_color); ?>66;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .<?php echo $unique_id; ?> iframe {
        width: 100%;
        height: 100%;
        border: none;
        display: block;
    }
    .<?php echo $unique_id; ?> .video-title {
        color: <?php echo esc_attr($primary_color); ?>;
        font-family: 'Segoe UI', Arial, sans-serif;
        font-size: 1.1em;
        margin-bottom: 0;
        margin-top: 0;
    }
    .<?php echo $unique_id; ?> .swiper-pagination {
        margin-top: 22px !important;
        margin-bottom: 14px !important;
        position: relative !important;
        z-index: 2;
    }
    .<?php echo $unique_id; ?> .swiper-pagination-bullet {
        background: <?php echo esc_attr($primary_color); ?>;
        opacity: 0.7;
    }
    .<?php echo $unique_id; ?> .swiper-pagination-bullet-active {
        background: <?php echo esc_attr($secondary_color); ?>;
        opacity: 1;
    }
    .<?php echo $unique_id; ?> .swiper-button-next,
    .<?php echo $unique_id; ?> .swiper-button-prev {
        color: <?php echo esc_attr($primary_color); ?>;
    }
    .<?php echo $unique_id; ?> .yt-limit-message {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,244,230,0.97);
        color: <?php echo esc_attr($primary_color); ?>;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1em;
        font-family: 'Segoe UI', Arial, sans-serif;
        z-index: 10;
        border-radius: 10px;
        text-align: center;
        padding: 20px;
        pointer-events: all;
    }
    <?php if ($is_grid): ?>
    .<?php echo $unique_id; ?> .swiper-wrapper {
        display: grid !important;
        grid-template-columns: repeat(<?php echo (int)$atts['columns']; ?>, 1fr);
        gap: 15px;
        transform: none !important;
    }
    .<?php echo $unique_id; ?> .swiper-slide {
        width: auto !important;
        margin: 0 !important;
    }
    .<?php echo $unique_id; ?> .swiper-pagination,
    .<?php echo $unique_id; ?> .swiper-button-next,
    .<?php echo $unique_id; ?> .swiper-button-prev {
        display: none !important;
    }
    @media (max-width: 768px) {
        .<?php echo $unique_id; ?> .swiper-wrapper {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 480px) {
        .<?php echo $unique_id; ?> .swiper-wrapper {
            grid-template-columns: 1fr;
        }
    }
    <?php endif; ?>
    @media (max-width: 700px) {
        .<?php echo $unique_id; ?> { padding: 8px 2px 2px 2px; }
        .<?php echo $unique_id; ?> .video-wrapper { aspect-ratio: 16/9; }
    }
    </style>
    <div class="<?php echo $unique_id; ?>">
      <div class="kep-channel-info">
        <div class="kep-channel-title"><?php echo esc_html($atts['channel_name']); ?></div>
        <div class="kep-channel-desc"><?php echo esc_html($atts['channel_desc']); ?></div>
      </div>
      <div class="swiper">
        <div class="swiper-wrapper">
          <?php foreach ($videos as $i => $video): ?>
            <div class="swiper-slide">
              <div class="video-wrapper" id="<?php echo $unique_id; ?>-video-<?php echo $i; ?>">
                <iframe id="<?php echo $unique_id; ?>-iframe-<?php echo $i; ?>" src="https://www.youtube.com/embed/<?php echo esc_attr($video['videoId']); ?>?enablejsapi=1&autoplay=0" allowfullscreen></iframe>
                <div class="mobile-timer" id="<?php echo $unique_id; ?>-timer-<?php echo $i; ?>" style="display:none;position:absolute;top:5px;right:5px;background:rgba(0,0,0,0.7);color:white;padding:4px 8px;border-radius:4px;font-size:12px;z-index:5;"></div>
                <div class="yt-limit-message" id="<?php echo $unique_id; ?>-msg-<?php echo $i; ?>" style="display:none;">
                  <div style="text-align:center;">
                    <strong>¡Te gustó? ¡Suscríbete para escuchar completo!</strong><br>
                    <small style="font-size:0.9em;margin-top:8px;display:block;">Redirigiendo a YouTube en 3 segundos...</small>
                    <div style="margin-top:10px;">
                      <a href="https://www.youtube.com/channel/<?php echo esc_attr($channel_id); ?>?sub_confirmation=1" target="_blank" style="color:<?php echo esc_attr($secondary_color); ?>;text-decoration:underline;font-weight:bold;">¡Ir ahora!</a>
                    </div>
                  </div>
                </div>
              </div>
              <p class="video-title"><?php echo esc_html($video['title']); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
        <?php if (!$is_grid): ?>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <?php endif; ?>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
    // Variables para esta instancia
    if (!window.kepInstances) window.kepInstances = {};
    window.kepInstances['<?php echo $unique_id; ?>'] = {
      players: {},
      timers: {},
      timeLimit: <?php echo (int)$atts['time_limit']; ?>,
      channelUrl: 'https://www.youtube.com/channel/<?php echo esc_js($channel_id); ?>?sub_confirmation=1'
    };
    
    // Inicializar Swiper
    document.addEventListener('DOMContentLoaded', function() {
      <?php if (!$is_grid): ?>
      new Swiper('.<?php echo $unique_id; ?> .swiper', {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 20,
        pagination: { el: '.<?php echo $unique_id; ?> .swiper-pagination', clickable: true },
        navigation: { nextEl: '.<?php echo $unique_id; ?> .swiper-button-next', prevEl: '.<?php echo $unique_id; ?> .swiper-button-prev' }
      });
      <?php endif; ?>
      
      // Cargar API de YouTube
      if (!window.YT) {
        var tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
      } else {
        init<?php echo str_replace('-', '_', $unique_id); ?>();
      }
    });
    
    // Detectar móvil
    var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    // Método simple para móviles - detectar click y bloquear después de 30s
    if (isMobile) {
      <?php for ($i = 0; $i < $num_videos; $i++): ?>
      (function(videoIndex) {
        var wrapper = document.getElementById('<?php echo $unique_id; ?>-video-' + videoIndex);
        var timer = document.getElementById('<?php echo $unique_id; ?>-timer-' + videoIndex);
        var inst = window.kepInstances['<?php echo $unique_id; ?>'];
        
        if (wrapper) {
          wrapper.addEventListener('click', function() {
            console.log('<?php echo $unique_id; ?> - Móvil click en video ' + videoIndex);
            
            if (inst.timers[videoIndex]) {
              clearTimeout(inst.timers[videoIndex]);
            }
            
            // Mostrar contador
            if (timer) {
              timer.style.display = 'block';
              var countdown = inst.timeLimit;
              var countInterval = setInterval(function() {
                timer.textContent = countdown + 's';
                countdown--;
                if (countdown < 0) {
                  clearInterval(countInterval);
                }
              }, 1000);
            }
            
            inst.timers[videoIndex] = setTimeout(function() {
              console.log('<?php echo $unique_id; ?> - Móvil: Bloqueando video ' + videoIndex);
              
              // Bloquear iframe
              var iframe = document.getElementById('<?php echo $unique_id; ?>-iframe-' + videoIndex);
              if (iframe) {
                iframe.style.pointerEvents = 'none';
                iframe.style.opacity = '0.5';
              }
              
              // Mostrar mensaje
              var msgElement = document.getElementById('<?php echo $unique_id; ?>-msg-' + videoIndex);
              if (msgElement) {
                msgElement.style.display = 'flex';
              }
              
              setTimeout(function() {
                window.open(inst.channelUrl, '_blank');
              }, 3000);
              
            }, inst.timeLimit * 1000);
          }, { once: true }); // Solo una vez por video
        }
      })(<?php echo $i; ?>);
      <?php endfor; ?>
    }
    
    // Método normal para PC
    function init<?php echo str_replace('-', '_', $unique_id); ?>() {
      if (isMobile) return; // Skip en móviles
      
      var instance = window.kepInstances['<?php echo $unique_id; ?>'];
      
      <?php for ($i = 0; $i < $num_videos; $i++): ?>
      instance.players[<?php echo $i; ?>] = new YT.Player('<?php echo $unique_id; ?>-iframe-<?php echo $i; ?>', {
        events: {
          'onStateChange': function(event) {
            var playerId = <?php echo $i; ?>;
            var inst = window.kepInstances['<?php echo $unique_id; ?>'];
            
            if (event.data === YT.PlayerState.PLAYING) {
              console.log('<?php echo $unique_id; ?> - PC: Video ' + playerId + ' playing');
              
              if (inst.timers[playerId]) {
                clearTimeout(inst.timers[playerId]);
              }
              
              inst.timers[playerId] = setTimeout(function() {
                console.log('<?php echo $unique_id; ?> - PC: Pausando video ' + playerId);
                
                inst.players[playerId].pauseVideo();
                
                var msgElement = document.getElementById('<?php echo $unique_id; ?>-msg-' + playerId);
                if (msgElement) {
                  msgElement.style.display = 'flex';
                }
                
                setTimeout(function() {
                  window.open(inst.channelUrl, '_blank');
                }, 3000);
                
              }, inst.timeLimit * 1000);
            }
            
            if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.ENDED) {
              if (inst.timers[playerId]) {
                clearTimeout(inst.timers[playerId]);
              }
            }
          }
        }
      });
      <?php endfor; ?>
    }
    
    if (window.YT && window.YT.Player) {
      init<?php echo str_replace('-', '_', $unique_id); ?>();
    } else {
      if (!window.onYouTubeIframeAPIReady) {
        window.onYouTubeIframeAPIReady = function() {
          for (var id in window.kepInstances) {
            var funcName = 'init' + id.replace(/-/g, '_');
            if (window[funcName]) window[funcName]();
          }
        };
      }
      window['init<?php echo str_replace('-', '_', $unique_id); ?>'] = init<?php echo str_replace('-', '_', $unique_id); ?>;
    }
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('kadence_youtube_carousel', 'kep_youtube_carousel_shortcode');
?>