<?php

class YS_Moto_Meta {
  private string $slug = '';
  private $default = null;
  private string $description = '';
  private string $post_type = '';
  private string $type = 'string';
  private bool $single = true;
  private bool $show_in_rest = true;

  public function __construct(string $slug, string $post_type)
  {
    $this->slug = sanitize_title($slug);
    $this->post_type = $post_type;
    $this->description = sprintf(esc_html__('Une donnée meta associée au type de contenu %s.', 'ys-moto'), $post_type);
  }

  /**
   * set type of meta created
   * @param string $type - Description of the meta
   */
  public function set_description (string $description): YS_Moto_Meta
  {
    $this->description = $description;
    return $this;
  }

  /**
   * if meta can have multiple value by content
   */
  public function can_have_multiple (): YS_Moto_Meta
  {
    $this->single = false;
    return $this;
  }

  /**
   * if meta musn't be in json api
   */
  public function not_in_api (): YS_Moto_Meta
  {
    $this->show_in_rest = false;
    return $this;
  }

  /**
   * if meta is a complexe_field, set scheme for api datas
   * exemple : [
   * 'schema' => [
   *   'type'   => 'object'
   *   'properties' => [
   *     '_client_id' => ['type' => 'number']
   *     '_client_postcode' => ['type' => 'string']
   *     '_client_city' => ['type' => 'string']
   *   ]
   *  ]
   * ]
   * @param array $scheme - scheme for api display
   * @link https://make.wordpress.org/core/2019/10/03/wp-5-3-supports-object-and-array-meta-types-in-the-rest-api/
   */
  public function complexe_api (array $scheme): YS_Moto_Meta
  {
    $this->show_in_rest = $scheme;
    return $this;
  }

  /**
   * set type of meta created
   * @param string $type - Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'
   */
  public function set_type (string $type): YS_Moto_Meta
  {
    if (in_array($type, ['string', 'boolean', 'integer', 'number', 'array', 'object'])) {
      $this->type = $type;
    }
    return $this;
  }

  /**
   * set default value of meta created
   * @param string $type - Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'
   */
  public function set_default (string $value): YS_Moto_Meta
  {
    $this->default = $value;
    return $this;
  }

  public function register () 
  {

    $default = ['string' => '', 'boolean' => false, 'integer' => 0, 'number' => 0, 'array' => [], 'object' => []];

    $args = array(
        'type'		      => $this->type, // Validate and sanitize the meta value as a string.
        'default'       => (is_null($this->default)) ? $default[$this->type] : $this->default,
        'description'   => $this->description, // Shown in the schema for the meta key.
        'single'        => $this->single, // Return a single value of the type. Default: false.
        'show_in_rest'  => $this->show_in_rest, // Show in the WP REST API response. Default: false.
    );
    register_post_meta( $this->post_type, $this->slug, $args );

  }
}