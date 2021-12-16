<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AddressService {

  public $business_location;
  public $query_table;
  public $query_id;
  public $options;

  const THAI = 1;
  const LAOS = 2;
  const CAMBODIA = 3;
  const MYANMAR = 4;

  const PROVINCES = 'provinces';
  const AMPHURES = 'amphures';
  const DISTRICTS = 'districts';

  // Key = Business Location
  const DB_TABLES = [
    1 => [
      'provinces' => 'dataset_provinces',
      'amphures' => 'dataset_amphures',
      'districts' => 'dataset_districts',
    ],
    3 => [
      'provinces' => 'cambodia_provinces',
      'amphures' => 'cambodia_districts',
      'districts' => 'cambodia_communes',
    ]
  ];

  public function __construct($business_location)
  {
    $this->business_location = $business_location;
  }

  public function getTableName()
  {
    return static::DB_TABLES[$this->business_location];
  }

  public function query($query_table, $query_id = '')
  {
    $this->query_table = $query_table;
    $this->query_id = $query_id;
    return $this;
  }

  public function getProvinces() {
    return DB::table($this->getTableName()[static::PROVINCES]);
  }

  public function getAmphures() {
    return DB::table($this->getTableName()[static::AMPHURES])->where('province_id', $this->query_id);
  }

  public function getDistricts() {
    return DB::table($this->getTableName()[static::DISTRICTS])->where('amphure_id', $this->query_id);
  }

  public function getZipcode() {
    return DB::table($this->getTableName()[static::DISTRICTS])->select('zip_code')->where('id', $this->query_id)->first();
  }

  public function renderOptions()
  {
    if ($this->query_table == static::PROVINCES) {
      $this->options = $this->getProvinces();
    } else if ($this->query_table == static::AMPHURES) {
      $this->options = $this->getAmphures();
    } else if ($this->query_table == static::DISTRICTS) {
      $this->options = $this->getDistricts();
    } else {
      return $this->business_location == static::THAI ? $this->getZipcode()->zip_code : null;
    }

    $options = "<option value=''>- Select -</option>";

    foreach ($this->options->get() as $option) {

      if (isset($option->name)) {
        $name = $option->name;
      } else {
        $name = $option->name_th;
      }

      $options .= "<option value='$option->id'>$name</option>";
    }

    return $options;
  }

}
