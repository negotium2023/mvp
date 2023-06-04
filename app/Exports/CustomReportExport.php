<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomReportExport implements FromView
{
    public function __construct($clients,$columns)
    {
        $this->clients = $clients;
        $this->columns = $columns;
    }

    public function view(): View
    {
        return view('customreports.export', [
            'fields' => $this->columns,
            'clients' => $this->clients,
        ]);
    }
}
