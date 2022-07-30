<?php

namespace Drupal\fricks\Plugin\views\display;

use Drupal\Core\Render\RenderContext;
use Drupal\Core\Render\RendererInterface;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The plugin that handles an embed display.
 *
 * @ingroup views_display_plugins
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
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    /** @var \Drupal\Core\Render\RendererInterface $renderer */
    $renderer = $container->get('renderer');
    $instance->setRenderer($renderer);

    return $instance;
  }

  /**
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *
   * @return void
   */
  public function setRenderer(RendererInterface $renderer): void {
    $this->renderer = $renderer;
  }

  /**
   * @return string
   */
  public function getType(): string {

    return 'data';
  }

  /**
   * @return bool
   */
  public function usesExposed(): bool {

    return true;
  }

  /**
   * @return false
   */
  public function displaysExposed(): bool {

    return false;
  }

  /**
   * @return array
   */
  public function render(): array {
    return $this->renderer->executeInRenderContext(new RenderContext(), function () {

      return $this->view->style_plugin->render();
    });
  }

  /**
   * @return array
   */
  #[ArrayShape([
    '#prefix' => "string",
    '#markup' => "false|string",
    '#suffix' => "string"
  ])]
  public function preview(): array {

    return [
      '#prefix' => '<pre>',
      '#markup' => json_encode($this->view->render()),
      '#suffix' => '</pre>',
    ];
  }
}
