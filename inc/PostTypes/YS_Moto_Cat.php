<?php

class YS_Moto_Cat {
  private string $slug = '';
  private array $args = [];
  private array $labels = [];
  private array $contentTypes = [];

  public function __construct (string $slug) 
  {
    $this->slug = $this->verifySlug($slug);
    $this->labels = [
      'name' => $this->slug,
      'singular_name' => $this->slug,
      'search_items' =>  'Search '.$this->slug,
      'all_items' => 'All '.$this->slug,
      'parent_item' => 'Parent '.$this->slug,
      'parent_item_colon' => 'Parent '.$this->slug.':',
      'edit_item' => 'Edit '.$this->slug,
      'update_item' => 'Update '.$this->slug,
      'add_new_item' => 'Add New '.$this->slug,
      'new_item_name' => 'New '.$this->slug.' Name',
      'menu_name' => $this->slug,
    ];
    $this->args = [
      // Hierarchical taxonomy (like categories)
      'hierarchical' => true,
      'show_in_rest' => true,
      // Control the slugs used for this taxonomy
      'rewrite' => [
        'slug' => $this->slug, // This controls the base slug that will display before each term
        'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
      ]
    ];
  }


  /**
   * Met à jour la liste des labels pour la création de la taxonomie
   */
  public function setLabels (array $labels): YS_Moto_Cat 
  {
    $this->labels = array_merge($this->labels, $labels);
    return $this;
  }

  /**
   * Met à jour la liste des arguments pour la création de la taxonomie
   */
  public function setArgs (array $args): YS_Moto_Cat 
  {
    $this->labels = array_merge($this->args, $args);
    return $this;
  }

  /**
   * Ajoute un ou plusieurs post type à la taxonomie créée
   * @param string[] $post_slugs
   */
  public function addPostTypes (string ...$post_slugs): YS_Moto_Cat 
  {
    foreach ($post_slugs as $slug) {
      if (!in_array($slug, $this->contentTypes))
        $this->contentTypes[] = $slug;
    }
    return $this;
  }

  /**
   * Supprime l'aspect hierarchique de la taxonomie
   */
  public function isTag (): YS_Moto_Cat 
  {
    $this->args['hierarchical'] = false;
    return $this;
  }

  /**
   * Récupère le slug de la taxonomie
   */
  public function getSlug (): string 
  {
    return $this->slug;
  }


  /**
   * Helper permettant la génération des labels
   * @param string $singular
   * @param ?string $plural écriture du nom au pluriel, ajoute un s à la fin du singulier par défaut
   * @param string $feminin si le mot est féminin pour ajuster les labels
   */
  public function build_labels (string $singular, ?string $plural = null, bool $feminin = false): YS_Moto_Cat
  {
    $plural = (is_null($plural)) ? $singular.'s' : $plural;
    $this->labels = [
      'name' => $plural,
      'singular_name' => $singular,
      'search_items' =>  esc_html__( 'Rechercher des', 'ys-moto' ).' '.$plural,
      'all_items' => sprintf("%s %s.",( ($feminin) ? esc_html__( 'Toutes les', 'ys-moto' ) : esc_html__( 'Tous les', 'ys-moto' ) ), $singular),
      'parent_item' => sprintf("%s %s :", $singular, ( ($feminin) ? esc_html__( 'parente', 'ys-moto' ) : esc_html__( 'parent', 'ys-moto' ) ) ),
      'parent_item_colon' => sprintf("%s %s :", $singular, ( ($feminin) ? 'parente' : 'parent' ) ),
      'edit_item' => sprintf("%s %s.",( ($feminin) ? esc_html__( 'Modifier la', 'ys-moto' ) : esc_html__( 'Modifier le', 'ys-moto' ) ), $singular),
      'update_item' => sprintf("%s %s.",( ($feminin) ? esc_html__( 'Mettre à jour la', 'ys-moto' ) : esc_html__( 'Mettre à jour le', 'ys-moto' ) ), $singular),
      'add_new_item' => sprintf("%s %s.",( ($feminin) ? esc_html__( 'Ajouter une nouvelle', 'ys-moto' ) : esc_html__( 'Ajouter un nouveau', 'ys-moto' ) ), $singular),
      'new_item_name' => sprintf("%s %s.",esc_html__( 'Nouveau nom de', 'ys-moto' ), $singular),
      'menu_name' => $plural,
      'popular_items' => $plural.' '.esc_html__( 'Populaires', 'ys-moto' ),
      'separate_items_with_commas' => esc_html__( 'Séparer les', 'ys-moto' ).' '.$plural.' '.esc_html__( 'par des virgules.', 'ys-moto' ),
      'add_or_remove_items' => esc_html__( 'Ajouter ou supprimer des', 'ys-moto' ).' '.$plural,
      'choose_from_most_used' => sprintf( esc_html__( 'Choisissez les %s les plus %s', 'ys-moto' ), $plural, ( ($feminin) ? esc_html__( 'utilisées', 'ys-moto' ) : esc_html__( 'utilisés', 'ys-moto' ) )),
      'not_found' => sprintf( '%s %s %s.', ( ($feminin) ? esc_html__( 'Aucune', 'ys-moto' ) : esc_html__( 'Aucun', 'ys-moto' ) ), $singular, ( ($feminin) ? esc_html__( 'trouvée', 'ys-moto' ) : esc_html__( 'trouvé', 'ys-moto' ) ) ),
    ];
    return $this;
  }

  /**
   * Enregistre la taxonomie
   */
  public function register (): void {
    $params = $this->args;
    $params['labels'] = $this->labels;
    // Add new "Locations" taxonomy to Posts
    register_taxonomy($this->slug, $this->contentTypes, $params);
  }

    /**
   * Valide le format du slug du type de contenu
   * @param string text
   * @return string
   */
  private function verifySlug (string $text): string 
  {
    $slugText = sanitize_title($text);
    if ($text !== $slugText) {
      $text = $slugText;
      // trigger_error('Le slug renseigné est invalide, il sera remplacé par : '.$text, E_USER_WARNING);
    }
    return $text;
  }

}