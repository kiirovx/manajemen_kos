<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/booking', [BookingController::class, 'index'])->name('booking');
Route::post('/booking/store', [BookingController::class, 'store'])->middleware('auth')->name('booking.store');
Route::get('/booking/payment/{id}', [BookingController::class, 'paymentPage'])->name('booking.payment.page');
Route::get('/booking/payment/status/{id}', [BookingController::class, 'paymentStatus'])->name('booking.payment.status');
Route::get('/booking/payment/finish/{id}', [BookingController::class, 'paymentFinish'])->name('booking.payment.finish');
Route::get('/payment/bill/finish/{id}', [PaymentController::class, 'billFinish'])->name('payment.bill.finish');
Route::post('/payment/bill/create-snap/{id}', [PaymentController::class, 'createBillSnap'])->name('payment.bill.snap');
Route::get('/booking/invoice/{id}', [BookingController::class, 'invoice'])->name('booking.invoice');
Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');
Route::post('/contact/send', [MessageController::class, 'store'])->name('contact.send');

/*
|--------------------------------------------------------------------------
| Redirect URL Lama / Salah
|--------------------------------------------------------------------------
*/
Route::redirect('/index', '/');
Route::redirect('/index.html', '/');
Route::redirect('/home', '/');
Route::redirect('/login', '/auth/login');
Route::redirect('/admin', '/dashboard/admin');
Route::redirect('/user', '/dashboard/user');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'loginPage'])
            ->name('login.page');
        Route::post('/login/process', [AuthController::class, 'loginProcess'])->name('login.process');
        Route::get('/register', [AuthController::class, 'registerPage'])->name('register.page');
        Route::post('/register/process', [AuthController::class, 'registerProcess'])->name('register.process');

        // Forgot Password Routes
        Route::get('/forgot-password', [AuthController::class, 'forgotPasswordPage'])->name('forgot-password.page');
        Route::post('/forgot-password/verify', [AuthController::class, 'forgotPasswordVerify'])->name('forgot-password.verify');

        // OTP Routes
        Route::get('/otp', [PasswordResetController::class, 'showOtpPage'])->name('otp.page');
        Route::post('/otp/verify', [PasswordResetController::class, 'verifyOtp'])->name('otp.verify');
        Route::post('/otp/resend', [PasswordResetController::class, 'resendOtp'])->name('otp.resend');

        // Reset Password (after OTP verified)
        Route::get('/reset-password', [AuthController::class, 'resetPasswordPage'])->name('reset-password.page');
        Route::post('/reset-password/process', [AuthController::class, 'resetPasswordProcess'])->name('reset-password.process');
    });

    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| Midtrans Callback (Webhook) — Tanpa Auth & CSRF
|--------------------------------------------------------------------------
| Dipanggil langsung oleh server Midtrans.
| Prefix /user agar rapi, tapi TANPA middleware auth.
|--------------------------------------------------------------------------
*/
Route::post('/user/midtrans/callback', [MidtransCallbackController::class, 'handle'])
    ->name('midtrans.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Keep old route for backward compatibility
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

/*
|--------------------------------------------------------------------------
| Dashboard User
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])
    ->prefix('dashboard/user')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'userDashboard'])->name('dashboard.users');
        Route::get('/booking', [DashboardController::class, 'userBookings'])->name('dashboard.user.bookings');
        Route::get('/booking/{id}', [DashboardController::class, 'userBookingDetail'])->name('dashboard.user.booking.detail');
        Route::get('/booking/{id}/invoice', [BookingController::class, 'invoice'])->name('dashboard.user.booking.invoice');
        Route::post('/payment', [PaymentController::class, 'store'])->name('dashboard.payment.store');
        Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('dashboard.maintenance.store');
        Route::put('/profile', [ProfileController::class, 'update'])->name('dashboard.profile.update');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('dashboard.notifications.read-all');
    });

/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('dashboard/admin')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'adminDashboard'])->name('dashboard.admin');
        Route::get('/transactions', [DashboardController::class, 'adminTransactions'])->name('dashboard.admin.transactions');
        Route::get('/reports', [DashboardController::class, 'adminReports'])->name('dashboard.admin.reports');
        Route::get('/export/pdf/{type}', [ExportController::class, 'exportPdf'])->name('dashboard.admin.export.pdf');
        Route::get('/export/excel/{type}', [ExportController::class, 'exportExcel'])->name('dashboard.admin.export.excel');
        Route::post('/rooms', [RoomController::class, 'store'])->name('admin.rooms.store');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('admin.rooms.update');
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('admin.rooms.destroy');
        Route::get('/rooms/{room}/bookings-status', [RoomController::class, 'bookingsStatus'])->name('admin.rooms.bookings-status');
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('admin.bookings.cancel');
        Route::post('/bookings/{booking}/accept-cash', [BookingController::class, 'acceptCash'])->name('admin.bookings.accept-cash');
        Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('admin.bookings.destroy');
        Route::post('/tenants', [TenantController::class, 'store'])->name('admin.tenants.store');
        Route::put('/tenants/{tenant}', [TenantController::class, 'update'])->name('admin.tenants.update');
        Route::post('/tenants/{tenant}/approve', [TenantController::class, 'approve'])->name('admin.tenants.approve');
        Route::post('/tenants/{tenant}/reject', [TenantController::class, 'reject'])->name('admin.tenants.reject');
        Route::post('/tenants/{tenant}/check-out', [TenantController::class, 'checkOut'])->name('admin.tenants.check-out');
        Route::get('/tenants/{tenant}/payments', [PaymentController::class, 'tenantPayments'])->name('admin.tenants.payments');
        Route::post('/tenants/{tenant}/payments', [PaymentController::class, 'storeBill'])->name('admin.tenants.payments.store');
        Route::post('/payments/{payment}/mark-paid', [PaymentController::class, 'markPaid'])->name('admin.payments.mark-paid');
        Route::post('/maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('admin.maintenance.update-status');
        Route::post('/notifications/send', [NotificationController::class, 'send'])->name('admin.notifications.send');
        Route::post('/messages/{message}/read', [MessageController::class, 'markRead'])->name('admin.messages.read');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('admin.messages.destroy');
    });