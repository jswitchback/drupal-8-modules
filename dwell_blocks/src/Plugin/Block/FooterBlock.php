<?php

namespace Drupal\dwell_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'FooterBlock' block.
 *
 * @Block(
 *  id = "footer_block",
 *  admin_label = @Translation("Footer block"),
 * )
 */
class FooterBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration()
  {
    return [] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state)
  {
    $config = $this->getConfiguration();

    $form['content_first'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Logo'),
      '#format' => 'html',
      '#description' => $this->t('Faith alive logo markup'),
      '#default_value' => isset($config['content_first']) ? $config['content_first']['value'] : '',
      '#weight' => '0',
    ];
    $form['content_second'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Copyright and call to actions'),
      '#format' => 'html',
      '#default_value' => isset($config['content_second']) ? $config['content_second']['value'] : '',
      '#weight' => '1',
    ];
    $form['content_third'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Social media'),
      '#format' => 'html',
      '#default_value' => isset($config['content_third']) ? $config['content_third']['value'] : '',
      '#weight' => '2',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {
    $this->configuration['content_first'] = $form_state->getValue('content_first');
    $this->configuration['content_second'] = $form_state->getValue('content_second');
    $this->configuration['content_third'] = $form_state->getValue('content_third');
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {

    $build = [];
    $build['footer_block_first']['#markup'] = $this->configuration['content_first']['value'];
    $build['footer_block_second']['#markup'] = $this->configuration['content_second']['value'];
    $build['footer_block_third']['#markup'] = $this->configuration['content_third']['value'];

    return $build;
  }
}
