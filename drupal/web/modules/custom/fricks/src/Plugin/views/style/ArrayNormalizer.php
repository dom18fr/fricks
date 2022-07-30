<?php

namespace Drupal\fricks\Plugin\views\style;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * The style plugin for serialized output formats.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "array_normalizer",
 *   title = @Translation("ArrayNormalizer"),
 *   help = @Translation("Return php array."),
 *   display_types = {"data"}
 * )
 */
class ArrayNormalizer extends StylePluginBase implements CacheableDependencyInterface {

  /**
   * {@inheritdoc}
   */
  protected $usesRowPlugin = true;

  /**
   * {@inheritdoc}
   */
  protected $usesOptions = false;

  /**
   * {@inheritdoc}
   */
  protected $usesGrouping = false;

  /**
   * {@inheritdoc}
   */
  public function render() {
    $rows = [];
    foreach ($this->view->result as $row_index => $row) {
      $rows[] = $this->view->rowPlugin->render($row);
    }

    return $rows;
  }

  /**
   * Gets a list of all available formats that can be requested.
   *
   * This will return the configured formats, or all formats if none have been
   * selected.
   *
   * @return array
   *   An array of formats.
   */
  public function getFormats() {
    return $this->options['formats'];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return Cache::PERMANENT;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return ['request_format'];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return [];
  }

  /**
   * Returns an array of format options
   *
   * @return string[]
   *   An array of format options. Both key and value are the same.
   */
  protected function getFormatOptions() {
    $formats = array_keys($this->formatProviders);
    return array_combine($formats, $formats);
  }

}
