<?php

namespace AIVIKS\Http\Controllers;

use AIVIKS\Http\Controllers\Controller;
use AIVIKS\Report;
use Illuminate\Http\Request;
use Illuminate\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportController extends Controller
{


    public function index() {
            $reports = DB::table('reports')
            ->orderBy('date', 'title')->get();
            return view('reports/ViewReports', ['reports'=>$reports]); 
    }
    public function editReports() {
        if(Auth::check()) {
            $reports = Report::where('creator_id', '=', Auth::id())->
                orderBy('date', 'title')->get();
            return view('reports/EditReports', ['reports'=>$reports]);
        }
    }
    public function destroy($id) {
        $report = Report::findOrFail($id);
        $yours = $report->creator_id == Auth::id();
        if ($yours) { 
            Storage::disk('s3')->delete($report->path);
            $report->delete();
            return redirect()->back()->with("success", $report->title." ištrinta.");
        } else {
            return redirect()->back()->with("error", $report->title." Jūs negalite ištrinti šio failo.");
        }
    }

    public function download($id) {
        $report = Report::findOrFail($id);
        $exists = Storage::disk('s3')->exists($report->path);
        if($exists) {
            return  Storage::disk('s3')->download($report->path, $report->date.' Ataskaita.pdf');
        }
    }
    public function viewReport($id) {
        $report = Report::findOrFail($id);
        $exists = Storage::disk('s3')->exists($report->path);
        if($exists) {
            return  Storage::disk('s3')->url($report->path);
        }
    }

    public function create(Request $request) {
        $arr = array($request->title, $request->date, $request->file('report'));
        foreach ($arr as $field) {
            if (empty($field)) {
                return redirect()->back()->with("error","Užpildykite visus laukus.  ". 
                                            "Pavadinimas: ".'"'.$request->title.'"  '.
                                            "Data: ".'"'.$request->date.'"  '.
                                            "Failas: ".'"'.$request->file('report').'"  ');
            }
        }
        $result = $this->save( 
            $request->title,  
            $request->date,
            $request->file('report')
        );
        if ($result) {
            return redirect()->back()->with("success", "Ataskaita įkelta.");
        } else {
            return redirect()->back()->with("error","Jums reikia prisijungti.");
        }
    }
    public function save($title, $date, $file) {
        $report = new Report();
        if ( Auth::check() ) {
            $report->creator_id = Auth::id();
        } else {
            return false;
        }
        $report->title = $title;
        $report->date = $date;
        $path = Storage::disk('s3')->putFile('Reports', $file);
        $report->path = $path;
        $report->save();

        return true;
    }

}
