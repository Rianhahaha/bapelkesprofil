<?php  

/**  
 * @file  
 * Contains Drupal\contact_us\Form\ContactUsAdminForm.  
 */  

namespace Drupal\contact_us\Form;  

use Drupal\Core\Form\ConfigFormBase;  
use Drupal\Core\Form\FormStateInterface;  

/**
 * Contact Us admin configuraiton form.
 */
class ContactUsAdminForm extends ConfigFormBase {  
  /**  
   * {@inheritdoc}  
   */  
  protected function getEditableConfigNames() {  
    return [  
      'contact_us.adminsettings',  
    ];  
  }  

  /**  
   * {@inheritdoc}  
   */  
  public function getFormId() {  
    return 'contact_us_admin_form';  
  }  
  
  /**  
   * {@inheritdoc}  
   */  
  public function buildForm(array $form, FormStateInterface $form_state) {  
    $config = $this->config('contact_us.adminsettings');  

    $form['contact_us_admin_email'] = array(  
      '#type' => 'email',  
      '#title' => $this->t('Email address'),  
      '#description' => $this->t('Email address to which Contact Us form details to be sent'),  
      '#default_value' => $config->get('contact_us_admin_email'),  
      '#required' => TRUE,
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    /**
     * Validate email address
     */
    $contact_us_admin_email = trim($form_state->getValue('contact_us_admin_email'));
  
    if ($contact_us_admin_email !== '' && !\Drupal::service('email.validator')->isValid($contact_us_admin_email)) {
      $form_state->setErrorByName('contact_us_admin_email', $this->t('Invalid email address'));  
    }
  }

  /**  
   * {@inheritdoc}  
   */  
  public function submitForm(array &$form, FormStateInterface $form_state) {  
    $this->config('contact_us.adminsettings')  
      ->set('contact_us_admin_email', trim($form_state->getValue('contact_us_admin_email')))  
      ->save();  
  }    
}
