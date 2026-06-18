    <?php

    use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
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
    });

    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| Dashboard User
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])
    ->prefix('dashboard/user')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'userDashboard'])->name('dashboard.users');
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
        Route::post('/rooms', [RoomController::class, 'store'])->name('admin.rooms.store');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('admin.rooms.update');
        Route::post('/tenants', [TenantController::class, 'store'])->name('admin.tenants.store');
        Route::put('/tenants/{tenant}', [TenantController::class, 'update'])->name('admin.tenants.update');
        Route::post('/maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('admin.maintenance.update-status');
        Route::post('/notifications/send', [NotificationController::class, 'send'])->name('admin.notifications.send');
        Route::post('/messages/{message}/read', [MessageController::class, 'markRead'])->name('admin.messages.read');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('admin.messages.destroy');
    });

Route::get('/debug-auth', function () {
    return [
        'check' => auth()->check(),
        'id' => auth()->id(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
    ];
})->middleware('web');

Route::get('/session-test', function () {
    session(['test' => 'farrell']);

    return 'saved';
});

Route::get('/session-check', function () {
    dd(session()->all());
});
