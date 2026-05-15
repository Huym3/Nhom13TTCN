<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Controllers Student
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\ExamController     as StudentExam;
use App\Http\Controllers\Student\ResultController   as StudentResult;

// Controllers Teacher
use App\Http\Controllers\Teacher\DashboardController  as TeacherDashboard;
use App\Http\Controllers\Teacher\QuestionController   as TeacherQuestion;
use App\Http\Controllers\Teacher\ExamController       as TeacherExam;

// Controllers Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController      as AdminUser;

// ──────────────────────────────────────────────────────────
// TRANG CHỦ
// ──────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ──────────────────────────────────────────────────────────
// AUTH
// ──────────────────────────────────────────────────────────
Route::get ('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::get ('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// ──────────────────────────────────────────────────────────
// STUDENT
// ──────────────────────────────────────────────────────────
Route::middleware(['checklogin', 'checkrole:Student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [StudentDashboard::class, 'index'])
            ->name('dashboard');

        // Danh sách đề thi
        Route::get('/exams', [StudentExam::class, 'index'])
            ->name('exams');

        // Bắt đầu làm bài
        Route::get('/exams/{id}/start', [StudentExam::class, 'start'])
            ->name('exams.start');

        // Trang làm bài
        Route::get('/exams/{id}/do', [StudentExam::class, 'show'])
            ->name('exams.do');

        // Lưu đáp án TN (AJAX)
        Route::post('/exams/save-tn', [StudentExam::class, 'saveTN'])
            ->name('exams.saveTN');

        // Lưu đáp án Đúng/Sai (AJAX)
        Route::post('/exams/save-ds', [StudentExam::class, 'saveDS'])
            ->name('exams.saveDS');

        // Lưu đáp án số (AJAX)
        Route::post('/exams/save-so', [StudentExam::class, 'saveSo'])
            ->name('exams.saveSo');

        // Nộp bài
        Route::post('/exams/submit', [StudentExam::class, 'submit'])
            ->name('exams.submit');

        // Xem kết quả 1 bài làm
        Route::get('/results/{maBaiLam}', [StudentResult::class, 'show'])
            ->name('results.show');

        // Lịch sử làm bài
        Route::get('/results', [StudentResult::class, 'index'])
            ->name('results.index');
    });

// ──────────────────────────────────────────────────────────
// TEACHER
// ──────────────────────────────────────────────────────────
Route::middleware(['checklogin', 'checkrole:Teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [TeacherDashboard::class, 'index'])
            ->name('dashboard');

        // Ngân hàng câu hỏi
        Route::get   ('/questions',          [TeacherQuestion::class, 'index'])  ->name('questions.index');
        Route::get   ('/questions/create',   [TeacherQuestion::class, 'create']) ->name('questions.create');
        Route::post  ('/questions',          [TeacherQuestion::class, 'store'])  ->name('questions.store');
        Route::get   ('/questions/{id}/edit',[TeacherQuestion::class, 'edit'])   ->name('questions.edit');
        Route::put   ('/questions/{id}',     [TeacherQuestion::class, 'update']) ->name('questions.update');
        Route::delete('/questions/{id}',     [TeacherQuestion::class, 'destroy'])->name('questions.destroy');

        // Quản lý đề thi
        Route::get   ('/exams',              [TeacherExam::class, 'index'])      ->name('exams.index');
        Route::get   ('/exams/create',       [TeacherExam::class, 'create'])     ->name('exams.create');
        Route::post  ('/exams',              [TeacherExam::class, 'store'])      ->name('exams.store');
        Route::get   ('/exams/{id}/edit',    [TeacherExam::class, 'edit'])       ->name('exams.edit');
        Route::put   ('/exams/{id}',         [TeacherExam::class, 'update'])     ->name('exams.update');
        Route::delete('/exams/{id}',         [TeacherExam::class, 'destroy'])    ->name('exams.destroy');

        // Thêm câu hỏi vào đề
        Route::post('/exams/{id}/add-question', [TeacherExam::class, 'addQuestion'])
            ->name('exams.addQuestion');
        // Xóa câu hỏi khỏi đề

        Route::delete('/exams/{id}/remove-question', [TeacherExam::class, 'removeQuestion'])
            ->name('exams.removeQuestion');

        // Publish đề (Draft → Published)
        Route::put('/exams/{id}/publish', [TeacherExam::class, 'publish'])
            ->name('exams.publish');

        Route::put('/exams/{id}/unpublish', [TeacherExam::class, 'unpublish'])
    ->name('exams.unpublish');

        // Xem thống kê 1 đề
        Route::get('/exams/{id}/stats', [TeacherExam::class, 'stats'])
            ->name('exams.stats');
    });

        // Xem chi tiết bài làm của học sinh (thêm dòng này)
Route::get('/exams/{id}/bai-lam/{maBaiLam}', [TeacherExam::class, 'xemBaiLam'])
    ->name('exams.xemBaiLam');

// ──────────────────────────────────────────────────────────
// ADMIN
// ──────────────────────────────────────────────────────────
Route::middleware(['checklogin', 'checkrole:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');

        // Quản lý người dùng
        Route::get   ('/users',           [AdminUser::class, 'index'])  ->name('users.index');
        Route::get   ('/users/{id}',      [AdminUser::class, 'show'])   ->name('users.show');
        Route::put   ('/users/{id}/ban',  [AdminUser::class, 'ban'])    ->name('users.ban');
        Route::put   ('/users/{id}/unban',[AdminUser::class, 'unban'])  ->name('users.unban');
        Route::delete('/users/{id}',      [AdminUser::class, 'destroy'])->name('users.destroy');
    });