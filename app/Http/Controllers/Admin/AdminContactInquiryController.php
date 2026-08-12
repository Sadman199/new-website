<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactInquiry;
use Illuminate\Http\Request;

class AdminContactInquiryController extends AdminController
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = ContactInquiry::query()->latest();

        if ($status === ContactInquiry::STATUS_NEW) {
            $query->where('status', ContactInquiry::STATUS_NEW);
        } elseif ($status === ContactInquiry::STATUS_READ) {
            $query->where('status', ContactInquiry::STATUS_READ);
        } elseif ($status === ContactInquiry::STATUS_ARCHIVED) {
            $query->where('status', ContactInquiry::STATUS_ARCHIVED);
        }

        $inquiries = $query->paginate(20)->withQueryString();

        $counts = [
            'all' => ContactInquiry::count(),
            'new' => ContactInquiry::where('status', ContactInquiry::STATUS_NEW)->count(),
            'read' => ContactInquiry::where('status', ContactInquiry::STATUS_READ)->count(),
            'archived' => ContactInquiry::where('status', ContactInquiry::STATUS_ARCHIVED)->count(),
        ];

        return view('admin.contact_inquiries.index', compact('inquiries', 'status', 'counts'));
    }

    public function show(ContactInquiry $inquiry)
    {
        $inquiry->markRead();

        return view('admin.contact_inquiries.show', compact('inquiry'));
    }

    public function archive(ContactInquiry $inquiry)
    {
        $inquiry->update(['status' => ContactInquiry::STATUS_ARCHIVED]);

        return $this->flashSuccess('admin_contact_inquiries.index', 'Inquiry archived.');
    }

    public function destroy(ContactInquiry $inquiry)
    {
        $inquiry->delete();

        return $this->flashSuccess('admin_contact_inquiries.index', 'Inquiry deleted.');
    }
}
