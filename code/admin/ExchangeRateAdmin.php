<?php
class ExchangeRateAdmin extends ModelAdmin
{

    private static $managed_models = array('ExchangeRate');
    private static $url_segment = 'exchangeRate';
    private static $menu_title = 'Exchange Rates';
  //private static $menu_priority = 100;
  //private static $menu_icon = 'mysite/images/cashmoneys.png';
  private static $model_importers = array();

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $gridField = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
        $Config = $gridField->getConfig();

        $Config->removeComponentsByType('GridFieldDeleteAction');
        $gridField->setConfig($Config);

        return $form;
    }
}
