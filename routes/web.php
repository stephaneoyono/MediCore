<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController as MainDashboardController;
use App\Http\Controllers\Patient\PortalController as PatientPortalController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorCtrl;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Doctor\ScheduleController as DocScheduleCtrl;
use App\Http\Controllers\Doctor\MedicalRecordController as DocMedicalRecordCtrl;
use App\Http\Controllers\Doctor\HospitalNoteController as DocHospitalNoteCtrl;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Doctor\AppointmentController as DocAppointmentCtrl;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Patient\AppointmentController as PtnAppoinmentCtrl;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

Route::get('/find-doctor', [HomeController::class, 'findDoctor'])->name('find.doctor');

Route::get('/appointment-booking', [AppointmentController::class, 'create'])->name('appointment.booking');

Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/appointments/{appointment}/download-pdf', [AppointmentController::class, 'downloadPdf'])->name('appointments.download-pdf');
Route::get('/api/appointments/{bookingId}', [AppointmentController::class, 'getByBookingId'])->name('api.appointments.show');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','verified'])->group(function() {

    Route::get('/dashboard', [MainDashboardController::class, 'index'])->name('dashboard');

    // Patient Routes
    Route::middleware('role:patient')->group(function() {
        Route::get('/patient/book-appointment', [PatientPortalController::class, 'bookAppointment'])->name('patient.book-appointment');
        Route::get('/patient/medical-records', [PatientPortalController::class, 'medicalRecords'])->name('patient.medical-records');
        Route::get('/patient/profile', [PatientPortalController::class, 'profile'])->name('patient.profile');
        Route::resource('patient/appointments', PtnAppoinmentCtrl::class, ['as' => 'patient'])->only(['index', 'show']);
        Route::get('patient/appointments/{appointment}/download-pdf', [PtnAppoinmentCtrl::class, 'downloadPdf'])->name('patient.appointments.download-pdf');
        Route::get('patient/appointments/{appointment}/prescriptions/{prescription}/download-pdf', [PtnAppoinmentCtrl::class, 'downloadPrescriptionPdf'])->name('patient.prescriptions.download-pdf');
    });

    //Admin Routes
    Route::middleware('role:admin')->group(function(){

        Route::get('/admin/doctors', [AdminPageController::class, 'doctors'])->name('admin.doctors.index');

        Route::get('/admin/doctors/list', [AdminDoctorCtrl::class, 'index'])->name('admin.doctors.list');

        Route::post('/admin/doctors',[AdminDoctorCtrl::class, 'store'])->name('admin.doctors.store');

        Route::put('/admin/doctors/{doctor}',[AdminDoctorCtrl::class, 'update'])->name('admin.doctors.update');

        Route::delete('/admin/doctors/{doctor}',[AdminDoctorCtrl::class, 'destroy'])->name('admin.doctors.destroy');

        Route::get('/admin/schedules', [AdminPageController::class, 'schedules'])->name('admin.schedules.index');
        Route::get('/admin/schedules/list', [AdminScheduleController::class,'index'])->name('admin.schedules.list');

        Route::get('/admin/appointments', [AppointmentController::class, 'index'])->name('admin.appointments.index');
        Route::get('/admin/appointments/{doctor}/{date}', [AppointmentController::class, 'showByDoctorDate'])->name('admin.appointments.show.by.doctor.date');
        Route::get('/admin/appointments/{appointment}', [AppointmentController::class, 'show'])->name('admin.appointments.show');
        Route::put('/admin/appointments/{appointment}', [AppointmentController::class, 'update'])->name('admin.appointments.update');

    });

    //Doctors Routes

    Route::middleware(['auth','verified','role:doctor'])->group(function(){

        Route::get('/doctor', [MainDashboardController::class, 'doctor'])->name('doctor.home');

        Route::get('/doctor/schedules', [MainDashboardController::class, 'doctorSchedules'])->name('doctor.schedules');

        Route::get('/doctor/schedules/list', [DocScheduleCtrl::class, 'index']);
        Route::post('/doctor/schedules', [DocScheduleCtrl::class, 'store']);
        Route::put('/doctor/schedules/{schedule}', [DocScheduleCtrl::class, 'update']);
        Route::delete('/doctor/schedules/{schedule}', [DocScheduleCtrl::class, 'destroy']);

        Route::get('/doctor/appointments', [DocAppointmentCtrl::class, 'index'])->name('doctor.appointments.index');
        Route::get('/doctor/appointments/{appointment}', [DocAppointmentCtrl::class, 'show'])->name('doctor.appointments.show');
        Route::put('/doctor/appointments/{appointment}', [DocAppointmentCtrl::class, 'update'])->name('doctor.appointments.update');
        Route::post('/doctor/appointments/{appointment}/prescription', [DocAppointmentCtrl::class, 'storePrescription'])->name('doctor.appointments.store-prescription');
        Route::get('/doctor/appointments/{appointment}/prescriptions/{prescription}/download-pdf', [DocAppointmentCtrl::class, 'downloadPrescriptionPdf'])->name('doctor.appointments.download-prescription-pdf');
        Route::get('/doctor/medical-records', [DocMedicalRecordCtrl::class, 'index'])->name('doctor.medical-records.index');
        Route::post('/doctor/appointments/{appointment}/medical-records', [DocMedicalRecordCtrl::class, 'store'])->name('doctor.medical-records.store');
        Route::get('/doctor/hospital-notes', [DocHospitalNoteCtrl::class, 'index'])->name('doctor.hospital-notes.index');
        Route::post('/doctor/hospital-notes', [DocHospitalNoteCtrl::class, 'store'])->name('doctor.hospital-notes.store');
        Route::put('/doctor/hospital-notes/{hospitalNote}', [DocHospitalNoteCtrl::class, 'update'])->name('doctor.hospital-notes.update');
        Route::delete('/doctor/hospital-notes/{hospitalNote}', [DocHospitalNoteCtrl::class, 'destroy'])->name('doctor.hospital-notes.destroy');
    });

});

Route::get('/doctor/{id}', [HomeController::class, 'doctorDetail'])->name('doctor.detail');
