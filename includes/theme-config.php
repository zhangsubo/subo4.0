<?php
/**
 * Theme Configuration Constants
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Default theme values
 */
define( 'SUBO4_DEFAULT_AUTHOR_NAME', 'SUBO4' );
define( 'SUBO4_DEFAULT_BIO', 'All good things come to those who wait.' );
define( 'SUBO4_DEFAULT_ABOUTME', 'Welcome to my blog. I write about technology, products, and life.' );

/**
 * Theme paths and URIs
 */
define( 'SUBO4_THEME_VERSION', '2.0.0' );
define( 'SUBO4_THEME_DIR', get_template_directory() );
define( 'SUBO4_THEME_URI', get_template_directory_uri() );

/**
 * Highlight.js CDN configuration (loaded on demand when content has <pre>)
 */
define( 'SUBO4_HLJS_VERSION', '11.11.1' );
define( 'SUBO4_HLJS_CSS_CDN', 'https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@' . SUBO4_HLJS_VERSION . '/build/styles/github.min.css' );
define( 'SUBO4_HLJS_JS_CDN', 'https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@' . SUBO4_HLJS_VERSION . '/build/highlight.min.js' );
define( 'SUBO4_HLJS_CSS_SRI', 'sha384-eFTL69TLRZTkNfYZOLM+G04821K1qZao/4QLJbet1pP4tcF+fdXq/9CdqAbWRl/L' );
define( 'SUBO4_HLJS_JS_SRI', 'sha384-RH2xi4eIQ/gjtbs9fUXM68sLSi99C7ZWBRX1vDrVv6GQXRibxXLbwO2NGZB74MbU' );

/**
 * Feature flags
 */
define( 'SUBO4_ENABLE_FOOTNOTES', true );
define( 'SUBO4_FOOTNOTES_CACHE', true );
