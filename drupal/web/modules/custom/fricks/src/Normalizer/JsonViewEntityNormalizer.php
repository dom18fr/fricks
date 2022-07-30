<?php

namespace Drupal\fricks\Normalizer;

use Drupal\serialization\Normalizer\ContentEntityNormalizer;
use Drupal\views\ViewEntityInterface;


/**
 * Normalizes/denormalizes Drupal content entities into an array structure.
 */
class JsonViewEntityNormalizer extends ContentEntityNormalizer {

  CONST VIEW_MODE_SEPARATOR = '.';
  CONST DEFAULT_ITEM_PER_PAGE = 100;

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = ViewEntityInterface::class;

  /**
   * @param $entity
   * @param $format
   * @param array $context
   *
   * @return array
   */
  public function normalize($entity, $format = null, array $context = []): array {
    $view = $entity->getExecutable();
    $result = null;
    $displayMode = $context['viewMode'];
    $viewMode = null;

    if ($context['viewMode'] && strpos($context['viewMode'], self::VIEW_MODE_SEPARATOR)) {
      [$displayMode, $viewMode] = explode(self::VIEW_MODE_SEPARATOR, $context['viewMode']);
    }

    if (is_object($view)) {
      // Drupal pager starts at 0
      $view->get_total_rows = true;
      $view->executeDisplay($displayMode);
      $view->setExposedInput($context['query']);

      if ($viewMode) {
        $view->rowPlugin->options['view_mode'] = $viewMode;
      }

      if ($view->getPager()->usePager()) {
        $view->setCurrentPage(((int) ($context['page'] ?? 0)));
        $view->setItemsPerPage($context['itemsPerPage'] ?? $context['itemsPerPage'] ?? self::DEFAULT_ITEM_PER_PAGE);
        $view->execute();
        $result = [
          'page' => $view->getCurrentPage(),
          'totalItems' => intval($view->total_rows),
          'itemsPerPage' => intval($view->getItemsPerPage()),
          'collection' => $view->render(),
        ];
      }
      else {
        $result = $view->render();
      }
    }

    return $result;
  }
}
