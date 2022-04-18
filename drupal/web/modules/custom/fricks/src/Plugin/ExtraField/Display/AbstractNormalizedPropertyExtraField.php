<?php

namespace Drupal\fricks\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\extra_field_plus\Plugin\ExtraFieldPlusDisplayFormattedBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractNormalizedPropertyExtraField
  extends ExtraFieldPlusDisplayFormattedBase
  implements ContainerFactoryPluginInterface
{

  const EXPOSED_PROPERTY = null;

  protected LanguageManagerInterface $language;

  protected SerializerInterface $serializer;

  public function __construct(
    array $configuration,
    string $plugin_id,
    mixed $plugin_definition,
    SerializerInterface $serializer,
    LanguageManagerInterface $languageManager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->serializer = $serializer;
    $this->language = $languageManager;
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    mixed $plugin_definition
  ) {

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('serializer'),
      $container->get('language_manager')
    );
  }

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
