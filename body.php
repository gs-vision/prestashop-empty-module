<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class body extends Module
{

	public function __construct()
	{
		$this->name = 'body';
		$this->tab = 'front_office_features';
		$this->version = '0.1;
		$this->author = 'GS VISION';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Empty module');
		$this->description = $this->l('Emptry module structure.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}
	
	public function install()
	{
		if (!parent::install())
			return false;

		// Hook the module either on the left or right column
		$theme = new Theme(Context::getContext()->shop->id_theme);
		if ((!$theme->default_left_column || !$this->registerHook('leftColumn'))
			&& (!$theme->default_right_column || !$this->registerHook('rightColumn'))
		)
		{
			// If there are no colums implemented by the template, throw an error and uninstall the module
			$this->_errors[] = $this->l('This module needs to be hooked to a column, but your theme does not implement one');
			parent::uninstall();

			return false;
		}

		// Try to update with the extension of the image that exists in the module directory
		foreach (scandir(_PS_MODULE_DIR_.$this->name) as $file)
			if (in_array($file, array('advertising.jpg', 'advertising.gif', 'advertising.png')))
				Configuration::updateGlobalValue('BLOCKADVERT_IMG_EXT', substr($file, strrpos($file, '.') + 1));

		return true;
	}

	public function uninstall()
	{
		return (parent::uninstall());
	}
	
	public function postProcess()
	{
	 
	}
	
	/**
	 * getContent used to display admin module form
	 *
	 * @return string content
	 */
	public function getContent()
	{
		$this->postProcess();

		return $this->renderForm();
	}

	public function hookRightColumn($params)
	{
	/*	if (!$this->isCached('body.tpl', $this->getCacheId()))
			$this->smarty->assign();
	*/

		return $this->display(__FILE__, 'body.tpl', $this->getCacheId());
	}

	public function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

	public function hookHeader($params)
	{
		$this->context->controller->addCSS($this->_path.'body.css', 'all');
	}

  /*
  	public function renderForm()
  	{
  		$fields_form = array(
  			'form' => array(
  				'legend' => array(
  					'title' => $this->l('Configuration'),
  					'icon' => 'icon-cogs'
  				),
  				'input' => array(
  					array(
  						'type' => 'file',
  						'label' => $this->l('Image for the advertisement'),
  						'name' => 'adv_img',
  						'desc' => $this->l('By default the image will appear in the left column. The recommended dimensions are 155 x 163px.'),
  						'thumb' => $this->context->link->protocol_content.$this->adv_img,
  					),
  					array(
  						'type' => 'text',
  						'label' => $this->l('Target link for the image'),
  						'name' => 'adv_link',
  					),
  					array(
  						'type' => 'text',
  						'label' => $this->l('Title of the target link'),
  						'name' => 'adv_title',
  						'desc' => $this->l('This title will be displayed when you mouse over the advertisement block in your shop.')
  					),
  				),
  				'submit' => array(
  					'title' => $this->l('Save'),
  				)
  			),
  		);
  
  		$helper = new HelperForm();
  		$helper->show_toolbar = false;
  		$helper->table = $this->table;
  		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
  		$helper->default_form_language = $lang->id;
  		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
  		$this->fields_form = array();
  		$helper->id = (int)Tools::getValue('id_carrier');
  		$helper->identifier = $this->identifier;
  		$helper->submit_action = 'submitAdvConf';
  		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
  		$helper->token = Tools::getAdminTokenLite('AdminModules');
  		$helper->tpl_vars = array(
  			'fields_value' => $this->getConfigFieldsValues(),
  			'languages' => $this->context->controller->getLanguages(),
  			'id_language' => $this->context->language->id
  		);
  
  		return $helper->generateForm(array($fields_form));
  	 }
  	*/
}
