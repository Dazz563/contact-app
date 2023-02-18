<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\CompanyRepository;

class ContactController extends Controller
{


    public function __construct(protected CompanyRepository $company)
    {
    }

    public function index(CompanyRepository $company, Request $req)
    {
        $companies = $this->company->pluck();
        $query = Contact::query();
        if (request()->query('trash')) {
            $query->onlyTrashed();
        }
        // DB::enableQueryLog();
        $contacts = $query->allowedSorts('first_name')
            ->allowedFilters('company_id')
            ->allowedSearch(['first_name', 'last_name', 'email'])
            ->paginate(10);
        // dump(DB::getQueryLog());

        return view('contacts.index', compact('contacts', 'companies'));
    }

    public function create()
    {
        // dd(request()->path());
        // dd(request()->method());
        // dd(request()->url());
        // dd(request()->ip());
        // dd(request()->isMethod('post'));
        $companies = $this->company->pluck();
        $contact = new Contact();

        return view('contacts.create', compact('companies', 'contact'));
    }

    public function show($id)
    {

        $contact = Contact::findOrFail($id);
        return view('contacts.show')->with('contact', $contact);
    }

    public function store(Request $req)
    {
        // dd($req);
        // dd($req->input());
        // dd($req->input('first_name'));
        // dd($req->input('last_name'));

        $req->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'company_id' => 'required|exists:companies,id',
        ]);
        // dd($req->all());
        Contact::create($req->all());

        // sending the res as JSON
        // $contact = Contact::create($req->all());
        // return response()->json([
        //     'success' => true,
        //     'data' => $contact,
        // ]);

        return redirect()->route('contacts.index')->with('message', 'Contact has been added successfully');
    }

    public function edit($id)
    {
        $companies = $this->company->pluck();

        $contact = Contact::findOrFail($id);
        return view('contacts.edit', compact('companies', 'contact'));
    }

    public function update(Request $req, $id)
    {
        $contact = Contact::findOrFail($id);

        $req->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'company_id' => 'required|exists:companies,id',
        ]);
        $contact->update($req->all());

        return redirect()->route('contacts.index')->with('message', 'Contact has been updated successfully');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        $redirect = request()->query('redirect');

        return ($redirect ? redirect()->route($redirect) : back())
            ->with('message', 'Contact has been moved to trash.')
            ->with('undoRoute',  $this->getUndoRoute('contacts.restore', $contact));
    }

    public function restore($id)
    {
        $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->restore();

        return back()
            ->with('message', 'Contact has been restored from trash')
            ->with('undoRoute', $this->getUndoRoute('contacts.destroy', $contact));
    }

    protected function getUndoRoute($name, $resource)
    {
        return request()->missing('undo') ? route($name, [$resource->id, 'undo' => true]) : null;
    }

    public function forceDelete($id)
    {
        $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->forceDelete();

        return back()
            ->with('message', 'Contact has been removed permanently');
    }
}
