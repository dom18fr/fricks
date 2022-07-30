<?php

namespace Drupal\fricks\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceEntityFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Plugin implementation of the 'emwh_embed_entity_reference_formatter'
 * formatter.
 *
 * @FieldFormatter(
 *   id = "emwh_embed_entity_reference_formatter",
 *   label = @Translation("Normalized embed entity reference"),
 *   field_types = {
 *     "entity_reference_revisions",
 *     "entity_reference"
 *   }
 * )
 */
class EmbedEntityReferenceFormatter extends EntityReferenceEntityFormatter {

  /**
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  private SerializerInterface $serializer;

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

    /** @var SerializerInterface $serializer */
    $serializer = $container->get('serializer');
    $instance->setSerializer($serializer);

    return $instance;
  }

  /**
   * @param \Symfony\Component\Serializer\SerializerInterface $serializer
   *
   * @return void
   */
  public function setSerializer(SerializerInterface $serializer): void {
    $this->serializer = $serializer;
  }

  /**
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   * @param $langcode
   *
   * @return array
   * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $elements = [];
    $context = [
      'viewMode' => $this->getSetting('view_mode'),
      'lang' => 'fr',
    ];

    foreach ($items as $delta => $item) {
      $entity = $item->entity;
      $elements[$delta] = $this->serializer
        ->normalize($entity, 'json', $context);
    }

    return $elements;
  }
}
