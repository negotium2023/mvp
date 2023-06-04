<?php

namespace App\Http\Controllers;

use App\Client;
use App\Document;
use App\RelatedParty;
use App\Template;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->result_count = 6;
        $this->actions = collect([
            [
                'name' => 'Create Client',
                'route' => route('clients.create'),
                'type' => 'Shortcut'
            ],
            [
                'name' => 'Create Referrer',
                'route' => route('referrers.create'),
                'type' => 'Shortcut'
            ],
            [
                'name' => 'Create Document',
                'route' => route('documents.create'),
                'type' => 'Shortcut'
            ],
            [
                'name' => 'Create Template',
                'route' => route('templates.create'),
                'type' => 'Shortcut'
            ],
            [
                'name' => 'View Recents',
                'route' => route('recents'),
                'type' => 'Shortcut'
            ],
            [
                'name' => 'View Dashboard',
                'route' => route('dashboard'),
                'type' => 'Shortcut'
            ],
            [
                'name' => 'View Calendar',
                'route' => route('calendar'),
                'type' => 'Shortcut'
            ],
            [
                'name' => 'View Clients',
                'route' => route('clients.index'),
                'type' => 'Shortcut'
            ],
            [
                'name' => 'View Referrers',
                'route' => route('referrers.index'),
                'type' => 'Shortcut'
            ],
            [
                'name' => 'View Documents',
                'route' => route('documents.index'),
                'type' => 'Shortcut'
            ],
            [
                'name' => 'View Templates',
                'route' => route('templates.index'),
                'type' => 'Shortcut'
            ],
            [
                'name' => 'View Insight',
                'route' => route('insight.index'),
                'type' => 'Shortcut'
            ]
        ]);
        $this->actions->zip(['type' => 'Shortcut']);
    }

    public function getResults(Request $request)
    {
        if ($request->input('q') == '') {
            return null;
        }

        $search_term = $request->input('q');

        $actions = $this->actions->filter(function ($value) use ($search_term) {
            return str_contains(strtolower($value['name']), strtolower($search_term));
        });

        $actions = $actions->take($this->result_count);

        $results = collect();

        $results = $results->merge($actions);

        $client = new Client();
        $client->unHide();

        if ($results->count() < $this->result_count) {
            $results = $results->merge(
                $client->select(DB::raw("IF(`hash_first_name` != '',CONCAT(CAST(AES_DECRYPT(`hash_first_name`, 'Qwfe345dgfdg') AS CHAR(50)),' ',CAST(AES_DECRYPT(`hash_last_name`, 'Qwfe345dgfdg') AS CHAR(50))),CAST(AES_DECRYPT(`hash_company`, 'Qwfe345dgfdg') AS CHAR(50))) as `name`"), DB::raw("CONCAT('" . route('clients.show', null) . "/',`id`) as route"),
                    DB::raw('CAST(AES_DECRYPT(`hash_company`, "Qwfe345dgfdg") AS CHAR(50)) human_company'),
                    DB::raw('CAST(AES_DECRYPT(`hash_first_name`, "Qwfe345dgfdg") AS CHAR(50)) human_first_name'),
                    DB::raw('CAST(AES_DECRYPT(`hash_last_name`, "Qwfe345dgfdg") AS CHAR(50)) human_last_name'),
                    DB::raw('CAST(AES_DECRYPT(`hash_cif_code`, "Qwfe345dgfdg") AS CHAR(50)) human_cif_code'),
                    DB::raw('CAST(AES_DECRYPT(`hash_id_number`, "Qwfe345dgfdg") AS CHAR(50)) human_id_number'),
                    DB::raw('CAST(AES_DECRYPT(`hash_company_registration_number`, "Qwfe345dgfdg") AS CHAR(50)) human_company_registration_number'))
                    ->limit($this->result_count - $results->count())
                    ->having(DB::raw('human_company'), 'like', '%' . $search_term . '%')
                    ->orHaving(DB::raw('human_first_name'), 'like', '%' . $search_term . '%')
                    ->orHaving(DB::raw('human_last_name'), 'like', '%' . $search_term . '%')
                    ->orHaving(DB::raw('human_cif_code'), 'like', '%' . $search_term . '%')
                    ->orHaving(DB::raw('human_id_number'), 'like', '%' . $search_term . '%')
                    ->orHaving(DB::raw('human_company_registration_number'), 'like', '%' . $search_term . '%')
                    ->get()
                    ->map(function ($item) {
                        $item->type = 'Client';
                        return $item;
                    })
            );
        }

        if ($results->count() < $this->result_count) {
            $results = $results->merge(
                $client->select('case_number',DB::raw("IF(`hash_first_name` != '',CONCAT(CAST(AES_DECRYPT(`hash_first_name`, 'Qwfe345dgfdg') AS CHAR(50)),' ',CAST(AES_DECRYPT(`hash_last_name`, 'Qwfe345dgfdg') AS CHAR(50))),CAST(AES_DECRYPT(`hash_company`, 'Qwfe345dgfdg') AS CHAR(50))) as `name`"), DB::raw("CONCAT('" . route('clients.show', null) . "/',`id`) as route"))
                    ->where('case_number','like', '%'.$search_term.'%')
                    ->limit($this->result_count - $results->count())
                    ->get()
                    ->map(function ($item) {
                        $item->type = 'Client';
                        return $item;
                    })
            );
        }

        $related_party = new RelatedParty();
        $related_party->unHide();

        if ($results->count() < $this->result_count) {
            $results = $results->merge(
                $related_party->select('case_number',DB::raw("IF(`hash_first_name` != '',CONCAT(CAST(AES_DECRYPT(`hash_first_name`, 'Qwfe345dgfdg') AS CHAR(50)),' ',CAST(AES_DECRYPT(`hash_last_name`, 'Qwfe345dgfdg') AS CHAR(50))),CAST(AES_DECRYPT(`hash_company`, 'Qwfe345dgfdg') AS CHAR(50))) as `name`"),DB::raw("CONCAT('/relatedparty/',`client_id`,'/',`id`,'/',`process_id`,'/',`step_id`,'/details') as route"),
                    DB::raw('CAST(AES_DECRYPT(`hash_company`, "Qwfe345dgfdg") AS CHAR(50)) human_company'),
                    DB::raw('CAST(AES_DECRYPT(`hash_first_name`, "Qwfe345dgfdg") AS CHAR(50)) human_first_name'),
                    DB::raw('CAST(AES_DECRYPT(`hash_last_name`, "Qwfe345dgfdg") AS CHAR(50)) human_last_name'),
                    DB::raw('CAST(AES_DECRYPT(`hash_cif_code`, "Qwfe345dgfdg") AS CHAR(50)) human_cif_code'),
                    DB::raw('CAST(AES_DECRYPT(`hash_id_number`, "Qwfe345dgfdg") AS CHAR(50)) human_id_number'),
                    DB::raw('CAST(AES_DECRYPT(`hash_company_registration_number`, "Qwfe345dgfdg") AS CHAR(50)) human_company_registration_number'))
                    ->limit($this->result_count - $results->count())
                ->having(DB::raw('human_company'), 'like', '%' . $search_term . '%')
                ->orHaving(DB::raw('human_first_name'), 'like', '%' . $search_term . '%')
                ->orHaving(DB::raw('human_last_name'), 'like', '%' . $search_term . '%')
                ->orHaving(DB::raw('human_cif_code'), 'like', '%' . $search_term . '%')
                ->orHaving(DB::raw('human_id_number'), 'like', '%' . $search_term . '%')
                ->orHaving(DB::raw('human_company_registration_number'), 'like', '%' . $search_term . '%')
                    ->get()
                    ->map(function ($item) {
                        $item->type = 'Related Party';
                        return $item;
                    })
            );
        }

        if ($results->count() < $this->result_count) {
            $results = $results->merge(
                $related_party->select('case_number',DB::raw("IF(`hash_first_name` != '',CONCAT(CAST(AES_DECRYPT(`hash_first_name`, 'Qwfe345dgfdg') AS CHAR(50)),' ',CAST(AES_DECRYPT(`hash_last_name`, 'Qwfe345dgfdg') AS CHAR(50))),CAST(AES_DECRYPT(`hash_company`, 'Qwfe345dgfdg') AS CHAR(50))) as `name`"), DB::raw("CONCAT('" . route('clients.show', null) . "/',`id`) as route"))
                    ->where('case_number','like', '%'.$search_term.'%')
                    ->limit($this->result_count - $results->count())
                    ->get()
                    ->map(function ($item) {
                        $item->type = 'Related Party';
                        return $item;
                    })
            );
        }

        return $results;
    }
}
