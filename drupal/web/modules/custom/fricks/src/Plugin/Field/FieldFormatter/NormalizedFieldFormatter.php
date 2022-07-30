<?php

namespace Drupal\fricks\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Plugin implementation of the 'string' formatter.
 *
 */
abstract class NormalizedFieldFormatter extends FormatterBase {

  /**
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected SerializerInterface $serializer;

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

    return $this->serializer->normalize($items, 'json');
  }
}
