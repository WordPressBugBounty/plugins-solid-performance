<?php
/**
 * The template used to generate our htaccess configuration.
 *
 * Start and End tags are added with the modifier.
 *
 * @see \SolidWP\Performance\Cache_Delivery\Htaccess\Modifier
 *
 * @var string $base The RewriteBase to use.
 * @var string $cache_path The relative path from WP_CONTENT_DIR without slashes, e.g. wp-content/cache/solid-performance/page
 * @var string $site_cache_path The absolute path to the cache files, .e.g /app/wp-content/cache/solid-performance/page/$host
 * @var string $extensions_regex The FilesMatch extensions regex for our cache files, e.g. html|gz.
 *
 * @package SolidWP\Performance
 */

declare( strict_types=1 );

$htaccess = <<<RULES
<IfModule mod_expires.c>
	ExpiresActive on
	ExpiresByType text/html "access plus 0 seconds"
</IfModule>

<IfModule mod_headers.c>
    Header set Referrer-Policy "no-referrer-when-downgrade"
</IfModule>

# Avoid recompressing already compressed files
<IfModule mod_setenvif.c>
	SetEnvIfNoCase Request_URI \.gz$ no-gzip
</IfModule>

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase $base

# ----------------------------------
# Default env values
# ----------------------------------
RewriteRule .* - [E=SWPSP_SCHEME:-http]
RewriteRule .* - [E=SWPSP_EXT:html]
RewriteRule .* - [E=SWPSP_DEVICE:]
RewriteRule .* - [E=SWPSP_DEVICE_LABEL:desktop]

# ----------------------------------
# Scheme detection
# ----------------------------------
RewriteCond %{HTTPS} on [OR]
RewriteCond %{SERVER_PORT} ^443$ [OR]
RewriteCond %{HTTP:X-Forwarded-Proto} https
RewriteRule .* - [E=SWPSP_SCHEME:-https]

# ----------------------------------
# Mobile detection
# ----------------------------------
RewriteCond %{HTTP_USER_AGENT} "(android.*mobile|iphone|ipod|windows phone|blackberry|bb10|opera mini|mobile.*safari)" [NC]
RewriteRule .* - [E=SWPSP_DEVICE:-mobile,E=SWPSP_DEVICE_LABEL:mobile]

# ----------------------------------
# Gzip detection
# ----------------------------------
<IfModule mod_deflate.c>
	<IfModule mod_mime.c>
	  AddType text/html .gz
	  AddEncoding gzip .gz
	</IfModule>

	<IfModule mod_filter.c>
	  AddOutputFilterByType DEFLATE text/css text/x-component application/x-javascript application/javascript text/javascript text/x-js text/html text/richtext text/plain text/xsd text/xsl text/xml image/bmp application/java application/msword application/vnd.ms-fontobject application/x-msdownload image/x-icon application/json application/vnd.ms-access video/webm application/vnd.ms-project application/x-font-otf application/vnd.ms-opentype application/vnd.oasis.opendocument.database application/vnd.oasis.opendocument.chart application/vnd.oasis.opendocument.formula application/vnd.oasis.opendocument.graphics application/vnd.oasis.opendocument.presentation application/vnd.oasis.opendocument.spreadsheet application/vnd.oasis.opendocument.text audio/ogg application/pdf application/vnd.ms-powerpoint image/svg+xml application/x-shockwave-flash image/tiff application/x-font-ttf application/vnd.ms-opentype audio/wav application/vnd.ms-write application/font-woff application/font-woff2 application/vnd.ms-excel

	  <IfModule mod_mime.c>
	    # DEFLATE by extension
	    AddOutputFilter DEFLATE js css htm html xml
	  </IfModule>
	</IfModule>

	RewriteCond %{HTTP:Accept-Encoding} gzip
	RewriteRule .* - [E=SWPSP_EXT:gz]
</IfModule>

# Fallback if the extension is gz but a gz file was never created, check for a .html extension
RewriteCond %{ENV:SWPSP_EXT} =gz
RewriteCond "%{DOCUMENT_ROOT}$base$cache_path/%{HTTP_HOST}/%{REQUEST_URI}/index%{ENV:SWPSP_SCHEME}%{ENV:SWPSP_DEVICE}.gz" !-f
RewriteCond "$site_cache_path/%{REQUEST_URI}/index%{ENV:SWPSP_SCHEME}%{ENV:SWPSP_DEVICE}.gz" !-f
RewriteRule .* - [E=SWPSP_EXT:html]

# ----------------------------------
# Serve cache
# ----------------------------------
RewriteCond %{REQUEST_METHOD} GET
RewriteCond %{QUERY_STRING} ^$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{HTTP:Cookie} !(wordpress_logged_in_.+|wp-postpass_|wptouch_switch_toggle|comment_author_|comment_author_email_) [NC]
RewriteCond %{REQUEST_URI} !^(/(?:.+/)?feed(?:/(?:.+/?)?)?$|/(?:.+/)?embed/|/(index.php/)?(.*)wp-json(/.*|$))$ [NC]
RewriteCond "%{DOCUMENT_ROOT}$base$cache_path/%{HTTP_HOST}/%{REQUEST_URI}/index%{ENV:SWPSP_SCHEME}%{ENV:SWPSP_DEVICE}.%{ENV:SWPSP_EXT}" -f [OR]
RewriteCond "$site_cache_path/%{REQUEST_URI}/index%{ENV:SWPSP_SCHEME}%{ENV:SWPSP_DEVICE}.%{ENV:SWPSP_EXT}" -f
RewriteRule .* "$base$cache_path/%{HTTP_HOST}/%{REQUEST_URI}/index%{ENV:SWPSP_SCHEME}%{ENV:SWPSP_DEVICE}.%{ENV:SWPSP_EXT}" [L]
</IfModule>

# ----------------------------------
# Headers when cached file served
# ----------------------------------
<IfModule mod_headers.c>
     <FilesMatch "index-(https|http)(-mobile)?\.($extensions_regex)$">
        FileETag None
        Header unset ETag
        Header unset Pragma
        Header append Cache-Control "public"
        Header append Vary: Accept-Encoding
        Header always set X-Cached-By "Solid Performance (htaccess)"
        Header set X-Cache "HIT (%{SWPSP_DEVICE_LABEL}e)"
    </FilesMatch>
</IfModule>

RULES;

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, StellarWP.XSS.EscapeOutput.OutputNotEscaped
echo apply_filters( 'solidwp/performance/htaccess/rules', $htaccess, $cache_path );
