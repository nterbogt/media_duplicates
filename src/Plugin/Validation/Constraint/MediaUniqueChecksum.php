<?php

namespace Drupal\media_duplicates\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that the media entity is unique using the checksum algorithms.
 *
 * @Constraint(
 *   id = "MediaUniqueChecksum",
 *   label = @Translation("Media Unique Checksum", context = "Validation"),
 *   type = "media"
 * )
 */
class MediaUniqueChecksum extends Constraint {

  // The message that will be shown if the value is not unique.
  public $notUnique = '%value is not unique';

}