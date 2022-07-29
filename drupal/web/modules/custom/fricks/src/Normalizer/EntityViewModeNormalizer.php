<?php

namespace Drupal\fricks\Normalizer;

use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EntityViewModeNormalizer {

  /**
   * @var \Symfony\Component\Serializer\Normalizer\NormalizerInterface
   */
  private NormalizerInterface $normalizer;

  /**
   * @param \Symfony\Component\Serializer\Normalizer\NormalizerInterface $normalizer
   */
  public function __construct(NormalizerInterface $normalizer) {
    $this->normalizer = $normalizer;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param string $viewModeCode
   * @param array $options
   *
   * @return array
   * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
   */
  public function getNormalized(EntityInterface $entity, string $viewModeCode, array $options = []): array {
    $context = [
      'viewMode' => $viewModeCode,
      'page' => array_key_exists('page', $options) ? $options['page'] : null,
      'itemsPerPage' => array_key_exists('itemsPerPage', $options) ? $options['itemsPerPage'] : null,
      'query' => array_key_exists('query', $options) ? $options['query'] : null,
    ];

    return $this->normalizer->normalize($entity, 'json', $context);
  }
}
