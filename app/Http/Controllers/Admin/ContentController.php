<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContentController extends Controller
{
    public function showResponsePage()
    {
        return view('admin.content-management.response.response');
    }

    public function showAddResponsePage()
    {
        return view('admin.content-management.response.add-response');
    } 

    public function showEditResponsePage()
    {
        return view('admin.content-management.response.edit-response');
    }
}