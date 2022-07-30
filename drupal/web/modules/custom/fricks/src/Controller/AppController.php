<?php

namespace Drupal\fricks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\fricks\Normalizer\EntityViewModeNormalizer;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppController extends ControllerBase {

  /**
   * @var \Drupal\fricks\Normalizer\EntityViewModeNormalizer
   */
  private EntityViewModeNormalizer $entityViewModeNormalizer;

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\fricks\Controller\AppController
   */
  public static function create(ContainerInterface $container): AppController {
    $instance = parent::create($container);
    /** @var \Drupal\fricks\Normalizer\EntityViewModeNormalizer $entityViewModeNormalizer */
    $entityViewModeNormalizer = $container->get('fricks.entity_viewmode_normalizer');
    $instance->setEntityViewModeNormalizer($entityViewModeNormalizer);
    return $instance;
  }

  /**
   * @param \Drupal\fricks\Normalizer\EntityViewModeNormalizer $entityViewModeNormalizer
   *
   * @return void
   */
  public function setEntityViewModeNormalizer(EntityViewModeNormalizer $entityViewModeNormalizer): void {
    $this->entityViewModeNormalizer = $entityViewModeNormalizer;
  }

  /**
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
   */
  #[ArrayShape([
    '#markup' => "string",
    '#attached' => "array"
  ])]
  public function content(): array {
    $nodeStorage = $this->entityTypeManager()->getStorage('node');
    $query = $nodeStorage->getQuery();
    $result = $query->condition('type', 'home')
      ->range(0,1)
      ->execute();
    $homeId = reset($result);
    $home = $nodeStorage->load($homeId);

    return [
      '#markup' => '<div data-react="fricks"></div>',
      '#attached' => [
        'library' => [
          'fricks/app'
        ],
        'drupalSettings' => [
          'fricks' => $this->entityViewModeNormalizer->getNormalized($home, 'api')
        ]
      ]
    ];
  }
}
