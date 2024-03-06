<?php

class YS_Motorcycle {
  public static $dir;
  private array $init = [];
  private array $block_cats = [
    [
      'slug'  => 'yann-soaz-motorcycle',
      'title' => 'Moto'
    ]
    ];
  
  public function __construct (string $main_dir)
  {
    self::$dir = $main_dir;
    add_action( 'init', [$this, 'init_plugin']);
    add_filter( 'allowed_block_types_all', [$this, 'ys_motorcycle_allowed_block_types'], 25, 2 );
    add_filter( 'block_categories_all', [$this, 'ys_motorcycle_cats'] );
  
  }

  private function block_dir (): string
  {
    return self::$dir.'/build';
  }

  public function get_dir (string $dir): string
  {
    $directory = self::$dir.'/'.$dir;
    return (is_dir($directory)) ? $directory : null ;
  }

  public function get_file (string $dir, string $file): string
  {
    $directory = self::$dir.'/'.$dir.'/'.$file;
    return (is_file($directory)) ? $directory : null ;
  }

  private function all_blocks_dir (): array
  {
    $block_dir = $this->block_dir();
    $dir_content = scandir($block_dir);
    $blocks = [];
    foreach ($dir_content as $elem) {
      if ($elem === '.' || $elem === '..') 
        continue;
      if (is_dir($block_dir.'/'.$elem)) {
        $blocks[$elem] = $block_dir.'/'.$elem;
      }
    }
    return $blocks;
  }

  public function add_init_action (callable $action): YS_Motorcycle
  {
    $this->init[] = $action;
    return $this;
  }
 
  public function ys_motorcycle_allowed_block_types( $allowed_blocks, $editor_context ) {
      // if( !empty($editor_context->post->post_type) && 'moto' !== $editor_context->post->post_type ) { 
      //   array_diff($allowed_blocks, ['yann-soaz/ys-motorbike-metas']);
      // }
      return $allowed_blocks;
  }


  public function init_plugin (): void
  {
    
    if (!empty($this->init)) {
      foreach ($this->init as $action) {
        call_user_func($action);
      }
    }
    $dynamics = require('block_render.php');
    foreach ($this->all_blocks_dir() as $name => $block) {
      $params = [];
      if (!empty($dynamics[$name])) {
        $params['render_callback'] = $dynamics[$name];
      }
      register_block_type( $block, $params );
    }
  }

  public function ys_motorcycle_cats (array $categories):array
  {
    if (!empty($this->block_cats)) {
      foreach ($this->block_cats as $cat) {
        $categories[] = $cat;
      }
    }
    return $categories;
  }
}