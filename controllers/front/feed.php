<?php
require_once _PS_MODULE_DIR_ . 'ovesio_ecommerce/classes/OvesioExport.php';

class Ovesio_EcommerceFeedModuleFrontController extends ModuleFrontController
{
    public $auth = false;
    public $ajax = true;

    public function initContent()
    {
        parent::initContent();

        header('Content-Type: application/json');

        if (!Configuration::get('OVESIO_ECOMMERCE_STATUS')) {
            $this->returnError($this->module->l('Module is disabled'));
        }

        $hash = Tools::getValue('hash');
        $configuredHash = Configuration::get('OVESIO_ECOMMERCE_HASH');

        if (!$configuredHash || $hash !== $configuredHash) {
            $this->returnError($this->module->l('Access denied: Invalid Hash'));
        }

        $exporter = new OvesioExport();
        $action = Tools::getValue('action', 'products');

        if ($action == 'orders') {
            $duration = (int)Configuration::get('OVESIO_ECOMMERCE_EXPORT_DURATION');
            $data = $exporter->getOrdersExport($duration);
            $this->outputJson($data, 'orders');
        } else {
            $data = $exporter->getProductsExport();
            $this->outputJson($data, 'products');
        }
    }

    private function returnError($message)
    {
        die(json_encode(['error' => $message]));
    }

    private function outputJson($data, $type)
    {
        $filename = "export_" . $type . "_" . date('Y-m-d') . ".json";

        header('Content-Disposition: attachment; filename="' . $filename . '";');

        echo json_encode(['data' => $data], JSON_PRETTY_PRINT);
        die();
    }
}
