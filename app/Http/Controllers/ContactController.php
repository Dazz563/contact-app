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

        // DB::enableQueryLog();
        $contacts = Contact::latest()->where(function ($query) {
            if ($companyId = request()->query('company_id')) {
                $query->where('company_id', $companyId);
            }
        })->where(function ($query) {
            if ($search = request()->query('search')) {
                $query->where("first_name", "LIKE", "%{$search}%");
                $query->orWhere("last_name", "LIKE", "%{$search}%");
                $query->orWhere("email", "LIKE", "%{$search}%");
            }
        })->paginate(10);
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

        return back()->with('message', 'Contact has been deleted successfully');
    }
}
