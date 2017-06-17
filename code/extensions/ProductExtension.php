<?php

class ProductExtension extends DataExtension
{

    private static $casting = array(
    "CurrencyPrice" => 'Currency'
  );

    public function getCurrencyPrice()
    {
        if ($currentCurrency = Session::get('Currency')) {
            $currencyRate = ExchangeRate::get()->filter(array("Currency" => $currentCurrency))->first();
            if ($currencyRate && $currencyRate->exists()) {
                $convertedPrice = $this->owner->getField('BasePrice') * $currencyRate->Rate;

                return $convertedPrice;
            }
        }

    // no currency other than base
    return false;
    }

  // Disabled for now.
  // Not ideal as it changes it throughout the whole checkout process meaning the user pays less in some cases.
  // re-wroking logic
  //
  // public function getBasePrice() {
  //
  //   if($currentCurrency = Session::get('Currency')){
  //     $currencyRate = ExchangeRate::get()->filter(array("Currency" => $currentCurrency))->first();
  //     if($currencyRate && $currencyRate->exists()){
  //
  //       $convertedPrice = $this->owner->getField('BasePrice') * $currencyRate->Rate;
  //
  //       return $convertedPrice;
  //     }
  //   }
  //
  //   return $this->owner->getField('BasePrice');
  // }
}
