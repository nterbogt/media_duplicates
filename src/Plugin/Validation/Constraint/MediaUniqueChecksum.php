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

  /**
   * The message that will be displayed if the media entity fails validation.
   *
   * @var string
   */
  public $notUnique = 'This %value already exists as a media entity in the system';

}
