<?php

use App\Http\Controllers\AuthMobileController;
use App\Http\Controllers\PrintReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/login');
// MOBILE HANDLER AUTHENTICATION APPLICATION WEB VIEW
Route::get('/login-mobile', [AuthMobileController::class,'login'])->name('mobile.login');
Route::post('/logout-mobile', [AuthMobileController::class,'logout'])->name('mobile.logout');

Route::middleware('auth', 'verified', 'force.logout')->namespace('App\Livewire')->group(function () {
    /**
     *  MOBILE / FORNTEND UNTUK MOBILE
     */

    Route::namespace('Mobile')->prefix('mobile-app')->name('mobile.')->group(function(){
        /**
         * beranda / home
         */
        Route::get('beranda', Home\Index::class)->name('home');
    });





    /**
     * Print Report / Cetak Laporan
     */
    Route::prefix('cetak-laporan')->middleware('roles:admin')->name('print-report.')->group(function () {
        Route::get('/siswa', [PrintReportController::class, 'student'])->name('student');
        Route::get('/orangtua-siswa', [PrintReportController::class, 'student_parent'])->name('student-parent');
        Route::get('/guru', [PrintReportController::class, 'teacher'])->name('teacher');
        Route::get('/kelas', [PrintReportController::class, 'grand'])->name('grand');
        Route::get('/mata-pelajaran', [PrintReportController::class, 'school_subject'])->name('school-subject');
        Route::get('/piket', [PrintReportController::class, 'on_duty'])->name('on-duty');
        Route::get('/penugasan-kelas', [PrintReportController::class, 'grade_assignment'])->name('grade-assignment');
        Route::get('/pelanggaran-siswa', [PrintReportController::class, 'violation_student'])->name('violation-student');
    });

    /**
     * Report / Laporan
     */
    Route::prefix('laporan')->name('report.')->middleware('roles:admin')->namespace('Report')->group(function () {
        Route::get('/siswa', Student::class)->name('student');
        Route::get('/guru', Teacher::class)->name('teacher');
        Route::get('/kelas', Grand::class)->name('grand');
        Route::get('/mata-pelajaran', SchoolSubject::class)->name('school-subject');
        Route::get('/orangtua-siswa', ParentStudent::class)->name('parent-student');
        Route::get('/piket-siswa', OnDuty::class)->name('on-duty');
        Route::get('/penugasan-kelas', GradeAssignment::class)->name('grade-assignment');
        Route::get('/pelanggaran-siswa', ViolationStudent::class)->name('violation-student');
    });

    /**
     * on duty / piket
     */
    Route::prefix('piket')->name('on-duty.')->middleware('roles:admin')->namespace('OnDuty')->group(function () {
        // Petugas Guru Piket
        Route::prefix('petugas-guru-piket')->name('teacher-duty-status.')->namespace('TeacherDutyStatus')->group(function () {
            Route::get('/',Index::class)->name('index');
        });

        // Violation Type / Jenis Pelanggaran
        Route::prefix('jenis-pelanggaran')->name('violation-type.')->namespace('ViolationType')->group(function () {
            Route::get('/', Index::class)->name('index');
        });

        // Student Violation / Pelanggaran Siswa
        Route::prefix('pelanggaran-siswa')->name('student-violation.')->namespace('Violation')->group(function () {
            Route::get('/', Index::class)->name('index');
            Route::get('/tambah', Create::class)->name('create');
            Route::get('/sunting/{id}', Edit::class)->name('edit');
        });

        // Assignment / Penugasan
        // Route::prefix('penugasan')->name('assignment.')->namespace('Assignment')->group(function () {
        //     Route::get('/', Index::class)->name('index');
        //     Route::get('/tambah', Create::class)->name('create');
        //     Route::get('/sunting/{id}', Edit::class)->name('edit');
        //     Route::get('/lokasi/{id}', Map::class)->name('map');
        // });

        // Grade Assignment / Penugasan Kelas
        Route::prefix('penugasan-kelas')->name('grade-assignment.')->namespace('GradeAssignment')->group(function () {
            Route::get('/', Index::class)->name('index');
            Route::get('/tambah', Create::class)->name('create');
            Route::get('/sunting/{id}', Edit::class)->name('edit');
            Route::get('/lokasi/{id}', Map::class)->name('map');
        });

        // Feedback / Masukan
        // Route::prefix('masukan')->name('feedback.')->namespace('Feedback')->group(function () {
        //     Route::get('/', Index::class)->name('index');
        //     Route::get('/pesan/{id}', Message::class)->name('message');
        // });
    });

    /**
     * school subject / mata pelajaran
     */
    Route::prefix('mata-pelajaran')->name('school-subject.')->middleware('roles:admin')->namespace('SchoolSubject')->group(function () {
        Route::get('/', Index::class)->name('index');
        Route::get('/tambah', Create::class)->name('create');
        Route::get('/sunting/{id}', Edit::class)->name('edit');
    });

    /**
     * student parent / orangtua siswa
     */
    Route::prefix('kelas')->name('grade.')->middleware('roles:admin')->namespace('Grade')->group(function () {
        Route::get('/', Index::class)->name('index');
    });

    /**
     * student parent / orangtua siswa
     */
    Route::prefix('orangtua-siswa')->name('guardian-parent.')->middleware('roles:admin')->namespace('StudentParent')->group(function () {
        Route::get('/', Index::class)->name('index');
        Route::get('/tambah', Create::class)->name('create');
        Route::get('/sunting/{id}', Edit::class)->name('edit');
    });

    /**
     * student / siswa
     */
    Route::prefix('siswa')->name('student.')->middleware('roles:admin')->namespace('Student')->group(function () {
        Route::get('/', Index::class)->name('index');
        Route::get('/tambah', Create::class)->name('create');
        Route::get('/sunting/{id}', Edit::class)->name('edit');
    });

    /**
     * teacher / guru
     */
    Route::prefix('guru')->name('teacher.')->middleware('roles:admin')->namespace('Teacher')->group(function () {
        Route::get('/', Index::class)->name('index');
        Route::get('/tambah', Create::class)->name('create');
        Route::get('/sunting/{id}', Edit::class)->name('edit');
    });

    /**
     * beranda / home
     */
    Route::get('beranda', Home\Index::class)->name('home')
        ->middleware('roles:admin');

    /**
     * user / pengguna
     */
    Route::prefix('pengguna')->name('user.')->middleware('roles:admin')->namespace('User')->group(function () {
        Route::get('/', Index::class)->name('index');
        Route::get('/tambah', Create::class)->name('create');
        Route::get('/sunting/{id}', Edit::class)->name('edit');
    });

    /**
     * setting
     */
    Route::prefix('pengaturan')->name('setting.')->middleware('roles:admin,user')->namespace('Setting')->group(function () {
        Route::redirect('/', 'pengaturan/aplikasi');

        /**
         * Profile
         */
        Route::prefix('profil')->name('profile.')->group(function () {
            Route::get('/', Profile\Index::class)->name('index');
        });

        /**
         * Account
         */
        Route::prefix('akun')->name('account.')->group(function () {
            Route::get('/', Account\Index::class)->name('index');
        });
    });
});
