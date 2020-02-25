<?php

namespace Drupal\media_duplicates\Plugin;

use Drupal\Component\Utility\Crypt;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\media\Entity\Media;

/**
 * The initial base implementation of the ContentCheck interface.
 *
 * @package Drupal\content_check
 */
abstract class MediaDuplicatesChecksumBase extends PluginBase implements MediaDuplicatesChecksumInterface, ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getChecksum(Media $media) {
    $data = $this->getChecksumData($media);
    if (!empty($data)) {
      return Crypt::hashBase64($data);
    }
    else {
      return NULL;
    }
  }

  /**
   * {@inheritdoc}
   */
  abstract public function getChecksumData(Media $media);

}
