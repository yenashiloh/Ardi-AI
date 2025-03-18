<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContentController extends Controller
{
    public function showDocumentsPage()
    {
        return view('admin.content-management.documents.documents');
    }

    public function showAddDocumentPage()
    {
        return view('admin.content-management.documents.add-document');
    }

    public function showEditDocumentPage()
    {
        return view('admin.content-management.documents.edit-document');
    }
}