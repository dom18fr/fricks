<?php

namespace Drupal\fricks\Plugin\rest\resource;

use Drupal\Component\Plugin\DependentPluginInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\fricks\Normalizer\EntityViewModeNormalizer;
use Drupal\rest\Plugin\ResourceBase;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Represents EntityViewModeResource records as resources.
 *
 * @RestResource (
 *   id = "fricks_entity_view_mode_resource",
 *   label = @Translation("Entity View Mode Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/entity/{entityType}/{identifier}/{viewModeCode}",
 *   }
 * )
 *
 */
class EntityViewModeResource extends ResourceBase implements DependentPluginInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private EntityTypeManagerInterface $entityTypeManager;

  /**
   * @var \Drupal\fricks\Normalizer\EntityViewModeNormalizer
   */
  private EntityViewModeNormalizer $entityViewModeNormalizer;

  /**
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  private RequestStack $requestStack;

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   *
   * @return static
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ): self {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);

    /** @var EntityTypeManagerInterface $entityTypeManager */
    $entityTypeManager = $container->get('entity_type.manager');
    $instance->setEntityTypeManager($entityTypeManager);

    /** @var EntityViewModeNormalizer $entityViewModeNormalizer */
    $entityViewModeNormalizer = $container->get('fricks.entity_viewmode_normalizer');
    $instance->setEntityViewModeNormalizer($entityViewModeNormalizer);

    /** @var RequestStack $requestStack */
    $requestStack = $container->get('request_stack');
    $instance->setRequestStack($requestStack);

    return $instance;
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *
   * @return void
   */
  public function setEntityTypeManager(EntityTypeManagerInterface $entityTypeManager): void {
    $this->entityTypeManager = $entityTypeManager;
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
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *
   * @return void
   */
  public function setRequestStack(RequestStack $requestStack): void {
    $this->requestStack = $requestStack;
  }

  /**
   * @param string $entityType
   * @param string $identifier
   * @param string $viewModeCode
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
   */
  public function get(string $entityType, string $identifier, string $viewModeCode): Response {
    $content = 'No resource found';
    if ($entity = $this->loadEntity($entityType, $identifier)) {
      $options = [
        'viewMode' => $viewModeCode,
        'page' => $this->requestStack->getCurrentRequest()->get('page'),
        'itemsPerPage' => $this->requestStack->getCurrentRequest()->get('itemsPerPage'),
        'query' => $this->requestStack->getCurrentRequest()->query->all()
      ];
      $content = $this->entityViewModeNormalizer->getNormalized($entity, $viewModeCode, $options);
    }

    return new Response(json_encode($content));
  }

  public function calculateDependencies(): array
  {
    return [];
  }

  /**
   * @param string $entityType
   * @param string $id
   *
   * @return \Drupal\Core\Entity\EntityInterface
   */
  protected function loadEntity(string $entityType, string $id): EntityInterface
  {
    try {
      $entity = $this->entityTypeManager->getStorage($entityType)->load($id);
    } catch (Exception $exception) {
      throw new BadRequestHttpException($exception->getMessage());
    }
    if (!$entity) {
      throw new NotFoundHttpException('The entity was not found.');
    }

    return $entity;
  }
}
