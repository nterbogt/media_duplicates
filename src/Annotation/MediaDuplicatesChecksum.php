<?php

namespace Drupal\media_duplicates\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a content check plugin annotation object.
 *
 * Plugin namespace: Plugin\content_check.
 *
 * For a working example,
 * see \Drupal\content_check\Plugin\ContentCheck\UrlAliasCheck.
 *
 * @see \Drupal\content_check\ContentCheckInterface
 * @see \Drupal\content_check\ContentCheckBase
 * @see \Drupal\content_check\ContentCheckPluginManager
 * @see hook_content_check_info_alter()
 * @see plugin_api
 *
 * @Annotation
 */
class MediaDuplicatesChecksum extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the checksum.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The media types this plugin supports.
   *
   * @var string[]
   */
  public $media_types;

}
