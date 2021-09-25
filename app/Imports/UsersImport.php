<?php

namespace App\Imports;

use Employees;
use Users;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\OnEachRow;

class UsersImport implements ToModel, WithProgressBar, OnEachRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onEachRow(array $row)
    {   


        return new Employees([
            //
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
