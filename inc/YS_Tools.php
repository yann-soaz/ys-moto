<?php

class YS_Tools {
  
  static function isAPI () {
    $bIsRest = false;
    if ( function_exists( 'rest_url' ) && !empty( $_SERVER[ 'REQUEST_URI' ] ) ) {
        $sRestUrlBase = get_rest_url( get_current_blog_id(), '/' );
        $sRestPath = trim( parse_url( $sRestUrlBase, PHP_URL_PATH ), '/' );
        $sRequestPath = trim( $_SERVER[ 'REQUEST_URI' ], '/' );
        $bIsRest = ( strpos( $sRequestPath, $sRestPath ) === 0 );
    }
    return $bIsRest;  
  }


}