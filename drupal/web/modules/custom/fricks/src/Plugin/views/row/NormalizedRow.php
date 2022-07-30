<?php

namespace Drupal\fricks\Plugin\views\row;

use Drupal\Core\DependencyInjection\DeprecatedServicePropertyTrait;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\TranslatableInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\views\Entity\Render\EntityTranslationRenderTrait;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\row\RowPluginBase;
use Drupal\views\ResultRow;
use Drupal\views\ViewExecutable;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Plugin which displays entities as raw data.
 *
 * @ingroup views_row_plugins
 *
 * @ViewsRow(
 *   id = "fricks_normalized_entity",
 *   title = @Translation("Normalized entity"),
 *   help = @Translation("Use normalized entity as row data."),
 *   display_types = {"data"}
 * )
 */
class NormalizedRow extends RowPluginBase {

  use EntityTranslationRenderTrait;
  use DeprecatedServicePropertyTrait;

  /**
   * {@inheritdoc}
   */
  protected $deprecatedProperties = ['entityManager' => 'entity.manager'];

  protected $entityType;

  protected $entityTypeManager;

  protected $entityRepository;

  protected $entityDisplayRepository;

  protected $languageManager;

  protected $serializer;

  /**
   * The table the entity is using for storage.
   *
   * @var string
   */
  public $base_table;

  /**
   * The actual field which is used for the entity id.
   *
   * @var string
   */
  public $base_field;

  /**
   * Stores the entity type ID of the result entities.
   *
   * @var string
   */
  protected $entityTypeId;

  private $build;

  /**
   * Constructs a new DataEntityRow object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity manager.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    array $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    LanguageManagerInterface $language_manager,
    EntityRepositoryInterface $entity_repository,
    EntityDisplayRepositoryInterface $entity_display_repository,
    SerializerInterface $serializer
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeManager = $entity_type_manager;
    $this->languageManager = $language_manager;
    $this->entityRepository = $entity_repository;
    $this->entityDisplayRepository = $entity_display_repository;
    $this->serializer = $serializer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('language_manager'),
      $container->get('entity.repository'),
      $container->get('entity_display.repository'),
      $container->get('serializer'),
      );
  }

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = null) {
    parent::init($view, $display, $options);
    $this->entityTypeId = $view->getBaseEntityType()->id();
    $this->entityType = $this->entityTypeManager->getDefinition($this->entityTypeId);
    $this->base_table = $this->entityType->getDataTable() ?: $this->entityType->getBaseTable();
    $this->base_field = $this->entityType->getKey('id');
  }

  /**
   * {@inheritdoc}
   */
  public function preRender($result) {
    /** @var \Drupal\views\ResultRow $row */
    $currentLanguage = $this->languageManager->getCurrentLanguage()->getId();
    foreach ($result as $row) {
      $entity = $row->_entity;

      $context = [
        'lang' => $this->languageManager->getCurrentLanguage()->getId(),
        'viewMode' => $this->view->rowPlugin->options['view_mode']
      ];

      if (
        $entity instanceof TranslatableInterface
        && $entity->hasTranslation($currentLanguage)
      ) {

        $entity = $entity->getTranslation($currentLanguage);
      }

      $this->build[$entity->id()] = $this->serializer->normalize($entity, 'json', $context);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function render($row) {
    $entity_id = $row->_entity->id();
    return $this->build[$entity_id];
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityTypeId() {
    return $this->view->getBaseEntityType()->id();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEntityManager() {
    // This relies on DeprecatedServicePropertyTrait to trigger a deprecation
    // message in case it is accessed.
    return $this->entityManager;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEntityTypeManager() {
    return $this->entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEntityRepository() {
    return $this->entityRepository;
  }

  /**
   * {@inheritdoc}
   */
  protected function getLanguageManager() {
    return $this->languageManager;
  }

  /**
   * {@inheritdoc}
   */
  protected function getView() {
    return $this->view;
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['view_mode'] = ['default' => 'default'];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['view_mode'] = [
      '#type' => 'select',
      '#options' => $this->entityDisplayRepository->getViewModeOptions($this->entityTypeId),
      '#title' => $this->t('View mode'),
      '#default_value' => $this->options['view_mode'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function summaryTitle() {
    $options = $this->entityDisplayRepository->getViewModeOptions($this->entityTypeId);
    if (isset($options[$this->options['view_mode']])) {
      return $options[$this->options['view_mode']];
    }
    else {
      return $this->t('No view mode selected');
    }
  }
}
