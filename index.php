<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php do_action('get_header'); ?>
    <div id="app">
      <?php echo view(app('sage.view'), app('sage.data'))->render(); ?>
    </div>
    <div class="overlay z-50 fixed top-0 left-0 right-0 bottom-0 bg-black/70 lg:bg-black/30  transition-all duration-200"></div>
    <?php do_action('get_footer'); ?>
    <?php wp_footer(); ?>
  </body>
</html>
