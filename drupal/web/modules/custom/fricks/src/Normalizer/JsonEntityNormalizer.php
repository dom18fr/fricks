<?php

namespace Drupal\fricks\Normalizer;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\extra_field\Plugin\ExtraFieldDisplayManagerInterface;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;
use Drupal\extra_field_plus\Plugin\ExtraFieldPlusDisplayFormattedBase;
use Drupal\Component\Utility\SortArray;

/**
 * Normalizes/denormalizes Drupal content entities into an array structure.
 */
class JsonEntityNormalizer extends ContentEntityNormalizer {

  const FIELD_FORMATTED_API_ENTIY_TYPE = [
    'node',
    'paragraph',
  ];

  /**
   * @var \Drupal\extra_field\Plugin\ExtraFieldDisplayManagerInterface
   */
  protected ExtraFieldDisplayManagerInterface $extraFieldDisplayManager;

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entity_type_repository
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   * @param \Drupal\extra_field\Plugin\ExtraFieldDisplayManagerInterface $extraFieldDisplayManager
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    EntityTypeRepositoryInterface $entity_type_repository,
    EntityFieldManagerInterface $entity_field_manager,
    ExtraFieldDisplayManagerInterface $extraFieldDisplayManager
  ) {
    parent::__construct($entity_type_manager, $entity_type_repository, $entity_field_manager);
    $this->extraFieldDisplayManager = $extraFieldDisplayManager;
  }

  /**
   * @param $entity
   * @param $format
   * @param array $context
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function normalize($entity, $format = null, array $context = []): array {
    $viewMode = $context['viewMode'];
    $attributes = [];
    $entityViewDisplay = EntityViewDisplay::collectRenderDisplay($entity, $viewMode);
    $components = $entityViewDisplay->toArray()['content'];
    uasort($components, [SortArray::class, 'sortByWeightElement']);
    foreach ($components as $name => $options) {
      /** @var FormatterBase $renderer */
      if ($renderer = $entityViewDisplay->getRenderer($name)) {
        $attributes[$name] = $renderer->viewElements($entity->get($name), 'fr');
      }
      else {
        $pluginName = substr($name, strlen('extra_field_') - (strlen($name)));
        /** @var ExtraFieldPlusDisplayFormattedBase $instance */
        $instance = $this->extraFieldDisplayManager->createInstance($pluginName, $options);

        $attributeName = $options['settings']['attribute_name'];
        $attributes[$attributeName] = $instance->viewElements($entity);
      }
    }

    return $attributes;
  }

  /**
   * @param $data
   * @param $format
   *
   * @return bool
   */
  public function supportsNormalization($data, $format = NULL): bool {
    /** @var ContentEntityInterface $data */
    return (
      parent::supportsNormalization($data, $format)
      && in_array($data->getEntityTypeId(), self::FIELD_FORMATTED_API_ENTIY_TYPE)
    );
  }


}
