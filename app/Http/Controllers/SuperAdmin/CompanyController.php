<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        try {
            $companies = Company::withCount('users')
                ->with('owner')
                ->latest()
                ->paginate(20);
        } catch (\Exception $e) {
            $companies = Company::with('owner')
                ->latest()
                ->paginate(20);

            foreach ($companies as $company) {
                $company->users_count = $company->users()->count();
            }
        }

        return view('superadmin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('superadmin.companies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255|unique:companies,name',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        $company = Company::create($data);

        return redirect()->route('superadmin.companies.index')
            ->with('success', '✅ Компания "' . $company->name . '" создана успешно!');
    }

    public function show(Company $company)
    {
        $users = User::where('company_id', $company->id)
            ->withCount('documents')
            ->get();

        $documents = Document::whereHas('creator', function ($q) use ($company) {
            $q->where('company_id', $company->id);
        })->latest()->take(20)->get();

        $stats = [
            'total_users' => $users->count(),
            'online_users' => $users->filter(fn($u) => $u->isOnline())->count(),
            'total_documents' => $documents->count(),
            'admins' => $users->filter(fn($u) => $u->isAdmin())->count(),
        ];

        return view('superadmin.companies.show', compact('company', 'users', 'documents', 'stats'));
    }

    public function edit(Company $company)
    {
        $users = User::where('company_id', $company->id)->get();
        return view('superadmin.companies.edit', compact('company', 'users'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        $company->update($data);

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Компания обновлена');
    }

    public function destroy(Company $company)
    {
        if ($company->users()->count() > 0) {
            return back()->with('error', 'Нельзя удалить компанию с пользователями');
        }

        $company->delete();

        return back()->with('success', 'Компания удалена');
    }
}