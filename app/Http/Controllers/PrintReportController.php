<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrintReportController extends Controller
{
    public function generateReport(Request $request, $model, $view, $fileName)
    {
        $dataQuery = $model::query();

        $dateStart = $request->date_start ? $request->date_start . '-01' : null;
        $dateEnd = $request->date_end ? date('Y-m-t', strtotime($request->date_end . '-01')) : null;

        if ($dateStart) {
            $dataQuery->whereDate('created_at', '>=', $dateStart);
        }
        if ($dateEnd) {
            $dataQuery->whereDate('created_at', '<=', $dateEnd);
        }

        $data = $dataQuery->get();

        if (!$dateStart && !$dateEnd) {
            $data = $model::all();
        }

        $pdf = \PDF::loadView($view, [
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'data' => $data,
        ])->setPaper('a4', 'portrait');

        $fileName = "cetak-data-{$fileName}";
        if ($dateStart && $dateEnd) {
            $fileName .= "-[$request->date_start-$request->date_end]";
        } elseif ($dateStart) {
            $fileName .= "-[$request->date_start]";
        } elseif ($dateEnd) {
            $fileName .= "-[$request->date_end]";
        }
        $fileName .= ".pdf";

        return $pdf->stream($fileName);
    }

    public function student(Request $request)
    {
        return $this->generateReport($request, \App\Models\Student::class, 'pdf.student', 'siswa');
    }

    public function student_parent(Request $request)
    {
        return $this->generateReport($request, \App\Models\StudentParent::class, 'pdf.student-parent', 'orangtua-siswa');
    }

    public function grand(Request $request)
    {
        return $this->generateReport($request, \App\Models\Grade::class, 'pdf.grade', 'kelas');
    }

    public function teacher(Request $request)
    {
        return $this->generateReport($request, \App\Models\Teacher::class, 'pdf.teacher', 'guru');
    }

    public function school_subject(Request $request)
    {
        return $this->generateReport($request, \App\Models\SchoolSubject::class, 'pdf.school-subject', 'mata-pelajran');
    }

    public function on_duty(Request $request)
    {
        return $this->generateReport($request, \App\Models\OnDuty::class, 'pdf.on-duty', 'piket');
    }
}
