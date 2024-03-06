<?php

include 'YS_Moto_Meta.php';
include 'YS_Moto_Cat.php';

class YS_Moto_CPT {
  private string $slug = '';
  private string $menu_icon = '';
  private array $labels_params = [];
  private string $label = '';
  private string $description = '';
  private array $supports = ['title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'];
  private bool $show_in_rest = true;
  private bool $hierarchical = false;
  private bool $public = true;
  private bool $has_archive = true;
  private array $rewrite = [];
  private array $metas = [];
  private array $cats = [];

  public function __construct(string $slug)
  {
    $slug = sanitize_title($slug);
    $this->slug = $slug;
    $this->rewrite = ['slug' => $slug];
  }

  public function prepare_label (string $singular, string $plural, bool $feminin = false) : YS_Moto_CPT {
    $this->label = ucfirst($plural);
    $this->labels_params = [
      'singular' => $singular,
      'plural' => $plural,
      'feminin' => $feminin,
    ];
    return $this;
  }

  private function build_labels () : array
  {
    foreach ($this->labels_params as $name => $value) {
      $$name = $value;
    }
    return [
      'name'               => ucfirst($plural),
      'singular_name'      => ucfirst($singular),
      'add_new'            => sprintf("%s %s.",( ($feminin) ? esc_html__( 'Ajouter une nouvelle', 'ys-moto' ) : esc_html__( 'Ajouter un nouveau', 'ys-moto' ) ), $singular),
      'add_new_item'       => sprintf("%s %s.",( ($feminin) ? esc_html__( 'Ajouter une nouvelle', 'ys-moto' ) : esc_html__( 'Ajouter un nouveau', 'ys-moto' ) ), $singular),
      'edit_item'          => sprintf( '%s %s.', ( ($feminin) ? esc_html__( 'Modifier la', 'ys-moto' ) : esc_html__( 'Modifier le', 'ys-moto' ) ), $singular ),
      'new_item'           => sprintf("%s %s.",( ($feminin) ? esc_html__( 'Nouvelle', 'ys-moto' ) : esc_html__( 'Nouveau', 'ys-moto' ) ), $singular),
      'view_item'          => sprintf("%s %s.",( ($feminin) ? esc_html__( 'Voir la', 'ys-moto' ) : esc_html__( 'Voir le', 'ys-moto' ) ), $singular),
      'search_items'       => sprintf( 'Rechercher des %s.', $plural),
      'not_found'          => sprintf("%s %s %s.",( ($feminin) ? esc_html__( 'Aucune', 'ys-moto' ) : esc_html__( 'Aucun', 'ys-moto' ) ), $singular, (($feminin) ? esc_html__( 'trouvée', 'ys-moto' ) : esc_html__( 'trouvé', 'ys-moto' )) ),
      'not_found_in_trash' => sprintf("%s %s %s dans la corbeille.",( ($feminin) ? esc_html__( 'Aucune', 'ys-moto' ) : esc_html__( 'Aucun', 'ys-moto' ) ), $singular, (($feminin) ? esc_html__( 'trouvée', 'ys-moto' ) : esc_html__( 'trouvé', 'ys-moto' )) ),
      'parent_item_colon'  => sprintf("%s %s :", $singular, ( ($feminin) ? esc_html__( 'parente', 'ys-moto' ) : esc_html__( 'parent', 'ys-moto' ) ) ),
      'menu_name'          => ucfirst($plural)
    ];
  }

  public function set_description ($description): YS_Moto_CPT {
    $this->description = $description;
    return $this;
  }

  public function not_in_api (): YS_Moto_CPT {
    $this->show_in_rest = false;
    return $this;
  }

  public function has_hierarchy (): YS_Moto_CPT {
    $this->hierarchical = true;
    return $this;
  }

  public function not_public (): YS_Moto_CPT {
    $this->public = false;
    return $this;
  }

  public function no_archive (): YS_Moto_CPT {
    $this->has_archive = false;
    return $this;
  }

  public function custom_rewrite (string $new_slug): YS_Moto_CPT {
    $this->rewrite['slug'] = sanitize_title($new_slug);
    return $this;
  }

  public function set_icon (string $icon): YS_Moto_CPT {
    $this->menu_icon = $icon;
    return $this;
  }

  public function set_icon_svg (string $icon): YS_Moto_CPT {
    $this->menu_icon = 'data:image/svg+xml;base64,'.base64_encode(file_get_contents($icon));
    return $this;
  }

  public function set_meta (string $slug, callable $meta_settings): YS_Moto_CPT {
    $meta = new YS_Moto_Meta($slug, $this->slug);
    call_user_func_array($meta_settings, [&$meta]);
    $this->metas[] = $meta;
    return $this;
  }

  public function set_cat (string $slug, callable $cat_settings): YS_Moto_CPT {
    $cat = new YS_Moto_Cat($slug);
    $cat->addPostTypes($this->slug);
    call_user_func_array($cat_settings, [&$cat]);
    $this->cats[] = $cat;
    return $this;
  }

  public function register () {
    
    $args = array(
      'label'               => $this->label,
      'description'         => $this->description,
      'labels'              => $this->build_labels(),
      'supports'            => $this->supports,
      'show_in_rest'        => $this->show_in_rest,
      'hierarchical'        => $this->hierarchical,
      'public'              => $this->public,
      'has_archive'         => $this->has_archive,
      'rewrite'			        => $this->rewrite,
      'menu_icon'			      => $this->menu_icon,
    );
    
    register_post_type( $this->slug, $args );

    foreach ($this->cats as $cat) {
      $cat->register();
    }
    foreach ($this->metas as $meta) {
      $meta->register();
    }

  }
}