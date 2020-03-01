<?php

namespace Drupal\media_duplicates\Plugin\Validation\Constraint;

use Drupal\media\Entity\Media;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the MediaUniqueChecksum constraint.
 */
class MediaUniqueChecksumValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($entity, Constraint $constraint) {
    if (!$this->isUnique($entity)) {
      $this->context->addViolation($constraint->notUnique, ['%value' => $entity->label()]);
    }
  }

  /**
   * Is unique?
   *
   * @param string $value
   *
   * @return bool
   */
  private function isUnique(Media $entity) {
    $id = $entity->getSource()->getPluginId();
    try {
      /** @var \Drupal\media_duplicates\Plugin\MediaDuplicatesChecksumInterface $checksum_plugin */
      $checksum_plugin = \Drupal::service('plugin.manager.media_duplicates.checksum')->createInstanceForMediaType($id);
      $checksum = $checksum_plugin->getChecksum($entity);

      if ($checksum === NULL) {
        return TRUE;
      }

      /** @var \Drupal\media_duplicates\ChecksumStatistics $statistics */
      $statistics = \Drupal::service('media_duplicates.checksum_statistics');
      return !$statistics->checksumExists($checksum, $entity->id());
    }
    catch (\Exception $e) {}

    return TRUE;
  }

}
