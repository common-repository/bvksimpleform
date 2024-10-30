<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

delete_option( 'BVK_SIMPLEFORM_VERSION' );
delete_option( 'BVK_SIMPLEFORM_LOGIN' );
delete_option( 'BVK_SIMPLEFORM_EMAIL' );
