<?php
if (!defined("ABSPATH")) exit;

function sdt_generate_sitemap() {
    $args = [
        "post_type" => "post",
        "post_status" => "publish",
        "posts_per_page" => -1,
        "orderby" => "date",
        "order" => "DESC",
    ];
    $query = new WP_Query($args);

    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_url = get_permalink();
            $last_mod = get_the_modified_time("c");

            $xml .= "  <url>\n";
            $xml .= "    <loc>" . esc_url($post_url) . "</loc>\n";
            $xml .= "    <lastmod>" . esc_html($last_mod) . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }
    }
    wp_reset_postdata();

    $xml .= "</urlset>";

    // Salva o sitemap na raiz do WordPress
    $sitemap_path = ABSPATH . "sitemap-seo-local.xml";
    file_put_contents($sitemap_path, $xml);
}

