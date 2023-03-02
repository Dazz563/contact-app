<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
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

        // DB::enableQueryLog();
        $contacts = Contact::allowedTrash()
            ->allowedSorts(['first_name', 'last_name', 'email'], "-id")
            ->allowedFilters('company_id')
            ->allowedSearch('first_name', 'last_name', 'email')
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

    public function show(Contact $contact)
    {
        return view('contacts.show')->with('contact', $contact);
    }

    public function store(ContactRequest $req)
    {
        // dd($req);
        // dd($req->input());
        // dd($req->input('first_name'));
        // dd($req->input('last_name'));

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

    public function edit(Contact $contact)
    {
        $companies = $this->company->pluck();

        return view('contacts.edit', compact('companies', 'contact'));
    }

    public function update(ContactRequest $req, Contact $contact)
    {
        $contact->update($req->all());

        return redirect()->route('contacts.index')->with('message', 'Contact has been updated successfully');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        $redirect = request()->query('redirect');

        return ($redirect ? redirect()->route($redirect) : back())
            ->with('message', 'Contact has been moved to trash.')
            ->with('undoRoute',  $this->getUndoRoute('contacts.restore', $contact));
    }

    public function restore(Contact $contact)
    {
        $contact->restore();

        return back()
            ->with('message', 'Contact has been restored from trash')
            ->with('undoRoute', $this->getUndoRoute('contacts.destroy', $contact));
    }

    protected function getUndoRoute($name, $resource)
    {
        return request()->missing('undo') ? route($name, [$resource->id, 'undo' => true]) : null;
    }

    public function forceDelete(Contact $contact)
    {
        $contact->forceDelete();

        return back()
            ->with('message', 'Contact has been removed permanently');
    }
}
