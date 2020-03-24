<?php

namespace Drupal\rw_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'RWMailchimpBlock' block.
 *
 * @Block(
 *  id = "mailchimp_block",
 *  admin_label = @Translation("Mailchimp block"),
 * )
 */
class RWMailchimpBlock extends BlockBase
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

    $form['mailchimp_user_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mailchimp User ID'),
      // '#description' => $this->t(''),
      '#default_value' => isset($config['mailchimp_user_id']) ? $config['mailchimp_user_id'] : '5d0a468ccba985c5b0985464e',
    ];

    $form['mailchimp_form_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mailchimp Form ID'),
      // '#description' => $this->t(''),
      '#default_value' => isset($config['mailchimp_form_id']) ? $config['mailchimp_form_id'] : 'a10c33b2f3',
    ];

    $form['content_first'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Intro'),
      '#format' => 'html',
      '#description' => $this->t('Pre-form html'),
      '#default_value' => isset($config['content_first']) ? $config['content_first']['value'] : '',
    ];

    $form['content_second'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Outro'),
      '#format' => 'html',
      '#description' => $this->t('Post-form html'),
      '#default_value' => isset($config['content_second']) ? $config['content_second']['value'] : '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {

    $this->configuration['mailchimp_user_id'] = $form_state->getValue('mailchimp_user_id');
    $this->configuration['mailchimp_form_id'] = $form_state->getValue('mailchimp_form_id');
    $this->configuration['content_first'] = $form_state->getValue('content_first');
    $this->configuration['content_second'] = $form_state->getValue('content_second');
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $build = [];

    $build['mailchimp_intro']['#markup'] = $this->configuration['content_first']['value'];
    $build['mailchimp_form'] = [
      '#theme' => 'rwmailchimp_block',
      '#user_id' => $this->configuration['mailchimp_user_id'],
      '#form_id' => $this->configuration['mailchimp_form_id'],
    ];

    $build['mailchimp_outro']['#markup'] = $this->configuration['content_second']['value'];
    $build['#attached']['library'][] = 'rw_blocks/mailchimp_block';

    return $build;
  }
}
