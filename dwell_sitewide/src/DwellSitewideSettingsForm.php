<?php

namespace Drupal\dwell_sitewide;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure dwell sitewide settings for this site.
 *
 * @internal
 */
class DwellSitewideSettingsForm extends ConfigFormBase
{

  /**
   * Constructs a \Drupal\user\DwellSitewideSettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory)
  {
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'dwell_sitewide_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'dwell_sitewide.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('dwell_sitewide.settings');

    $form['header'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Header'),
      '#format' => $config->get('header.format'),
      '#default_value' => $config->get('header.value'),
    ];

    $form['footer'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Footer'),
      '#format' => $config->get('footer.format'),
      '#default_value' => $config->get('footer.value'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);
    $header = $form_state->getValue('header');
    $footer = $form_state->getValue('footer');


    $this->config('dwell_sitewide.settings')
      ->set('header.value', $header['value'])
      ->set('header.format', $header['format'])
      ->set('footer.value', $footer['value'])
      ->set('footer.format', $footer['format'])
      ->save();
  }
}
