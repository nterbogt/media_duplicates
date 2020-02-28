<?php

namespace Drupal\media_duplicates\Commands;

use Drush\Commands\DrushCommands;
use Drupal\media_duplicates\MediaDuplicatesChecksumBatch;

/**
 * Media duplicates drush commands.
 */
class MediaDuplicatesCommands extends DrushCommands {

  /**
   * Rebuild all the checksums for media revisions.
   *
   * Useful for initial installs on existing sites or if you change the
   * algorithms, or install plugins.
   *
   * @command media-duplicates:refresh-checksums
   *
   * @validate-module-enabled media_duplicates
   */
  public function refreshChecksums() {
    batch_set(MediaDuplicatesChecksumBatch::tasks());
    $batch =& batch_get();
    $batch['progressive'] = TRUE;
    drush_backend_batch_process();
  }

}
