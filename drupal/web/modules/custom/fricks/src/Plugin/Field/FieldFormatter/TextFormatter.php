<?php

namespace Drupal\fricks\Plugin\Field\FieldFormatter;

/**
 * Plugin implementation of the 'Normalized long text' formatter.
 *
 * @FieldFormatter(
 *   id = "emwh_normalized_text",
 *   label = @Translation("Normalized long text"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "text_with_summary"
 *   },
 * )
 */
class TextFormatter extends NormalizedFieldFormatter {}
