<?php
$_siteInfo = array(
  'app_id' => '248695429525999',
  'type' => 'website', // Need to be changed with each type of pages
  'title' => get_bloginfo('name'), // Site Title
  'url' => home_url(), // Permalink
  'image' => get_template_directory_uri() . '/screenshot.jpg?v=' . date('dmY'), // Screenshot, Thumbnail
  'description' => get_bloginfo('description'), // Tagline, Excerpt
  'author' => '', // Change manually
);

if (is_tax() || is_category() || is_tag()) {
  if (!is_category())
    $_siteInfo['url'] = get_term_link(get_query_var('term'), get_query_var('taxonomy'));
  else
    $_siteInfo['url'] = get_category_link(get_query_var('cat'));

  $_siteInfo['title'] = single_term_title('', false) . ' | ' . get_bloginfo('name');
  $_siteInfo['description'] = term_description() ? term_description() : $_siteInfo['description'];
  $_siteInfo['type'] = 'article';
}

if (is_search()) {
  $_siteInfo['title'] = 'Search: ' . esc_html(get_query_var('s')) . ' | ' . get_bloginfo('name');
  $_siteInfo['description'] = 'Search result for "' . esc_html(get_query_var('s')) . '" from ' . get_bloginfo('name');
}

if (is_author()) {
  $authorID = get_query_var('author');
  $authorData = get_userdata($authorID);
  $_siteInfo['title'] = $authorData->display_name . ' @ ' . get_bloginfo('name');
  $_siteInfo['description'] = $authorData->description ? $authorData->description : $_siteInfo['description'];
}

if (is_single() || is_page()) {
  $imageSource = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
  $postDescription = $post->post_excerpt ? $post->post_excerpt : wp_trim_words($post->post_content);
  $_siteInfo['title'] = $post->post_title;
  $_siteInfo['description'] = $postDescription ? strip_tags($postDescription) : $_siteInfo['description'];
  $_siteInfo['image'] = $imageSource ? $imageSource[0] : $_siteInfo['image'];
  $_siteInfo['url'] = get_permalink($post->ID);
  $_siteInfo['type'] = 'article';
  // $_siteInfo['author'] = get_the_author_meta( 'display_name', $post->post_author );
}

if (is_paged()) {
  $_siteInfo['title'] .= ' | ' . __('Trang', TEXT_DOMAIN) . ' ' . get_query_var('paged');
  $_siteInfo['description'] .= ' | ' . __('Trang', TEXT_DOMAIN) . ' ' . get_query_var('paged');
}
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
?>


<!doctype html>
<html lang="vi" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <meta name="author" content="<?php echo str_replace('"', '', $_siteInfo['author']); ?>"/>
    <meta name="description" content="<?php echo str_replace('"', '', $_siteInfo['description']); ?>"/>
    <meta property="fb:app_id" content="<?php echo $_siteInfo['app_id']; ?>"/>
    <meta property="og:type" content='<?php echo $_siteInfo['type']; ?>'/>
    <meta property="og:title" content="<?php echo str_replace('"', '', $_siteInfo['title']); ?>"/>
    <meta property="og:url" content="<?php echo $_siteInfo['url']; ?>"/>
    <meta property="og:image" content="<?php echo $_siteInfo['image']; ?>"/>
    <meta property="og:description" content="<?php echo str_replace('"', '', $_siteInfo['description']); ?>"/>

    <title><?php echo esc_html($_siteInfo['title']); ?></title>

    <link type="image/x-icon" rel="shortcut icon" href="<?php echo IMAGE_URL; ?>/favicon.png">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>


  <?php wp_head(); ?>
</head>
<header>
</header>
Header
<body>

