<?php

namespace Drupal\media_duplicates\Plugin\MediaDuplicatesChecksum;

use Drupal\media_duplicates\Plugin\MediaDuplicatesChecksumBase;
use Drupal\media\Entity\Media;

/**
 * File duplicates checksum.
 *
 * @MediaDuplicatesChecksum(
 *   id = "file",
 *   label = @Translation("File"),
 *   media_types = {"file", "image", "audio_file", "video_file"},
 * )
 */
class File extends MediaDuplicatesChecksumBase {

  /**
   * {@inheritdoc}
   */
  public function getChecksumData(Media $media) {
    $source = $media->getSource();

    /** @var \Drupal\file\Entity\File $file */
    $file = $media->get($source->configuration['source_field'])->entity;
    return file_get_contents($file->getFileUri());
  }

}
