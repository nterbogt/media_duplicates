<?php

namespace Drupal\media_duplicates\Plugin;

use Drupal\media\Entity\Media;

/**
 * The interface for a MediaDuplicatesChecksum plugin.
 *
 * @package Drupal\media_duplicates
 */
interface MediaDuplicatesChecksumInterface {

  /**
   * Build a checksum of the source data to identify uniqueness.
   *
   * @param \Drupal\media\Entity\Media $media
   *   The media entity to build the checksum for.
   *
   * @return string
   *   The SHA256 hash of the data.
   *
   * @see MediaDuplicatesChecksumInterface::getChecksumData()
   */
  public function getChecksum(Media $media);

  /**
   * The source data used to produce the checksum.
   *
   * @param \Drupal\media\Entity\Media $media
   *   The media entity to build the checksum data for.
   *
   * @return string
   *   A string of data from which the hash is created.
   */
  public function getChecksumData(Media $media);

}
