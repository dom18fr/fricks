<?php

namespace Drupal\fricks\Plugin\rest\resource;

use Drupal\Component\Plugin\DependentPluginInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\rest\Annotation\RestResource;
use Drupal\rest\Plugin\ResourceBase;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;
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

  protected SerializerInterface $serializer;

  protected EntityTypeManagerInterface $entityTypeManager;

  private RequestStack $requestStack;

  public function __construct(
    array $configuration,
    string $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    SerializerInterface $serializer,
    EntityTypeManagerInterface $entityTypeManager,
    RequestStack $requestStack
  ) {
    parent::__construct(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $serializer_formats,
      $logger
    );
    $this->serializer = $serializer;
    $this->entityTypeManager = $entityTypeManager;
    $this->requestStack = $requestStack;
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ): self
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('serializer'),
      $container->get('entity_type.manager'),
      $container->get('request_stack'),
      );
  }

  public function get(string $entityType, string $identifier, string $viewModeCode): Response
  {
    $content = 'No resource found';
    if ($entity = $this->loadEntity($entityType, $identifier)) {
      $context = [
        'viewMode' => $viewModeCode,
        'page' => $this->requestStack->getCurrentRequest()->get('page'),
        'itemsPerPage' => $this->requestStack->getCurrentRequest()->get('itemsPerPage'),
        'query' => $this->requestStack->getCurrentRequest()->query->all(),
      ];
      $content = $this->serializer
        ->serialize($entity, 'json', $context);
    }

    return new Response($content);
  }

  public function calculateDependencies(): array
  {
    return [];
  }


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
