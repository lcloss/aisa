<?php
/** Keep all editorial content, terms and metadata on uninstall. */
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;
delete_option( 'aisa_gtm_id' );
