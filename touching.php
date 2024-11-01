<?php
/*
Plugin Name: Touching Comments
Plugin URI: https://www.weisay.com/blog/wordpress-plugin-touching-comments.html
Description: Touching Comments or selected comments. Every comment, a story to behold! First mark the touching comment in the comments, and then create a new independent page and add shortcodes <code>[touching_comments]</code> to display these touching comments.
Version: 1.1.6
Author: Weisay
Author URI: https://www.weisay.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//评论中插入走心评论按钮
if ( !function_exists('touching_comments_button') ) :
	function touching_comments_button( $comment_text, $comment = null)
	{
		$floor_allow = get_option('touching_comments_floor_allow');
		if ( $comment === null )
			return $comment_text;
		if ( current_user_can('level_10') && ( ( $floor_allow == 1 ) || ($comment->comment_parent == 0) ) ) {
			$comment_text .='<span class="touching-comments-button"><a class="karma-link" data-karma="'. esc_attr($comment->comment_karma) .'" href="'. esc_url(wp_nonce_url( site_url('/comment-karma'), 'KARMA_NONCE' )) .' " onclick="return post_karma('. $comment->comment_ID .', this.href, this)">';
			if ( $comment->comment_karma == 0 ) {
				$comment_text .= '
			<span title="' . esc_attr__('Join the Touching Comments','touching-comments') . '"><svg t="1691142362631" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3461" width="18" height="18" ><path d="M709.56577067 110.4732032c-96.8271424 0-166.18710933 87.25008853-196.0785664 133.39655893-29.9242016-46.1464704-99.2525152-133.39655893-196.07747414-133.39655893-138.9415136 0-251.94071467 125.20683413-251.94071466 279.09134827 0 71.95780053 48.8076128 175.11579733 108.0556768 229.1037952 81.95836693 105.30066347 312.36872 294.85954133 340.81281173 294.85954133 28.94728533 0 254.41302293-185.87497493 337.85259093-293.59773653 60.28719787-54.93435093 109.33167147-158.2342464 109.33167147-230.3656C961.52176747 235.6789472 848.50401067 110.4732032 709.56577067 110.4732032M902.11434027 389.56455147c0 57.54855787-41.73561173 143.42877973-91.125008 187.5253632-1.35349333 1.2301504-2.58255147 2.58364373-3.81161067 4.06593706-73.42262933 95.66248427-221.2448032 214.31688427-292.6830368 266.2877408C461.38864 808.5743296 301.43851307 687.4618112 219.3229664 580.77818347c-1.1024416-1.44954773-2.39371733-2.80522347-3.74721067-4.06593707-49.2027456-44.03436693-90.71568533-129.69410027-90.71568533-187.14769493 0-121.14308053 86.3670432-219.71666667 192.5496608-219.71666667 68.4452672 0 134.3407296 74.08409387 169.27394667 147.5383776 4.6291648 9.7331424 14.8982464 15.7954816 26.80461866 15.7954816s22.17436267-6.0634304 26.83518187-15.7954816c34.90156373-73.45428373 100.76427947-147.5383776 169.24338453-147.5383776C815.7451136 169.8478848 902.11434027 268.42147093 902.11434027 389.56455147" fill="#d81e06" p-id="3462"></path></svg></span></a></span>';
			}
			else {
			$comment_text .= '
			<span title="' . esc_attr__('Cancel the Touching Comments','touching-comments') . '"><svg t="1691141971354" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3103" width="18" height="18" ><path d="M709.56577067 110.4732032c-96.8271424 0-166.18710933 87.25008853-196.0785664 133.39655893-29.9242016-46.1464704-99.2525152-133.39655893-196.07747414-133.39655893-138.9415136 0-251.94071467 125.20683413-251.94071466 279.09134827 0 71.95780053 48.8076128 175.11579733 108.0556768 229.1037952 81.95836693 105.30066347 312.36872 294.85954133 340.81281173 294.85954133 28.94728533 0 254.41302293-185.87497493 337.85259093-293.59773653 60.28719787-54.93435093 109.33167147-158.2342464 109.33167147-230.3656C961.52176747 235.6789472 848.50401067 110.4732032 709.56577067 110.4732032" fill="#d81e06" p-id="3104"></path></svg></span></a></span>';
			}
		}
		return $comment_text;
	}
endif;
add_filter('comment_text', 'touching_comments_button', 10, 2);

//评论中展示“入选走心评论”的标识
function touching_comments_show( $comment_text, $comment = null ) {
	if ( $comment === null )
		return $comment_text;

	if( $comment->comment_karma == 1 ) {
		$comment_text = '<div class="touching-comments-chosen"><a href="' . esc_attr(get_option('touching_comments_page_url')) . '" target="_blank"><span>' . esc_html__('Selected Touching Comments','touching-comments') . '</span></a></div>' . $comment_text;
	}
	return $comment_text;
}
add_filter( 'comment_text' , 'touching_comments_show', 10, 2);

//head中插入css和js
function touching_comments_css_js() {
	if ( current_user_can('level_10') && is_singular() ) {
	wp_enqueue_script( 'touching-comments-js', plugin_dir_url( __FILE__ ) . 'js/touching.js', array(), '1.1.6', false );
	}
	if ( is_singular() ) {
	wp_enqueue_style( 'touching-comments-style', plugin_dir_url( __FILE__ ) . 'css/touching.css', array(), '1.1.6', 'all' );
	wp_add_inline_style('touching-comments-style', get_option('touching_comments_custom_css'));
	}
}
add_action('wp_enqueue_scripts', 'touching_comments_css_js');

//后台插入css和js
function touching_comments_admin_css_js() {
$button_allow = get_option('touching_comments_button_allow');
if ( empty($button_allow) || $button_allow === 0 ) {
	wp_enqueue_style( 'touching-comments-no-button', plugin_dir_url( __FILE__ ) . 'css/tc-admin-no-button.css', array(), '1.1.6', 'all' );
} else {
	wp_enqueue_script( 'touching-comments-js', plugin_dir_url( __FILE__ ) . 'js/touching.js', array(), '1.1.6', false );
	wp_enqueue_style( 'touching-comments-button', plugin_dir_url( __FILE__ ) . 'css/tc-admin-button.css', array(), '1.1.6', 'all' );
	}
}
add_action('admin_enqueue_scripts', 'touching_comments_admin_css_js');

//新建页面简码[touching_comments]来展示走心评论列表
add_shortcode('touching_comments', 'touching_comments_shortcode');
function touching_comments_shortcode() {
	if ( ! defined("REST_REQUEST") ) {
	global $wpdb;
	$counts = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_karma = '1' and comment_approved = '1'");
	$karmamun = count($counts);
	$per_page = get_option('touching_comments_number');
	$img_allow = get_option('touching_comments_img_allow');
?>
<?php if ( empty($img_allow) || $img_allow === 0 ): ?>
<div class="touching-comments-picture">
<?php
$current_locale = get_locale();
if ( $current_locale == 'zh_CN' || $current_locale == 'zh_TW' || $current_locale == 'zh_HK' ) : ?>
<img src="<?php echo esc_url(plugin_dir_url( __FILE__ )) ; ?>images/cn/img<?php echo esc_attr(wp_rand(1,3)) ?>.jpg" alt="<?php esc_html_e( 'Every comment, a story to behold!', 'touching-comments' ); ?>">
<?php else: ?>
<img src="<?php echo esc_url(plugin_dir_url( __FILE__ )) ; ?>images/img<?php echo esc_attr(wp_rand(1,3)) ?>.jpg" alt="<?php esc_html_e( 'Every comment, a story to behold!', 'touching-comments' ); ?>">
<?php endif; ?>
</div>
<?php endif; ?>
<span class="touching-comments-title" id="comments"><?php esc_html_e( 'Currently', 'touching-comments' ); ?> <?php	echo esc_html($karmamun); ?> <?php esc_html_e( 'touching comments have been selected', 'touching-comments' ); ?></span>
	<ol class="comment-list touching-comments-list">
	<?php
	$comments = get_comments(array(
	'karma' => '1',
	'status' => 'approve',
	'order' => 'desc',
	));
	if ( empty($per_page) || $per_page === 0 ){
		$per_page =20;
	}
	wp_list_comments(array(
	'max_depth' => -1,
	'type' => 'comment',
	'callback' => 'touching_comments_list',
	'end-callback' => 'touching_comments_end_list',
	'per_page' => (int)$per_page,
	'reverse_top_level' => false
	),$comments);
	?>
	</ol>
	<div class="navigation pagination">
	<?php
	if ( empty($per_page) || $per_page === 0 ){
		$per_page = 20;
	}
	$pagemun = ceil($karmamun / $per_page);
	$max_page = $pagemun;
	paginate_comments_links(array('total'=> $max_page));
	?></div>
<?php
	}
}

//走心评论
function touching_comments_list( $comment ) {
	$cpage = get_page_of_comment( $comment->comment_ID, $args = array() );
?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-body">
		<div class="comment-meta">
		<div class="comment-author vcard"><?php echo get_avatar( $comment, 48, '', get_comment_author() ); ?></div>
		<b class="fn comment-name"><?php comment_author_link() ?></b><span class="edit-link"><?php edit_comment_link( esc_html__( 'Edit' ), ' ' ); ?></span>
		</div>
		<div class="comment-content">
		<?php comment_text(); ?>
		</div>
		<div class="comment-metadata"><?php comment_date('Y-m-d') ?> <?php esc_html_e( 'Commented on', 'touching-comments' ); ?>&nbsp;&nbsp;•&nbsp;&nbsp;<a href="<?php echo esc_url(get_comment_link($comment->comment_ID, $cpage)); ?>" target="_blank"><?php echo esc_html(get_the_title($comment->comment_post_ID)); ?></a></div>
	</div>
<?php
}
function touching_comments_end_list() {
	echo '</li>';
}

/**
 * 处理走心评论
 * POST /comment-karma
 * 提交三个参数
 *  comment_karma: 0 或者 1
 *  comment_id: 评论ID
 *  _wpnonce: 避免意外提交
 */
function touching_comments_karma_request() {
	// Check if we're on the correct url
	global $wp;
	$current_slug = add_query_arg( array(), $wp->request );
	if($current_slug !== 'comment-karma') {
		return false;
	}

	global $wp_query;
	if ($wp_query->is_404) {
		$wp_query->is_404 = false;
	}

	header('Cache-Control: no-cache, must-revalidate');
	header('Content-type: application/json; charset=utf-8');

	$result = array(
		'code'=> 403,
		'message'=> 'Login required.'
	);

	if (!is_user_logged_in() || !current_user_can('level_10')) {
		header("HTTP/1.1 403 Forbidden");
		die(json_encode($result));
	}

	if (empty($_SERVER['REQUEST_METHOD']) ||
		strtoupper(sanitize_text_field($_SERVER['REQUEST_METHOD'])) !== 'POST' ||
		empty($_SERVER['HTTP_X_REQUESTED_WITH']) ||
		strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
		$result['message'] = 'Request method not allowed';
		header("HTTP/1.1 403 Forbidden");
		die( json_encode($result) );
	}

	// Check if it's a valid request.
	$nonce = filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING);
	if ( $nonce===false || ! wp_verify_nonce( $nonce,  'KARMA_NONCE')) {
		$result['message'] = 'Security Check';
		header("HTTP/1.1 403 Forbidden");
		die( json_encode($result) );
	}

	if (empty($_POST['comment_id'])) {
		$result['code'] = 501;
		$result['message'] = 'Incorrect parameter';
		header("HTTP/1.1 500 Internal Server Error");
		die( json_encode($result) );
	}

	// Do your stuff here
	$comment_karma = empty( $_POST['comment_karma'] ) ? '0' : filter_input(INPUT_POST, 'comment_karma', FILTER_SANITIZE_NUMBER_INT);
	$comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
	if ($comment_karma === false ||
		$comment_id === false ||
		!is_numeric($comment_karma) ||
		!is_numeric($comment_id)) {
		$result['code'] = 501;
		$result['message'] = 'Incorrect parameter';
		header("HTTP/1.1 500 Internal Server Error");
		die( json_encode($result) );
	}

	// update database
	$comment_data = array();
	$comment_data['comment_ID'] = intval($comment_id);
	$comment_data['comment_karma'] = intval($comment_karma);

	if (wp_update_comment( $comment_data )) {
		$result['code'] = 200;
		$result['message'] = 'ok';
		header("HTTP/1.1 200 OK");
	} else {
		$result['code'] = 502;
		$result['message'] = 'comment update failed';
		header("HTTP/1.1 500 Internal Server Error");
	}

	exit(json_encode($result));
}

add_action( 'template_redirect', 'touching_comments_karma_request', 0);

//后台设置页面
add_action('admin_menu', 'touching_comments_add_menu');
function touching_comments_add_menu() {
	add_options_page(esc_html__('Touching Comments Options','touching-comments'), esc_html__('Touching Comments','touching-comments'), 'edit_themes', 'touching_comments_setings', 'touching_comments_function', null, 999);
}

$plugin_basename = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_basename", 'touching_comments_add_setings');
function touching_comments_add_setings( $links )
{
	$settings_link = '<a href="options-general.php?page=touching_comments_setings">' . esc_html__('Settings','touching-comments') . '</a>';
	array_unshift($links, $settings_link);
	return $links;
}

function touching_comments_function() {
	$update = false;

	if (! empty( $_POST )) {
		if ( check_admin_referer('touching_comments_update_settings') ) {
			update_option('touching_comments_number', filter_input(INPUT_POST, 'touching_comments_number', FILTER_SANITIZE_NUMBER_INT));
			update_option('touching_comments_page_url', trim(sanitize_url($_POST['touching_comments_page_url'])));
			update_option('touching_comments_img_allow', filter_input(INPUT_POST, 'touching_comments_img_allow', FILTER_SANITIZE_NUMBER_INT));
			update_option('touching_comments_floor_allow', filter_input(INPUT_POST, 'touching_comments_floor_allow', FILTER_SANITIZE_NUMBER_INT));
			update_option('touching_comments_button_allow', filter_input(INPUT_POST, 'touching_comments_button_allow', FILTER_SANITIZE_NUMBER_INT));
			update_option('touching_comments_custom_css', trim(sanitize_textarea_field($_POST['touching_comments_custom_css'])));
			$update = true;
		}
	}

	$selected_img_options = array(
		'img_allow' => get_option('touching_comments_img_allow')
	);
	$selected_floor_options = array(
		'floor_allow' => get_option('touching_comments_floor_allow')
	);
	$selected_button_options = array(
		'button_allow' => get_option('touching_comments_button_allow')
	);
?>
<div class="wrap">
<?php if ( $update ) {
	echo '<div class="notice notice-success settings-error is-dismissible"><p><strong>' . esc_html__('Settings saved.','touching-comments') . '</strong></p></div>';
}
?>
<h1><?php esc_html_e( 'Touching Comments Options', 'touching-comments' ); ?></h1>
<h2><?php esc_html_e( 'Plugin Usage', 'touching-comments' ); ?></h3>
<p>1. <?php esc_html_e( 'Install the plugin, and then you can see the love icon in the comment section of the article, click to add the touching comments;', 'touching-comments' ); ?></p>
<p>2. <?php esc_html_e( 'Create a new independent page, and input shortcodes', 'touching-comments' ); ?> <code>[touching_comments]</code> <?php esc_html_e( 'in the content area to display these selected touching comments.', 'touching-comments' ); ?></p>
<form method="post" name="touching_comments_seting" id="touching_comments_seting">
<?php wp_nonce_field('touching_comments_update_settings'); ?>
<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><label for="touching_comments_number"><?php esc_html_e( 'Comments per Page', 'touching-comments' ); ?></label></th>
	<td>
		<input name="touching_comments_number" type="number" id="touching_comments_number" value="<?php echo esc_attr(get_option('touching_comments_number')); ?>" class="regular-text" />
		<p class="description"><?php esc_html_e( 'Fill in the number, and if it is not filled in, the default will be displayed as 20.', 'touching-comments' ); ?></p>
	</td>
	</tr>
	<tr>
	<th scope="row"><label for="touching_comments_page_url"><?php esc_html_e( 'Page Address (URL)', 'touching-comments' ); ?></label></th>
	<td>
		<input name="touching_comments_page_url" type="url" id="touching_comments_page_url" value="<?php echo esc_attr(get_option('touching_comments_page_url')); ?>" class="regular-text" />
		<p class="description"><?php esc_html_e( 'The selected touching comments will be marked above and can be set with the url.', 'touching-comments' ); ?></p>
	</td>
	</tr>
	<tr>
		<th scope="row"><label for="touching_comments_img_allow"><?php esc_html_e( 'Display Top Image', 'touching-comments' ); ?></label></th>
		<td valign="top">
			<select name="touching_comments_img_allow" id="touching_comments_img_allow">
				<option value="0"<?php echo esc_attr(selected( '0', $selected_img_options['img_allow'] )); ?>><?php esc_html_e( 'Yes', 'touching-comments' ); ?></option>
				<option value="1"<?php echo esc_attr(selected( '1', $selected_img_options['img_allow'] )); ?>><?php esc_html_e( 'No', 'touching-comments' ); ?></option>
			</select>
			<p class="description"><?php esc_html_e( 'Select whether to display a random image above the touching comments, default display.', 'touching-comments' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="touching_comments_floor_allow"><?php esc_html_e( 'Comments levels deep', 'touching-comments' ); ?></label></th>
		<td valign="top">
			<select name="touching_comments_floor_allow" id="touching_comments_floor_allow">
				<option value="0"<?php echo esc_attr(selected( '0', $selected_floor_options['floor_allow'] )); ?>><?php esc_html_e( 'The first-level deep', 'touching-comments' ); ?></option>
				<option value="1"<?php echo esc_attr(selected( '1', $selected_floor_options['floor_allow'] )); ?>><?php esc_html_e( 'All levels deep', 'touching-comments' ); ?></option>
			</select>
			<p class="description"><?php esc_html_e( 'Choose at what level to display the love icon for interaction, by default, it is displayed only in the first-level comments.', 'touching-comments' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="touching_comments_button_allow"><?php esc_html_e( 'Backend comments quick operations', 'touching-comments' ); ?></label></th>
		<td valign="top">
			<select name="touching_comments_button_allow" id="touching_comments_button_allow">
				<option value="0"<?php echo esc_attr(selected( '0', $selected_button_options['button_allow'] )); ?>><?php esc_html_e( 'No', 'touching-comments' ); ?></option>
				<option value="1"<?php echo esc_attr(selected( '1', $selected_button_options['button_allow'] )); ?>><?php esc_html_e( 'Yes', 'touching-comments' ); ?></option>
			</select>
			<p class="description"><?php esc_html_e( 'Choose whether to click the love icon directly on the backend comments page to add a touching comment.', 'touching-comments' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="touching_comments_custom_css"><?php esc_html_e( 'Additional CSS', 'touching-comments' ); ?></label></th>
		<td>
			<textarea name="touching_comments_custom_css" id="touching_comments_custom_css" rows="6" cols="50" class="large-text code"><?php echo esc_attr(get_option('touching_comments_custom_css')); ?></textarea>
			<p class="description"><?php esc_html_e( 'Custom CSS area, the filled CSS will be displayed at the top of the page, without adding &lt;style&gt; before and after.', 'touching-comments' ); ?></p>
		</td>
	</tr>
</tbody>
</table>
<?php submit_button(); ?>
</form>
</div>
<?php }