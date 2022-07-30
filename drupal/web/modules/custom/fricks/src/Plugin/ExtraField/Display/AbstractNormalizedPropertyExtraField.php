<?php

namespace Drupal\fricks\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\extra_field_plus\Plugin\ExtraFieldPlusDisplayFormattedBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractNormalizedPropertyExtraField extends ExtraFieldPlusDisplayFormattedBase implements ContainerFactoryPluginInterface {

  const EXPOSED_PROPERTY = null;

  /**
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected SerializerInterface $serializer;

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    mixed $plugin_definition
  ): self {

    $instance = new static($configuration, $plugin_definition, $plugin_id);

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
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *
   * @return array|null
   * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
   */
  public function viewElements(ContentEntityInterface $entity): ?array {
    if (null !== $this::EXPOSED_PROPERTY) {
      $propertyValue = $entity->get($this::EXPOSED_PROPERTY);
      return $this->serializer->normalize($propertyValue, 'json');
    }

    return null;
  }

  public function getSettingsForm() {
    $form = parent::settingsForm();
    $form['attribute_name'] = [
      '#type' => 'textfield',
      '#title' => 'Attribute name',
      '#required' => TRUE,
    ];

    return $form;
  }

  public function defaultFormValues() {
    $values = parent::defaultFormValues();

    $values += [
      'attribute_name' => ''
    ];

    return $values;
  }
}
