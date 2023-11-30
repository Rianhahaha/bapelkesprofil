<?php

/**
 * @file
 * Contains \Drupal\contact_us\Form\ContactUsForm.
 */

namespace Drupal\contact_us\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Contact us form.
 */
class ContactUsForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contact_us_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['contactusyourname'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#size' => 60,
      '#maxlength' => 60,
      '#required' => TRUE,
    );	 
    $form['contactuscompanyname'] = array(
      '#type' => 'textfield',
      '#title' => t('Company Name'),
      '#size' => 60,
      '#maxlength' => 100,
    );
    $form['contactusdesignation'] = array(
      '#type' => 'textfield',
      '#title' => t('Designation/Role'),
      '#size' => 60,
      '#maxlength' => 100,
    );
    $form['contactusaddress'] = array(
      '#type' => 'textfield',
      '#title' => t('Address'),
      '#size' => 60,
      '#maxlength' => 100,
    );
    $form['contactuscountry'] = array(
      '#type' => 'textfield',
      '#title' => t('Country'),
      '#size' => 60,
      '#maxlength' => 100,
    );
    $form['contactusemail'] = array(
      '#type' => 'email',
      '#title' => t('Email'),
      '#size' => 60,
      '#maxlength' => 100,
      '#required' => TRUE,
    );
    $form['contactusphone'] = array(
      '#type' => 'tel',
      '#title' => t('Phone'),
      '#size' => 60,
      '#maxlength' => 100,
      '#required' => TRUE,
    );
    $form['contactusquery'] = array(
      '#type' => 'textarea',
      '#title' => t('Query'),
      '#rows' => 5,
      '#cols' => 45,
      '#maxlength' => 1000,
      '#required' => TRUE,
      '#resizable' => 'none',
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
	/**
	 * Validate name
	 * Only alphabets and space are allowed
     */
    $contactusyourname = trim($form_state->getValue('contactusyourname'));
    
    if (!preg_match("/^([a-zA-Z ]+)$/", $contactusyourname)) {
	    $form_state->setErrorByName('contactusyourname', $this->t('Invalid characters in name'));
    }
    
    /**
     * Validate email address
     */
    $contactusemail = trim($form_state->getValue('contactusemail'));
  
    if ($contactusemail !== '' && !\Drupal::service('email.validator')->isValid($contactusemail)) {
      $form_state->setErrorByName('contactusemail', $this->t('Invalid email address'));  
    }

    /**
     * Validate phone for any alphabets
     */
    $contactusphone = trim($form_state->getValue('contactusphone'));

    if (preg_match('/[\Aa-z\z]/i', $contactusphone)) {
      $form_state->setErrorByName('contactusphone', $this->t('Phone can not contain alphabets'));  
    }

    /**
     * Validate query
     */
    $contactusquery = trim($form_state->getValue('contactusquery'));
  
    if ($contactusquery == '') {
      $form_state->setErrorByName('contactusquery', $this->t('Query can not be empty'));  
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /**
     * Get user form input
     */
    $contactusyourname = trim($form_state->getValue('contactusyourname'));
    $contactuscompanyname = trim($form_state->getValue('contactuscompanyname'));
    $contactusdesignation = trim($form_state->getValue('contactusdesignation'));
    $contactusaddress = trim($form_state->getValue('contactusaddress'));
    $contactuscountry = trim($form_state->getValue('contactuscountry'));
    $contactusemail = trim($form_state->getValue('contactusemail'));
    $contactusphone = trim($form_state->getValue('contactusphone'));
    $contactusquery = trim($form_state->getValue('contactusquery'));
    
    /**
     * Save contact us form data in content type "Contact Us Form"
     */
     $node = Node::create([
     'type' => 'contact_us_form',
     'langcode' => 'en',
     'created' => \Drupal::time()->getRequestTime(),
     'changed' => \Drupal::time()->getRequestTime(),
     // Default admin user ID.
     'uid' => 1,
     'title' => t('Contact Us Form'),
     'field_contact_us_name' => $contactusyourname,
     'field_contact_us_company_name' => $contactuscompanyname,
     'field_contact_us_designation' => $contactusdesignation,
     'field_contact_us_address' => $contactusaddress,
     'field_contact_us_country' => $contactuscountry,
     'field_contact_us_email' => $contactusemail,
     'field_contact_us_phone' => $contactusphone,
     'field_contact_us_query' => $contactusquery,
     'status' => 0,
     'promote' => 0,
    ]);
    $node->save();
    
    /**
     * Get the email address to which email to be sent 
     */
    $config = $this->config('contact_us.adminsettings');
    $contact_us_admin_email = trim($config->get('contact_us_admin_email'));
    
    if ($contact_us_admin_email) {
      /**
       * Send email
       */
      $mail_manager = \Drupal::service('plugin.manager.mail');
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $params['message']['contactusyourname'] = $contactusyourname;
      $params['message']['contactuscompanyname'] = $contactuscompanyname;
      $params['message']['contactusdesignation'] = $contactusdesignation;
      $params['message']['contactusaddress'] = $contactusaddress;
      $params['message']['contactuscountry'] = $contactuscountry;
      $params['message']['contactusemail'] = $contactusemail;
      $params['message']['contactusphone'] = $contactusphone;
      $params['message']['contactusquery'] = $contactusquery;
      
      $to = $contact_us_admin_email;
      
      $result = $mail_manager->mail('contact_us', 'contact_us_notify', $to, $langcode, $params, NULL, 'true');
   }

    /**
     * Display thanks message to the visitor
     */
    \Drupal::messenger()->addStatus(t('Thanks ' . $contactusyourname . '! The form has been submitted successfully.'));
  }
 
}
