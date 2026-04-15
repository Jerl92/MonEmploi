<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function monemploi_resume_from() {

    if(is_user_logged_in()){
    	
    	    if (isset($_GET['delete_attachment'])) {
	        echo "<p>Attachment ID ".$_GET['delete_attachment']." permanently deleted.</p>";
	    }
    
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
        if ( $attachments )  {
            echo '<h2>Vos documents</h2>';
        
            foreach ( $attachments as $attachment )  {
                // Get the URL of the media file
                $file_url = wp_get_attachment_url( $attachment->ID );
                // Get the title
                $file_title = apply_filters( 'the_title', $attachment->post_title );
        
                echo '<div class="document-attachment-wrapper" style="display: flex;">';
        	        echo '<a href="' . esc_url( $file_url ) . '" style="width: calc(100% - 75px);">' . esc_html( $file_title ) . '</a>';
            		echo '<div class="delete-document-attachment" style="width: 75px">';
	                        echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">';
		                        echo '<input type="hidden" name="attachmentid" value="' . $attachment->ID . '" />';
		                        echo '<input type="hidden" name="action" value="delete_document_attachment" />';
		                        echo '<button type="submit" name="submit" style="padding: 0; margin: 0;">';
		                        	echo '<i class="material-icons">';
		            				echo 'delete';
		            			echo '</i>';
		            		echo '</button>';
	                        echo '</form>';
            		echo '</div>';
                echo '</div>';
            }
            
        }
    
    }

}
add_shortcode('monemploi-resume-from', 'monemploi_resume_from');