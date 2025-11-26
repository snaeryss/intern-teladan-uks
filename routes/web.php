<?php

use App\Http\Controllers\Academic\AcademicYearController;
use App\Http\Controllers\Academic\SyncClassesController;
use App\Http\Controllers\Academic\SyncStudentsController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DCU\DentalCheckUpController;
use App\Http\Controllers\DentalDiagnosisController;
use App\Http\Controllers\Doctor\DoctorAccountController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ManageUser\ManageUserController;
use App\Http\Controllers\ManageUser\RoleController;
use App\Http\Controllers\ManageUser\StudentAccountController;
use App\Http\Controllers\ManageUser\StudentAccountMultipleController;
use App\Http\Controllers\MCU\MedicalCheckUpController;
use App\Http\Controllers\MedicalRecord\MedicalRecordListController;
use App\Http\Controllers\MedicalRecord\RecordHistoriesController;
use App\Http\Controllers\MedicalRecord\ReportController;
use App\Http\Controllers\MedicalRecord\WaitingListController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\Print\PrintDocumentController;
use App\Http\Controllers\Student\StudentClassesController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\StudentLiveSearchApiController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;

// DASHBOARD
Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// ACADEMIC MODULE
Route::controller(AcademicYearController::class)
    ->prefix('academic-year')
    ->middleware(['auth', 'role:SuperVisor'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('academic-year');
        Route::post('/store', 'store')
            ->name('academic-year.store');
        Route::post('/update/{academicYear}', 'update')
            ->whereNumber('academicYear')
            ->name('academic-year.update');
    });

Route::controller(PeriodController::class)
    ->prefix('periods')
    ->middleware(['auth', 'role:SuperVisor'])
    ->name('periods.')
    ->group(function () {
        Route::get('/{academicYear}', 'show')
            ->whereNumber('academicYear')
            ->name('show');
        Route::post('/{academicYear}/store', 'store')
            ->whereNumber('academicYear')
            ->name('store');
        Route::post('/{period}/update', 'update')
            ->whereNumber('period')
            ->name('update');
        Route::post('/{period}/toggle-status', 'toggleStatus')
            ->whereNumber('period')
            ->name('toggle-status');
    });

Route::controller(SyncClassesController::class)
    ->prefix('sync/classes')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('sync-classes');
        Route::post('/store', 'store')
            ->name('sync-classes.store');
    });

Route::controller(SyncStudentsController::class)
    ->prefix('sync/students')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('sync-students');
        Route::post('/store', 'store')
            ->name('sync-students.store');
    });

Route::controller(DoctorController::class)
    ->prefix('doctor')
    ->middleware(['auth', 'role:SuperVisor|Principal'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('doctor');
        Route::get('/create', 'create')
            ->name('doctor.create');
        Route::post('/store', 'store')
            ->name('doctor.store');
        Route::get('/{doctor}', 'show')
            ->whereUuid('doctor')
            ->name('doctor.detail');
        Route::post('/{doctor}', 'update')
            ->whereUuid('doctor')
            ->name('doctor.update');
        Route::delete('/{doctor}', 'destroy')
            ->whereUuid('doctor')
            ->name('doctor.destroy');
    });

Route::controller(DoctorAccountController::class)
    ->prefix('doctor/account')
    ->middleware(['auth', 'role:SuperVisor|Principal'])
    ->group(function () {
        Route::post('/store', 'store')
            ->name('doctor.account.store');
        Route::get('/{doctor}', 'show')
            ->whereUuid('doctor')
            ->name('doctor.account.show');
        Route::post('/{doctor}/reset', 'reset')
            ->whereUuid('doctor')
            ->name('doctor.account.reset');
    });

// MANAGE USER MODULE
Route::controller(ManageUserController::class)
    ->prefix('manage-account')
    ->middleware(['auth', 'role:SuperVisor|Principal'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('manage-account');
        Route::get('/create', 'create')
            ->name('manage-account.create');
        Route::post('/store', 'store')
            ->name('manage-account.store');
        Route::get('/{user}', 'show')
            ->name('manage-account.detail');
        Route::post('/{user}/update', 'update')
            ->name('manage-account.update');
    });

Route::controller(RoleController::class)
    ->prefix('roles')
    ->middleware(['auth','role:SuperVisor'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('roles');
        Route::post('/store', 'store')
            ->name('roles.store');
        Route::post('/{role}/update', 'update')
            ->name('roles.update');
    });

Route::controller(StudentAccountController::class)
    ->prefix('student/account')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC'])
    ->group(function () {
        Route::post('/store', 'store')
            ->name('student.account.store');
        Route::get('/{student}', 'show')
            ->name('student.account.show');
    });

Route::controller(StudentAccountMultipleController::class)
    ->prefix('student/accounts')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC'])
    ->group(function () {
        Route::post('/store', 'store')
            ->name('student.multiple.account.store');
        Route::post('/export', 'export')
            ->name('student.multiple.account.export');
    });

// STUDENT
Route::controller(StudentClassesController::class)
    ->prefix('student/classes')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('student.classes');
        Route::get('/{class_level}-{class_name}-{group_year}', 'show')
            ->whereNumber('group_year')
            ->name('student.classes.show');
    });

Route::get('/student/live-search', [StudentLiveSearchApiController::class, 'search'])
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC|Doktor|Doktor Gigi|Perawat UKS'])
    ->name('student.live-search');

Route::controller(StudentController::class)
    ->prefix('student')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC|Doktor|Doktor Gigi|Perawat UKS'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('student');
        Route::get('/{student}', 'show')
            ->whereUuid('student')
            ->name('student.detail');
        Route::post('/{student}', 'update')
            ->whereUuid('student')
            ->name('student.update');
    });

// MEDICAL
Route::controller(DentalDiagnosisController::class) 
    ->prefix('dental-diagnoses')
    ->middleware(['auth', 'role:SuperVisor|Principal'])
    ->name('dental-diagnoses.')
    ->group(function () {
        Route::get('/', 'index')
            ->name('index');

        Route::middleware(['role:SuperVisor'])->group(function () {
            Route::post('/store', 'store')
                ->name('store');
            Route::match(['PUT', 'POST'], '/{dentalDiagnosis}/update', 'update')
                ->name('update');
            Route::delete('/{dentalDiagnosis}', 'destroy')
                ->name('destroy');
        });
    });

// MEDICAL
Route::controller(DentalDiagnosisController::class)
    ->prefix('dental-diagnoses')
    ->middleware(['auth', 'role:SuperVisor|Principal'])
    ->name('dental-diagnoses.')
    ->group(function () {
        Route::get('/', 'index')
            ->name('index');

        Route::middleware(['role:SuperVisor'])->group(function () {
            Route::post('/store', 'store')
                ->name('store');
            Route::match(['PUT', 'POST'], '/{dentalDiagnosis}/update', 'update')
                ->name('update');
            Route::delete('/{dentalDiagnosis}', 'destroy')
                ->name('destroy');
        });
    });

Route::controller(DentalCheckUpController::class)
    ->prefix('dcu')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC|Doktor|Doktor Gigi|Perawat UKS'])
    ->name('dcu.')
    ->group(function () {
        Route::get('/form/{student}/{period}', 'showForm')
            ->name('form');
        Route::get('/form/{student}/{period}/diagnoses', 'getDiagnoses') 
            ->name('get-diagnoses');
        Route::get('/form/{student}/{period}/evaluator-name', 'getEvaluatorDoctorName')
            ->name('evaluator-name');
        Route::post('/form/{student}/{period}/save-step', 'saveStep')
            ->name('save-step');
        Route::post('/store', 'store')
            ->name('store');
    });

// SCREENING / MCU Routes
Route::controller(MedicalCheckUpController::class)
    ->prefix('screening')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC|Doktor|Doktor Gigi|Perawat UKS'])
    ->name('screening.')
    ->group(function () {
        Route::get('/form/{student}/{period}', 'show')
            ->name('form');
        Route::get('/form/{student}/{period}/get-data', 'getExistingData')
            ->name('get-data');
        Route::get('/form/{student}/{period}/evaluator-name', 'getEvaluatorDoctorName')
            ->name('evaluator-name');
        Route::post('/form/{student}/{period}/save-step', 'saveStep')
            ->name('save-step');
        Route::post('/store', 'store')
            ->name('store');
    });

Route::controller(MedicalCheckUpController::class)
    ->prefix('medical-checkup')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC|Doktor|Doktor Gigi|Perawat UKS'])
    ->name('medical-checkup.')
    ->group(function () {
        Route::get('/form/{student}/{period}', 'show')
            ->name('form');
    });

Route::post('/mcu/evaluate-bmi', [MedicalCheckUpController::class, 'evaluateBmi'])
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC|Doktor|Doktor Gigi|Perawat UKS'])
    ->name('mcu.evaluate-bmi');

Route::controller(MedicalRecordListController::class)
    ->prefix('medical-record')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC|Doktor|Doktor Gigi|Perawat UKS'])
    ->name('medical-record.')
    ->group(function () {
        Route::get('/all', 'index')
            ->name('all');
    });

Route::controller(RecordHistoriesController::class)
    ->prefix('record-histories')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC|Doktor|Doktor Gigi|Perawat UKS'])
    ->name('record-histories.')
    ->group(function () {
        Route::get('/', 'index')
            ->name('index');
    });

Route::controller(VisitController::class)
    ->prefix('visits')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', 'index')
            ->name('visits.index');
        Route::get('/create', 'create')
            ->name('visits.create');
        Route::post('/', 'store')
            ->name('visits.store');
        Route::get('/{visit}', 'show')
            ->name('visits.show');
        Route::get('/{visit}/edit', 'edit')
            ->name('visits.edit');
        Route::put('/{visit}', 'update')
            ->name('visits.update');
        Route::delete('/{visit}', 'destroy')
            ->name('visits.destroy');
    });

Route::controller(WaitingListController::class)
    ->prefix('waiting-list')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC|Perawat UKS'])
    ->name('waiting-list.')
    ->group(function () {
        Route::get('/', 'index')
            ->name('index');
        Route::get('/{level}/form', 'show')
            ->name('form');
        Route::post('/store', 'store')
            ->name('store');
    });

// REPORTS & PRINT
Route::controller(PrintDocumentController::class)
    ->prefix('print-documents')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC'])
    ->name('print-documents.')
    ->group(function () {
        Route::get('/dcu', 'dcu')
            ->name('dcu');
        Route::get('/dcu/show', 'showDcu')
            ->name('dcu.show');
        Route::get('/mcu', 'mcu')
            ->name('mcu');
        Route::get('/mcu/show', 'showMcu')
            ->name('mcu.show');
        Route::get('/visits', 'visits')
            ->name('visits');
        Route::get('/visits/show', 'showVisits')
            ->name('visits.show');
        Route::get('/visits/export', 'exportVisits')
            ->name('visits.export');
    });

Route::controller(ReportController::class)
    ->prefix('reports')
    ->middleware(['auth', 'role:SuperVisor|Principal|PIC'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('reports');
        Route::get('/{id}', 'show')
            ->name('reports.detail');
        Route::get('/{id}/{activity}', 'showActivityDetail')
            ->name('reports.activity.detail');
        Route::get('/{id}/{activity}/{class_id}', 'showStudentList')
            ->name('reports.student.list');
        Route::post('/{id}/{activity}/{class_id}/export', 'exportStudentList')
            ->name('reports.student.list.export');
    });

// SYSTEM
Route::controller(AccountController::class)
    ->prefix('account')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', 'index')
            ->name('account');
        Route::post('/update', 'update')
            ->name('account.update');
    });

Route::controller(ArtisanController::class)
    ->prefix('artisan')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', 'index')
            ->name('artisan');
        Route::post('/do-command', 'doCommand')
            ->name('artisan.do-command');
    });

Route::controller(AuthController::class)
    ->prefix('auth')
    ->group(function () {
        Route::get('/', 'index')
            ->middleware('guest')
            ->name('auth');
        Route::post('/', 'login')
            ->name('auth.login');
        Route::post('/logout', 'logout')
            ->name('auth.logout');
    });

Route::controller(LocationController::class)
    ->prefix('locations')
    ->middleware(['auth', 'role:SuperVisor'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('locations');
        Route::post('/store', 'store')
            ->name('locations.store');
        Route::post('/{location}/update', 'update')
            ->name('locations.update');
        Route::delete('/{location}', 'destroy')
            ->name('locations.destroy');
    });