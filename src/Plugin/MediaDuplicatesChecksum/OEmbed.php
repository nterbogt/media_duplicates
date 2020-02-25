<?php

namespace Drupal\media_duplicates\Plugin\MediaDuplicatesChecksum;

use Drupal\media_duplicates\Plugin\MediaDuplicatesChecksumBase;
use Drupal\media\Entity\Media;

/**
 * OEmbed duplicates checksum.
 *
 * @MediaDuplicatesChecksum(
 *   id = "oembed",
 *   label = @Translation("OEmbed"),
 *   media_types = {"oembed"},
 * )
 */
class OEmbed extends MediaDuplicatesChecksumBase {

  /**
   * {@inheritdoc}
   */
  public function getChecksumData(Media $media) {
    $source = $media->getSource();
    return $source->getSourceFieldValue($media);
  }

}
