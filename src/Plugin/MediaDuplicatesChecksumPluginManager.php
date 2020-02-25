<?php

namespace Drupal\media_duplicates\Plugin;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\media_duplicates\Annotation\MediaDuplicatesChecksum;

/**
 * Manages content check plugins.
 */
class MediaDuplicatesChecksumPluginManager extends DefaultPluginManager {

  /**
   * Constructs a new MediaDuplicatesChecksumPluginManager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/MediaDuplicatesChecksum', $namespaces, $module_handler, MediaDuplicatesChecksumInterface::class, MediaDuplicatesChecksum::class);

    $this->alterInfo('media_duplicates_checksum_info');
    $this->setCacheBackend($cache_backend, 'media_duplicates_checksum_plugins');
  }

  /**
   * Get the plugin definition for a media type.
   *
   * @param string $type
   *   The type of media to get a definition for.
   *
   * @return mixed
   *   A plugin definition.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   *   Thrown if $type is cannot be matched to a plugin.
   */
  public function getDefinitionForMediaType($type) {
    $definitions = $this->getDefinitions();
    foreach ($definitions as $definition) {
      if (in_array($type, $definition['media_types'])) {
        return $definition;
      }
    }

    // Deal with deriver plugins.
    if (strpos($type, ':') !== FALSE) {
      $type_parts = explode(':', $type);
      array_pop($type_parts);
      $type = implode(':', $type_parts);
      return $this->getDefinitionForMediaType($type);
    }

    throw new PluginException('Unable to get a definition for media type ' . $type . '.');
  }

  /**
   * Get a plugin instance for a media type.
   *
   * @param string $type
   *   The type of media to create a checksum instance from.
   *
   * @return \Drupal\media_duplicates\Plugin\MediaDuplicatesChecksumInterface
   *   The checksum plugin.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function createInstanceForMediaType($type) {
    $definition = $this->getDefinitionForMediaType($type);
    return $this->createInstance($definition['id']);
  }

}
