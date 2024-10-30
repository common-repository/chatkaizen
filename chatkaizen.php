<?php
/**
 * @package ChatKaizen 
 * @version 1.0
 */
/*
Plugin Name: ChatKaizen
Plugin URI: http://appkaizen.appspot.com
Text Domain: chatkaizen
Domain Path: /languages
Description: Talk to your web visitors in real time from your Android device! ChatKaizen consists of two components: a chat widget and an Android application. This WordPress plugin installs the widget on your web site. To install the Android application on your device, browse to the plugin website with that device, log in and follow the instructions.
Author: Roberto Iglesias
Version: 1.0
Author URI: http://github.io
*/

add_action('admin_menu', 'chatkaizen_menu');

function register_chatkaizen_settings() {
    register_setting('chatkaizen-settings-group', 'ck_key');
    register_setting('chatkaizen-settings-group', 'ck_language');
    register_setting('chatkaizen-settings-group', 'ck_title');
    register_setting('chatkaizen-settings-group', 'ck_title_on');
    register_setting('chatkaizen-settings-group', 'ck_title_off');
}
function chatkaizen_options() {
    if (!current_user_can('manage_options'))  {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
?>
<div class="wrap">
<?php echo screen_icon('plugins'); ?><h2>ChatKaizen</h2>

<form method="post" action="options.php">
    <?php settings_fields('chatkaizen-settings-group'); ?>
    <?php do_settings_sections('chatkaizen-settings-group'); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><label for="key"><?php _e('User key', 'chatkaizen') ?></label></th>
        <td><input type="text" name="ck_key" value="<?php echo esc_attr(get_option('ck_key')); ?>" /></td>
        <td><?php _e('To obtain a key, visit the <a href="appkaizen.appspot.com">ChatKaizen website</a>') ?></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><label for="language"><?php _e('Language', 'chatkaizen') ?></label></th>
        <td>
            <?php $selected = esc_attr(get_option('ck_language')) ?>
            <select name="ck_language">
                <option value="es" <?php echo $selected == 'es' ? 'selected' : ''?>><?php _e('Spanish', 'chatkaizen') ?></option>
                <option value="en" <?php echo $selected == 'en' ? 'selected' : ''?>><?php _e('English', 'chatkaizen') ?></option>
            </select>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><label for="ck_title_closed"><?php _e('Chat title when closed', 'chatkaizen') ?></label></th>
        <td><input type="text" name="ck_title_closed" value="<?php echo esc_attr(get_option('ck_title_closed')); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><label for="title_on"><?php _e('Title when open and online', 'chatkaizen') ?></label></th>
        <td><input type="text" name="ck_title_on" value="<?php echo esc_attr(get_option('ck_title_on')); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><label for="title_off"><?php _e('Title when open but offline', 'chatkaizen') ?></label></th>
        <td><input type="text" name="ck_title_off" value="<?php echo esc_attr(get_option('ck_title_off')); ?>" /></td>
        </tr>

    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php
}
function chatkaizen_menu() {
    add_options_page('ChatKaizen Options', 'ChatKaizen', 'manage_options', 'chatkaizen_options', 'chatkaizen_options');
    add_action('admin_init', 'register_chatkaizen_settings');
}

add_action('plugins_loaded', 'load_chatkaizen_textdomain');
function load_chatkaizen_textdomain() {
  load_plugin_textdomain('chatkaizen', false, dirname(plugin_basename(__FILE__)) . '/languages'); 
}
// Add settings link on plugin page
function chatkaizen_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=chatkaizen_options">'. __('Settings') .'</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'chatkaizen_settings_link');

if (!is_admin()) {
    $key = esc_attr(get_option('ck_key'));
    $title_cl = esc_attr(get_option('ck_title_closed'));
    $lang = esc_attr(get_option('ck_language'));
    $title_on = esc_attr(get_option('ck_title_on'));
    $title_off = esc_attr(get_option('ck_title_off'));
    wp_enqueue_script('chatkaizen_script', "http://appkaizen.herokuapp.com/appkaizen.js?key=$key&title_closed=$title_cl&lang=$lang&title_on=$title_on&title_off=$title_off", array(), '1.0', true);
}
