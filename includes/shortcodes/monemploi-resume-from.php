<?php

function monemploi_resume_from() {

?><form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" enctype="multipart/form-data">
    <!-- Security field -->
    <?php wp_nonce_field( 'media_upload', 'media_upload_nonce' ); ?>
    
    <input type="file" accept=".doc, .docx, .pdf" name="file_upload" id="file_upload" required />
    <input type="hidden" name="action" value="frontend_media_upload">
    <input type="submit" name="submit" value="Télécharger" />
</form>
<?php

// Arguments to query media attachments
$args = array(
    'post_type'      => 'attachment',
    'post_mime_type' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf', 'application/msword'), // Change to the desired MIME type, e.g., 'application/pdf' or 'video'
    'post_status'    => 'inherit',
    'author'         => get_current_user_id(),
    'posts_per_page' => -1, // -1 to get all matching files, or a specific number
    'orderby'        => 'date',
    'order'          => 'DESC'
);

// Get the attachments
$attachments = get_posts( $args );

// Check if any attachments were found
if ( $attachments ) :
    echo '<h2>Vos documents</h2>';
    echo '<ul>';

    foreach ( $attachments as $attachment ) :
        // Get the URL of the media file
        $file_url = wp_get_attachment_url( $attachment->ID );
        // Get the title
        $file_title = apply_filters( 'the_title', $attachment->post_title );

        echo '<div class="document-attachment-wrapper" style="display: flex;">';
	        echo '<a href="' . esc_url( $file_url ) . '" style="width: 85%">' . esc_html( $file_title ) . '</a>';
		echo '<div class="delete-document-attachment" style="width: 15%" data-object-id="' . $attachment->ID . '">';
			echo '<i class="material-icons">';
				echo 'delete';
			echo '</i>';
		echo '</div>';
        echo '</div>';
    endforeach;

    echo '</ul>';
endif;

}
add_shortcode('monemploi-resume-from', 'monemploi_resume_from');