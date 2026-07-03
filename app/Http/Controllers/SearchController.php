<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentSignature as Signature;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('query'));

        if (empty($query)) {
            $results = collect();
            return view('search.results', compact('results', 'query'));
        }

        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->get();

        $documents = Document::where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->get();

        // УБРАЛИ 'users' - такой связи нет в модели
        $signatures = Signature::with(['document', 'user'])
            ->where(function($q) use ($query) {
                $q->where('id', 'LIKE', "%{$query}%")
                    ->orWhereHas('document', function($q2) use ($query) {
                        $q2->where('title', 'LIKE', "%{$query}%");
                    });
            })
            ->get();

        $results = collect()
            ->concat($users)
            ->concat($documents)
            ->concat($signatures)
            ->sortByDesc('created_at');

        return view('search.results', compact('results', 'query'));
    }
}