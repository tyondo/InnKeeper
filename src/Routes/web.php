<?php
use Illuminate\Support\Facades\Route;

use Tyondo\Innkeeper\Http\Controllers\AuthController;
use Tyondo\Innkeeper\Http\Controllers\OrganizationController;

Route::prefix('innkeeper')->name('innkeeper.')->group(function (){
    Route::name('auth.')->group(function (){
        Route::get('/',[AuthController::class,'loginForm'])->name('login');
        Route::post('/',[AuthController::class,'login'])->name('login.post');
        Route::post('logout',[AuthController::class,'logoutUser'])->name('logout');
    });

    Route::get('dashboard',[OrganizationController::class,'dashboard'])->name('dashboard');



    Route::prefix('organizations')->name('organizations.')->group(function (){
        Route::get('/',[OrganizationController::class,'listOrganizations'])->name('list');
        Route::get('create',[OrganizationController::class,'createOrganizationForm'])->name('create');
        Route::post('store',[OrganizationController::class,'storeOrganization'])->name('store');
        Route::delete('destroy/{organizationId}',[OrganizationController::class,'destroyOrganization'])->name('destroy');
    });
});




