<?php
if (!defined('ABSPATH')) exit;

class Aztra_Shortcodes {
  public static function register(){
    add_shortcode('aztra_login', [__CLASS__, 'login']);
    add_shortcode('aztra_signup', [__CLASS__, 'signup']);
    add_shortcode('aztra_builder', [__CLASS__, 'builder']);
    add_shortcode('aztra_gallery', [__CLASS__, 'gallery']);
    add_shortcode('aztra_home', [__CLASS__, 'home']);
    add_shortcode('aztra_chat', [__CLASS__, 'chat']);
    add_shortcode('aztra_privacy', [__CLASS__, 'privacy']);
    add_shortcode('aztra_terms', [__CLASS__, 'terms']);
    add_shortcode('aztra_commands', [__CLASS__, 'commands']);
  }

  private static function render_header(){
    ?>
    <header class="az-header">
      <div class="az-brand">Aztra&nbsp;G</div>
      <nav class="az-nav">
        <button class="az-btn" data-aztra-act="toggle-theme">Tema</button>
      </nav>
    </header>
    <?php
  }

  private static function render_footer(){
    ?>
    <footer class="az-footer">
      <a href="<?php echo esc_url( get_permalink( get_page_by_title('Aztra — Tutoriais') ) ); ?>">Tutoriais</a>
    </footer>
    <?php
  }

  public static function login($atts=[]){
    wp_enqueue_style('aztra-app'); wp_enqueue_script('aztra-app');
    ob_start(); ?>
    <div class="az-card">
      <h2>Login</h2>
      <?php if(!is_user_logged_in()): ?>
        <form method="post" action="<?php echo esc_url( wp_login_url() ); ?>">
          <div class="az-field"><label>Username</label><input name="log" required></div>
          <div class="az-field"><label>Password</label><input type="password" name="pwd" required></div>
          <button class="az-btn">Login</button>
        </form>
        <p>Don't have an account? <a href="<?php echo esc_url( get_permalink( get_page_by_title('Aztra — Signup') ) ); ?>">Create account</a></p>
      <?php else: ?>
        <p>You're logged in. <a href="<?php echo esc_url( get_permalink( get_page_by_title('Aztra — App') ) ); ?>">Open App</a></p>
      <?php endif; ?>
    </div>
    <?php return ob_get_clean();
  }

  public static function signup($atts = []){
    wp_enqueue_style('aztra-app'); wp_enqueue_script('aztra-app');
    $a = shortcode_atts([
      'title' => 'Create account',
      'subtitle' => 'Use your access code to join.',
      'label_username' => 'Username',
      'label_password' => 'Password',
      'label_access' => 'Access code',
      'placeholder_access' => 'Enter your access code',
      'button' => 'Create account',
    ], $atts, 'aztra_signup');

    ob_start(); ?>
    <div class="az-card" data-aztra-login-url="<?php echo esc_url( get_permalink( get_page_by_title('Aztra — Login') ) ); ?>">
      <h2><?php echo esc_html($a['title']); ?></h2>
      <?php if(!empty($a['subtitle'])): ?><p class="az-sub"><?php echo esc_html($a['subtitle']); ?></p><?php endif; ?>
      <div class="az-field"><label><?php echo esc_html($a['label_username']); ?></label><input id="az-su-user"></div>
      <div class="az-field"><label><?php echo esc_html($a['label_password']); ?></label><input id="az-su-pass" type="password"></div>
      <div class="az-field"><label><?php echo esc_html($a['label_access']); ?></label>
        <input id="az-su-code" autocomplete="off" placeholder="<?php echo esc_attr($a['placeholder_access']); ?>"></div>
      <button class="az-btn az-primary" data-aztra-act="signup" type="button"><?php echo esc_html($a['button']); ?></button>
    </div>
    <?php return ob_get_clean();
  }

  public static function builder($atts=[]){
    if(!is_user_logged_in()){ return '<p>Please log in to use the app.</p>'; }
    wp_enqueue_style('aztra-app'); wp_enqueue_style('aztra-el'); wp_enqueue_script('aztra-app'); wp_enqueue_script('aztra-el');
    ob_start(); ?>
    <div class="az-grid">
      <div class="az-card">
        <h3>Builder</h3>
        <form id="aztra-form">
          <div class="az-grid-2">
            <div class="az-field"><label>Animal</label><select id="animal" name="animal"></select></div>
            <div class="az-field"><label>Scenario</label><select id="scenario" name="scenario"></select></div>
            <div class="az-field"><label>Time of day</label><select id="time_of_day" name="time_of_day"></select></div>
            <div class="az-field"><label>Weather</label><select id="weather" name="weather"></select></div>
            <div class="az-field"><label>Flight style</label><select id="flight_style" name="flight_style"></select></div>
            <div class="az-field"><label>Camera movement</label><select id="camera_movement" name="camera_movement"></select></div>
            <div class="az-field az-col-2"><label>Style</label><select id="style" name="style"></select></div>
          </div>
          <button class="az-btn az-primary" type="button" data-aztra-act="generate">Send to Workflow</button>
        </form>
      </div>

      <div class="az-card">
        <h3>Live Response</h3>
        <pre id="aztra-response">{}</pre>
        <h4>Assets</h4>
        <div id="aztra-assets" class="az-assets"></div>
      </div>
    </div>
    <?php return ob_get_clean();
  }

  public static function gallery($atts=[]){
    if(!is_user_logged_in()){ return '<p>Please log in to view your gallery.</p>'; }
    wp_enqueue_style('aztra-app');
    $q = new WP_Query([
      'post_type'=>'aztra_request',
      'posts_per_page'=>20,
      'author'=> get_current_user_id(),
      'orderby'=>'date','order'=>'DESC'
    ]);
    ob_start(); ?>
    <div class="az-grid">
      <?php if($q->have_posts()): while($q->have_posts()): $q->the_post();
        $resp = get_post_meta(get_the_ID(),'aztra_response',true);
        $links = get_post_meta(get_the_ID(),'aztra_links',true);
        $atts = get_attached_media('', get_the_ID());
      ?>
        <div class="az-card">
          <h4><?php the_title(); ?></h4>
          <div class="az-assets">
            <?php foreach($atts as $att): $url = wp_get_attachment_url($att->ID); $type = get_post_mime_type($att->ID); ?>
              <?php if(strpos($type,'image/')===0): ?>
                <img src="<?php echo esc_url($url); ?>" class="az-thumb" />
              <?php elseif(strpos($type,'video/')===0): ?>
                <video controls class="az-thumb"><source src="<?php echo esc_url($url); ?>"></video>
              <?php else: ?>
                <a class="az-btn" href="<?php echo esc_url($url); ?>" target="_blank">Download</a>
              <?php endif; ?>
            <?php endforeach; ?>
            <?php if(!empty($links)): foreach((array)$links as $link): ?>
              <a class="az-btn" href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener">Open Link</a>
            <?php endforeach; endif; ?>
          </div>
          <?php if(!empty($resp)): ?><details><summary>JSON</summary><pre><?php echo esc_html(json_encode($resp, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)); ?></pre></details><?php endif; ?>
        </div>
      <?php endwhile; else: ?>
        <p>No items yet.</p>
      <?php endif; wp_reset_postdata(); ?>
    </div>
    <?php return ob_get_clean();
  }

  public static function home($atts=[]){
    wp_enqueue_style('aztra-app'); wp_enqueue_script('aztra-app');
    ob_start();
    self::render_header(); ?>
    <div class="az-home">
      <p>utilize o webhook teste para testar</p>
      <pre id="aztra-preview">{}</pre>
      <button class="az-btn az-primary" data-aztra-act="open-save-model">Salvar Modelo e iniciar conversa</button>
    </div>
    <?php self::render_footer();
    return ob_get_clean();
  }

  public static function chat($atts=[]){
    if(!is_user_logged_in()){ return '<p>Please log in to use the chat.</p>'; }
    wp_enqueue_style('aztra-app'); wp_enqueue_script('aztra-app');
    ob_start();
    self::render_header(); ?>
    <div class="az-chat-layout">
      <aside class="az-sidebar">
        <button class="az-btn" data-aztra-act="new-chat">Novo chat</button>
        <a class="az-btn" href="<?php echo esc_url( get_permalink( get_page_by_title('Aztra — Gallery') ) ); ?>">Galeria</a>
        <button class="az-btn" data-aztra-act="new-project">Novo Projeto</button>
        <div class="az-label">Projeto Aztra G</div>
        <button class="az-btn" data-aztra-act="user-settings">Configurações de usuário</button>
      </aside>
      <section class="az-chat">
        <div id="aztra-chat-log" class="az-chat-log"></div>
        <div class="az-field"><input id="aztra-chat-file" type="file" multiple></div>
        <div class="az-field"><textarea id="aztra-chat-message" rows="3" placeholder="Digite sua mensagem..."></textarea></div>
        <button class="az-btn az-primary" data-aztra-act="send-chat">Enviar</button>
      </section>
    </div>
    <?php self::render_footer();
    return ob_get_clean();
  }

  private static function replace_placeholders($text){
    $o = get_option('aztra_g_settings', []);
    $search  = ['{company_name}','{contact_email}','{site_name}'];
    $replace = [
      $o['company_name'] ?? get_bloginfo('name'),
      $o['contact_email'] ?? get_bloginfo('admin_email'),
      get_bloginfo('name'),
    ];
    return str_replace($search, $replace, $text);
  }

  public static function privacy($atts=[]){
    wp_enqueue_style('aztra-app');
    $o = get_option('aztra_g_settings', []);
    $text = self::replace_placeholders($o['privacy_template'] ?? '');
    ob_start(); self::render_header(); ?>
    <div class="az-policy az-privacy">
      <?php echo wpautop( esc_html( $text ) ); ?>
    </div>
    <?php self::render_footer(); return ob_get_clean();
  }

  public static function terms($atts=[]){
    wp_enqueue_style('aztra-app');
    $o = get_option('aztra_g_settings', []);
    $text = self::replace_placeholders($o['terms_template'] ?? '');
    ob_start(); self::render_header(); ?>
    <div class="az-policy az-terms">
      <?php echo wpautop( esc_html( $text ) ); ?>
    </div>
    <?php self::render_footer(); return ob_get_clean();
  }

  public static function commands($atts=[]){
    wp_enqueue_style('aztra-app');
    ob_start(); self::render_header(); ?>
    <div class="az-commands">
      <h2>Aztra Commands</h2>
      <p>Customize your app and functions here.</p>
    </div>
    <?php self::render_footer(); return ob_get_clean();
  }
}
