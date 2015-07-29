<?php
class ExchangeRate extends DataObject implements PermissionProvider {

	private static $db = array(
		'Title' => 'Varchar',
		'Currency' => 'Varchar(3)',
		'CurrencySymbol' => 'Varchar(10)',
		'Rate' => 'Decimal(19,4)',
		'BaseCurrency' => 'Varchar(3)',
		'BaseCurrencySymbol' => 'Varchar(10)',
		'Sort' => 'Int',
    'Status' => 'Boolean'
	);

  private static $default_sort = 'Sort';

	private static $has_one = array();

	private static $summary_fields = array(
		'Title' => 'Title',
		'CurrencySymbol' => 'Symbol',
		'Currency' => 'Currency',
		'BaseCurrency' => 'Base Currency',
		'Rate' => 'Rate',
    'humanStatus' => 'Status'
	);

  public function providePermissions(){
      return array(
          //'EDIT_CURRENCY' => 'Edit Currency',
      );
  }
  //
  // public function canEdit($member = null){
  //     return Permission::check('EDIT_CURRENCY');
  // }
  //
  // public function canView($member = null){
  //     return true;
  // }
  //
  // public function canDelete($member = null){
  //     return Permission::check('EDIT_CURRENCY');
  // }
  //
  // public function canCreate($member = null)
  // {
  //     return Permission::check('EDIT_CURRENCY');
  // }


	/**
	 * Field for editing a {@link ExchangeRate}.
	 *
	 * @return FieldSet
	 */
	public function getCMSFields() {

		$baseCurrency = ShopConfig::config()->base_currency;

		return new FieldList(
			TextField::create('Title')->setDescription('E.g. New Zealand'),
			TextField::create('Currency', _t('ExchangeRate.CURRENCY', ' Currency'))->setDescription('3 letter currency code - <a href="http://en.wikipedia.org/wiki/ISO_4217#Active_codes" target="_blank">available codes</a>'),
			TextField::create('CurrencySymbol', _t('ExchangeRate.SYMBOL', 'Symbol'))->setDescription('Symbol to use for this currency'),
			NumericField::create('Rate', _t('ExchangeRate.RATE', 'Rate'))->setDescription("Rate to convert from {$baseCurrency}"),
      DropdownField::create('Status', 'Status', array("0" => "Inactive", "1" => "Active"))->setDescription("This will determine if the user can browse using this currency.")
		);
	}

	public function getCMSValidator() {
		return new RequiredFields(array(
			'Title',
			'Currency',
			'Rate'
		));
	}

	public function validate() {

		$result = new ValidationResult();

		if (!$this->Title || !$this->Currency || !$this->Rate) {
			$result->error(
				'Please fill in all fields',
				'ExchangeRateInvalidError'
			);
		}
		return $result;
	}

  public function humanStatus(){
    if($this->Status == 1){
      return "Active";
    }else{
      return "Inactive";
    }
  }

}
