<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>product_gallery</title>
	<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
	<link href="assets/css/productgallery.css" rel="stylesheet">
	<link href="assets/css/slick.css" rel="stylesheet">
	<link href="assets/css/slick-theme.min.css" rel="stylesheet">
</head>
<body>

<?php

function property_gallery_add_metabox(){
	add_meta_box(
		'post_custom_gallery',
		'Gallery',
		'property_gallery_metabox_callback',
		'products', // Change post type name
		'normal',
		'core'
	);
}
add_action( 'admin_init', 'property_gallery_add_metabox' );

function property_gallery_metabox_callback(){
	wp_nonce_field( basename(__FILE__), 'sample_nonce' );
	global $post;
	$gallery_data = get_post_meta( $post->ID, 'gallery_data', true );
	?>
	<div id="gallery_wrapper">
		<div id="img_box_container">
		<?php 
		if ( isset( $gallery_data['image_url'] ) ){
			for( $i = 0; $i < count( $gallery_data['image_url'] ); $i++ ){
			?>
			<div class="gallery_single_row dolu">
			  <div class="gallery_area image_container ">
				<img class="gallery_img_img" src="<?php esc_html_e( $gallery_data['image_url'][$i] ); ?>" height="55" width="55" onclick="open_media_uploader_image_this(this)"/>
				<input type="hidden"
						 class="meta_image_url"
						 name="gallery[image_url][]"
						 value="<?php esc_html_e( $gallery_data['image_url'][$i] ); ?>"
				  />
			  </div>
			  <div class="gallery_area">
				<span class="button remove" onclick="remove_img(this)" title="Remove"/><i class="fas fa-trash-alt"></i></span>
			  </div>
			  <div class="clear" />
			</div> 
			</div>
			<?php
			}
		}
		?>
		</div>
		<div style="display:none" id="master_box">
			<div class="gallery_single_row">
				<div class="gallery_area image_container" onclick="open_media_uploader_image(this)">
					<input class="meta_image_url" value="" type="hidden" name="gallery[image_url][]" />
				</div> 
				<div class="gallery_area"> 
					<span class="button remove" onclick="remove_img(this)" title="Remove"/><i class="fas fa-trash-alt"></i></span>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div id="add_gallery_single_row">
		  <input class="button add" type="button" value="+" onclick="open_media_uploader_image_plus();" title="Add image"/>
		</div>
	</div>
	<?php
}

function property_gallery_styles_scripts(){
    global $post;
    if( 'products' != $post->post_type )
        return;
    ?>  
    
    <?php
}
add_action( 'admin_head-post.php', 'property_gallery_styles_scripts' );
add_action( 'admin_head-post-new.php', 'property_gallery_styles_scripts' );

function property_gallery_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'sample_nonce' ] ) && wp_verify_nonce( $_POST[ 'sample_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Correct post type
	if ( 'products' != $_POST['post_type'] ) // here you can set the post type name
		return;

	if ( $_POST['gallery'] ){

		// Build array for saving post meta
		$gallery_data = array();
		for ($i = 0; $i < count( $_POST['gallery']['image_url'] ); $i++ ){
			if ( '' != $_POST['gallery']['image_url'][$i]){
				$gallery_data['image_url'][]  = $_POST['gallery']['image_url'][ $i ];
			}
		}

		if ( $gallery_data ) 
			update_post_meta( $post_id, 'gallery_data', $gallery_data );
		else 
			delete_post_meta( $post_id, 'gallery_data' );
	} 
	// Nothing received, all fields are empty, delete option
	else{
		delete_post_meta( $post_id, 'gallery_data' );
	}
}
add_action( 'save_post', 'property_gallery_save' );

function product_gallery(){
	// $args = array( 'post_type' => 'products', 'posts_per_page' => -1);
 //  $loop = new WP_Query( $args );
 //  $for_wrap= '<div class="slider-for">';
 //  while ( $loop->have_posts() ) : $loop->the_post();
 //   $for.='';


  // endwhile;

	$currentPostId=get_the_ID();
	$gallery = get_post_meta($currentPostId ,'gallery_data',true);		
	if(isset($gallery['image_url']) && count($gallery['image_url'])>0){
		$siderFor='<div class="slider-for">';
		$siderNav='<div class="slider-nav">';
			for($i=0;$i<count($gallery['image_url']);$i++){
				$siderFor.='<div><img src="'.$gallery['image_url'][$i].'"></div>';
				$siderNav.='<div><figure><img src="'.$gallery['image_url'][$i].'"></figure></div>';				
			}

			$siderFor.='</div>';
			$siderNav.='</div>';

			return $siderFor.$siderNav;

	
	}
}
add_shortcode('gallery_shortcode','product_gallery');

// create shortcode gallery design
function product_gallery(){


	$currentPostId=get_the_ID();
	$gallery = get_post_meta($currentPostId ,'gallery_data',true);		
	if(isset($gallery['image_url']) && count($gallery['image_url'])>0){
		$siderFor='<div class="slider-for">';
		$siderNav='<div class="slider-nav">';
			for($i=0;$i<count($gallery['image_url']);$i++){
				$siderFor.='<div><img src="'.$gallery['image_url'][$i].'"></div>';
				$siderNav.='<div><figure><img src="'.$gallery['image_url'][$i].'"></figure></div>';				
			}
             
			$siderFor.='</div>';
			$siderNav.='</div>';

			return $siderFor.$siderNav;

	
	}
	wp_reset_query();
	wp_reset_postdata();
}
add_shortcode('gallery_shortcode','product_gallery');

?>

<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js" integrity="sha384-+Ga2s7YBbhOD6nie0DzrZpJes+b2K1xkpKxTFFcx59QmVPaSA8c7pycsNaFwUK6l" crossorigin="anonymous"></script> 
    <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js" integrity="sha384-7ox8Q2yzO/uWircfojVuCQOZl+ZZBg2D2J5nkpLqzH1HY0C1dHlTKIbpRz/LG23c" crossorigin="anonymous"></script>
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script src="assets/js/productgallery.js"></script>
	<script src="assets/js/slick.min.js"></script>
</body>
</html>


	