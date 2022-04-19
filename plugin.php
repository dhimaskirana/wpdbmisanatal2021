<?php

/**
 * Plugin Name:       WPDB Misa Natal 2021
 * Plugin URI:        https://gerejapringgolayan.com
 * Description:       Plugin to collect data people who attend the 2021 Christmas Mass at Pringgolayan Church using WPDB
 * Version:           1.0.0
 * Author:            Dhimas Kirana
 * Author URI:        https://www.dhimaskirana.com/
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('PLUGIN_NATAL2021_DIR', plugin_dir_path(__FILE__));
define('PLUGIN_NATAL2021_URL', plugin_dir_url(__FILE__));

/**
 * Create table during plugin activation.
 */
register_activation_hook(__FILE__, function () {
    global $wpdb;

    $table_name = $wpdb->prefix . 'umat_natal2021';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nama_umat text NOT NULL,
        usia_umat text NOT NULL,
        alamat_umat text NOT NULL,
        jenis_kelamin_umat text NOT NULL,
        waktu_masuk datetime DEFAULT NULL,
        waktu_keluar datetime DEFAULT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});

/**
 * Drop table during plugin deactivation.
 */
register_deactivation_hook(__FILE__, function () {
    global $wpdb;

    $table_name = $wpdb->prefix . 'umat_natal2021';

    $sql = "DROP TABLE IF EXISTS $table_name";

    $wpdb->query($sql);
});

/**
 * WP Rest API for perform CRUD
 */
add_action('rest_api_init', function () {
    register_rest_route('natal2021/v1', '/proses', array(
        'methods' => 'POST',
        'callback' => 'umatmasuk',
    ));
    register_rest_route('natal2021/v1', '/keluar', array(
        'methods' => 'POST',
        'callback' => 'umatkeluar',
    ));
});

function umatmasuk(WP_REST_Request $request) {
    global $wpdb;
    $data = $request->get_json_params();
    $data_to_insert = array(
        'nama_umat' => $data['nama_umat'],
        'usia_umat' => $data['usia_umat'],
        'alamat_umat' => $data['alamat_umat'],
        'jenis_kelamin_umat' => $data['jenis_kelamin_umat'],
        'waktu_masuk' => current_time('mysql')
    );
    $wpdb->insert($wpdb->prefix . 'umat_natal2021', $data_to_insert);
    $data_to_insert['id_umat'] = $wpdb->insert_id;
    return $data_to_insert;
}

function umatkeluar(WP_REST_Request $request) {
    global $wpdb;
    $data = $request->get_json_params();
    foreach ($data as $key => $value) {
        $wpdb->update(
            $wpdb->prefix . 'umat_natal2021',
            array(
                'waktu_keluar' => current_time('mysql')
            ),
            array('id' => $key)
        );
    }
    return $data;
}

/**
 * Add form register template to the dropdown list of page templates
 */
add_filter('theme_page_templates', function ($templates) {
    $templates['form-register.php'] = 'Natal 2021 Form Register';
    return $templates;
});

/**
 * If page using form register template, use the template
 */
add_filter('page_template', function ($template) {
    $post = get_post();
    $page_template = get_post_meta($post->ID, '_wp_page_template', true);
    if ('form-register.php' == basename($page_template)) {
        $template = PLUGIN_NATAL2021_DIR . 'inc/form-register.php';
    }
    return $template;
});

/**
 * Shortcode to show data
 * Place shortcode [umat_natal_2021] on post/page
 */
add_action('wp_enqueue_scripts', function () {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'umat_natal_2021')) {
        wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js', array('jquery'));
        wp_enqueue_style('datatables', 'https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css');
    }
});

add_shortcode('umat_natal_2021', function ($atts) {
    global $wpdb;
    $table = $wpdb->prefix . 'umat_natal2021';
    $result =  $wpdb->get_results('SELECT * FROM ' . $table . '', OBJECT);
    ob_start(); ?>
    <table id="dataumat">
        <thead>
            <tr>
                <th>Nama Umat</th>
                <th>Usia Umat</th>
                <th>Alamat Umat</th>
                <th>Jenis Kelamin Umat</th>
                <th>Datang Gereja</th>
                <th>Keluar Gereja</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $value) { ?>
                <tr>
                    <td><?php echo $value->nama_umat; ?></td>
                    <td><?php echo $value->usia_umat; ?></td>
                    <td><?php echo $value->alamat_umat; ?></td>
                    <td><?php echo ucwords($value->jenis_kelamin_umat); ?></td>
                    <td><?php echo date('j F Y H:i:s', strtotime($value->waktu_masuk)); ?></td>
                    <td><?php if (empty($value->waktu_keluar)) {
                            echo 'Belum klik Logout';
                        } else {
                            echo date('j F Y H:i:s', strtotime($value->waktu_keluar));
                        } ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <script>
        jQuery(document).ready(function() {
            jQuery('#dataumat').DataTable({
                "order": [
                    [4, "desc"]
                ]
            });
        });
    </script>

<?php return ob_get_clean();
});
