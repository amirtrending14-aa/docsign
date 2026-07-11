<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentSignature as Signature;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('query'));
        $userId = Auth::id(); // Получаем ID текущего пользователя

        if (empty($query)) {
            $results = collect();
            return view('search.results', compact('results', 'query'));
        }

        // Поиск пользователей
        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->get();

        // ✅ ИСПРАВЛЕНО: Поиск только СВОИХ документов + документов, отправленных МНЕ
        $documents = Document::where(function($q) use ($query, $userId) {
            $q->where('user_id', $userId) // Мои документы
            ->where(function($q2) use ($query) {
                $q2->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('content', 'LIKE', "%{$query}%");
            });
        })
            ->orWhere(function($q) use ($query, $userId) {
                // Документы, которые мне отправили (через signatures)
                $q->whereHas('signatures', function($q2) use ($userId) {
                    $q2->where('user_id', $userId);
                })
                    ->where(function($q2) use ($query) {
                        $q2->where('title', 'LIKE', "%{$query}%")
                            ->orWhere('content', 'LIKE', "%{$query}%");
                    });
            })
            ->get();

        // Поиск подписей (только своих или связанных с моими документами)
        $signatures = Signature::with(['document', 'user'])
            ->where(function($q) use ($userId) {
                $q->where('user_id', $userId) // Мои подписи
                ->orWhereHas('document', function($q2) use ($userId) {
                    $q2->where('user_id', $userId); // Или подписи на моих документах
                });
            })
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