<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class ImportExcel implements ToArray
{
    const COLUMN_COUNT = 61;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(array $array): void
    {
        $keys = array_slice($array[0], 0, 61);
        unset($array[0]);
        if (count($array) > 0) {
            foreach ($array as $item) {
                if ($item[0]) {
                    $slicedItem = array_slice($item, 0, 61);

                    $this->data[] = array_combine($keys, $slicedItem);
                }
            }
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
