<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\KategoriArsip;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\LogAktivitas;
use App\Models\KlasifikasiArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
}
