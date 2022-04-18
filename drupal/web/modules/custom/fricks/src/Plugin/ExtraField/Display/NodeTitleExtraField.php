<?php

namespace Drupal\fricks\Plugin\ExtraField\Display;

use Drupal\fricks\Plugin\ExtraField\Display\AbstractNormalizedPropertyExtraField;

/**
 * @ExtraFieldDisplay(
 *   id = "node_title_normalized_extra_field",
 *   label = @Translation("Node Title Normalized Extra field"),
 *   bundles = {
 *     "node.*"
 *   }
 * )
 */
class NodeTitleExtraField extends AbstractNormalizedPropertyExtraField {

  const EXPOSED_PROPERTY = 'title';
}

