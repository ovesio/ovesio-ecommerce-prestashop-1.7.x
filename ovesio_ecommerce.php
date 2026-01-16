<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Ovesio_Ecommerce extends Module
{
    public function __construct()
    {
        $this->name = 'ovesio_ecommerce';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.0';
        $this->author = 'Ovesio';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Ovesio - Ecommerce Intelligence');
        $this->description = $this->l('Empowers your store with advanced AI-driven insights, stock management forecasting, and strategic consulting.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        $hash = md5(uniqid(mt_rand(), true));

        // Default settings
        if (!parent::install() ||
            !Configuration::updateValue('OVESIO_ECOMMERCE_STATUS', 0) ||
            !Configuration::updateValue('OVESIO_ECOMMERCE_EXPORT_DURATION', 12) ||
            !Configuration::updateValue('OVESIO_ECOMMERCE_HASH', $hash)
        ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('OVESIO_ECOMMERCE_STATUS') ||
            !Configuration::deleteByName('OVESIO_ECOMMERCE_EXPORT_DURATION') ||
            !Configuration::deleteByName('OVESIO_ECOMMERCE_HASH')
        ) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        $output = '';

        // Handle Form Submission
        if (Tools::isSubmit('submitOvesioEcommerce')) {
            $status = (string)Tools::getValue('OVESIO_ECOMMERCE_STATUS');
            $duration = (int)Tools::getValue('OVESIO_ECOMMERCE_EXPORT_DURATION');

            Configuration::updateValue('OVESIO_ECOMMERCE_STATUS', $status);
            Configuration::updateValue('OVESIO_ECOMMERCE_EXPORT_DURATION', $duration);

            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        // Generate hashing and URLs
        $hash = Configuration::get('OVESIO_ECOMMERCE_HASH');
        if (!$hash) {
            $hash = md5(uniqid(mt_rand(), true));
            Configuration::updateValue('OVESIO_ECOMMERCE_HASH', $hash);
        }

        $baseUrl = $this->context->link->getModuleLink('ovesio_ecommerce', 'feed', ['hash' => $hash]);
        $productFeedUrl = $baseUrl . '&action=products';
        $orderFeedUrl = $baseUrl . '&action=orders';

        // Information Block
        $this->context->smarty->assign([
            'product_feed_url' => $productFeedUrl,
            'order_feed_url' => $orderFeedUrl,
            'hash' => $hash
        ]);

        $infoBlock = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');


        return $output . $infoBlock . $this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Status'),
                        'name' => 'OVESIO_ECOMMERCE_STATUS',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            ]
                        ],
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->l('Order Export Period'),
                        'name' => 'OVESIO_ECOMMERCE_EXPORT_DURATION',
                        'desc' => $this->l('Choose the historical period for analysis.'),
                        'options' => [
                            'query' => [
                                ['id' => 12, 'name' => $this->l('Last 12 Months')],
                                ['id' => 24, 'name' => $this->l('Last 24 Months')]
                            ],
                            'id' => 'id',
                            'name' => 'name'
                        ]
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitOvesioEcommerce';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$fields_form]);
    }

    public function getConfigFormValues()
    {
        return [
            'OVESIO_ECOMMERCE_STATUS' => Configuration::get('OVESIO_ECOMMERCE_STATUS', 0),
            'OVESIO_ECOMMERCE_EXPORT_DURATION' => Configuration::get('OVESIO_ECOMMERCE_EXPORT_DURATION', 12),
        ];
    }
}
