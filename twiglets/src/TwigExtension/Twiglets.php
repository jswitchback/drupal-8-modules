<?php

namespace Drupal\twiglets\TwigExtension;

use Drupal\Core\Url;

class Twiglets extends \Twig_Extension {

  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('icon_link', [$this, 'iconLink']),
      new \Twig_SimpleFunction('icon', [$this, 'icon']),
    ];
  }

  public function getFilters() {
    return [
      new \Twig_SimpleFilter('new_filter', [$this, 'newFilter']),
    ];
  }

  /**
   * Gets a unique identifier for this Twig extension.
   */
  public function getName() {
    return 'twiglets.twig_extension';
  }

  /**
   * Builds icon markup.
   *
   * @param string $icon_name
   *   The name used as the class on the icon.
   * @return array
   *   A render array to represent the icon.
   */
  public static function icon($icon_name) {
    return [
      '#markup' => '<i class="icon icon-' . $icon_name . '"></i>'
    ];
  }


  /**
   * Builds link and icon markup
   *
   * @param string $icon_name
   *   Used as a class on the icon.
   * @param boolean $icon_is_first
   *   Determines placement of icon.
   * @param string $text
   *   Link text.
   * @param string $url
   *   Link href attribute.
   * @param string $link_class
   *   Link class attribute.
   * @return array
   *   A render array to represent the icon.
   */
  public static function iconLink($icon_name = 'default', $icon_is_first = FALSE, $text, $url, $link_class) {
    if (!in_array($url[0], ['/', '#', '?'])) {
      $url = '/' . $url;
    }
    $url = Url::fromUserInput($url);
    $icon = self::icon($icon_name)['#markup'];
    $link_text = $icon_is_first ? $icon . $text : $text . $icon;

    return [
      '#title' => t($link_text),
      '#type' => 'link',
      '#url' => $url,
      '#options' => [
        'attributes' => [
          'class' => $link_class,
        ],
      ],
    ];
  }

  /**
   * Returns nothing for now.
   */
  public static function newFilter($string) {
    return $string . ' has been filtered';
  }

}
