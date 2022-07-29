<?php

namespace Drupal\fricks\Plugin\Field\FieldFormatter;

/**
 * Plugin implementation of the 'Normalized String' formatter.
 *
 * @FieldFormatter(
 *   id = "emwh_string_formatter",
 *   label = @Translation("Normalized String"),
 *   field_types = {
 *     "string",
 *     "string_long",
 *     "uri",
 *   },
 * )
 */
class StringFormatter extends NormalizedFieldFormatter {}
