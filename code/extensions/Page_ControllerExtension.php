<?php
class Page_ControllerExtension extends DataExtension
{

    private static $allowed_actions = array(
    'changeCurrencyForm'
    );

    public function changeCurrencyForm()
    {
        $exchangeRates = ExchangeRate::get()->filter(array("Status" => 1))->map();
    // set the form value to current session
    $currentCurrency = $this->getCurrentCurrency();
        if ($currentCurrency === null) {
            $formValue = 0;
        } else {
            // get exchange rate value
      $formValue = ExchangeRate::get()->filter(array("Currency" => $currentCurrency, "Status" => 1))->column("ID");
        }

        $fields = new FieldList();
        $fields->push(DropdownField::create("Currency", "Currency", $exchangeRates, $formValue[0]));

        $actions = new FieldList(FormAction::create("doChangeCurrency")
      ->setTitle("Change Currency")
    );

        $required = new RequiredFields(array("Currency"));

        $form = Form::create($this->owner, 'changeCurrencyForm', $fields, $actions, $required);

        return $form;
    }

  // do currency change
  public function doChangeCurrency($data, Form $form)
  {

    // validate here again
    $currencyIsValid = ExchangeRate::get()->filter(array("ID" => $data['Currency'], "Status" => 1))->first();

      if ($currencyIsValid && $currencyIsValid->exists()) {
          // clear it just incase
      Session::clear('Currency');
      // trigger set currenct function
      $this->setCurrentCurrency($currencyIsValid->Currency);
      }

    // return the user to where they were
    return Controller::curr()->redirectBack();
  }

  // get current currency
  public function getCurrentCurrency()
  {
      if ($currentCurrency = Session::get('Currency')) {
          // validate current curencies against exchange rates table?

      return $currentCurrency;
      }

      return null;
  }

  // set current currency
  public function setCurrentCurrency($currency)
  {
      if ($currency) {
          Session::set('Currency', $currency);
          return true;
      }

      return false;
  }
}
