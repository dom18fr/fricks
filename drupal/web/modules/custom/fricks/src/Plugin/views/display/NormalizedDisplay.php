<?php

namespace Drupal\fricks\Plugin\views\display;

use Drupal\Core\Render\RenderContext;
use Drupal\Core\Render\RendererInterface;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The plugin that handles an embed display.
 *
 * @ingroup views_display_plugins
 *
 * @todo: Wait until annotations/plugins support access methods.
 * no_ui => !\Drupal::config('views.settings')->get('ui.show.display_embed'),
 *
 * @ViewsDisplay(
 *   id = "fricks_normalized_display",
 *   title = @Translation("Normalized Display"),
 *   help = @Translation("Provide a display which return normalized values"),
 *   uses_menu_links = FALSE
 * )
 */
class NormalizedDisplay extends DisplayPluginBase {

  /**
   * Constructs a RestExport object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteProviderInterface $route_provider
   *   The route provider.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state key value store.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   * @param string[] $authentication_providers
   *   The authentication providers, keyed by ID.
   * @param string[] $serializer_format_providers
   *   The serialization format providers, keyed by format.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RendererInterface $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('renderer'),
      );
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return 'data';
  }

  /**
   * {@inheritdoc}
   */
  public function usesExposed() {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function displaysExposed() {
    return false;
  }

  public function render() {
    //$this->view->get_total_rows = TRUE;
    $build = $this->renderer->executeInRenderContext(new RenderContext(), function () {
      return $this->view->style_plugin->render();
    });

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function preview() {

    $output = [
      '#prefix' => '<pre>',
      '#markup' => json_encode($this->view->render()),
      '#suffix' => '</pre>',
    ];

    return $output;
  }
}
