<?php

namespace Drupal\contact_us\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Contact Us form Block' block.
 *
 * @Block(
 *   id = "contact_us_block",
 *   admin_label = @Translation("Contact Us Block"),
 *   category = @Translation("Contact Us")
 * )
*/
class ContactUsFormBlock extends BlockBase {

 /**
  * {@inheritdoc}
 */
 public function build() {
  /**
   * Include the already created contact us form in a block 
   */
   $form = \Drupal::formBuilder()->getForm('Drupal\contact_us\Form\ContactUsForm');
   $form['contactusyourname']['#size'] = 20;
   $form['contactuscompanyname']['#size'] = 20;
   $form['contactusdesignation']['#size'] = 20;
   $form['contactusaddress']['#size'] = 20;
   $form['contactuscountry']['#size'] = 20;
   $form['contactusemail']['#size'] = 20;
   $form['contactusphone']['#size'] = 20;
   $form['contactusquery']['#rows'] = 3;
   $form['contactusquery']['#cols'] = 18;
   
   return $form;
 }
 
}
