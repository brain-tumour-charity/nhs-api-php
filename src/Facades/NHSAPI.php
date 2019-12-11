<?php namespace NHSAPI\Facades;

use Illuminate\Support\Facades\Facade;

class NHSAPI extends Facade {

  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'brain-tumour-charity.nhs-api-php'; }

}
