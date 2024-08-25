<?php

use App\Http\Controllers\Api\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FarmerController;
use App\Http\Controllers\Api\InspectionController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\CropController;







/*FarmerController
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'loginUser'])->name('login');


Route::post('/create-pin', [AuthController::class, 'createPin'])->name('create.pin');
Route::post('/verify-pin', [AuthController::class, 'verifyPin'])->name('verify.pin');




Route::middleware('auth:sanctum')->group(function () {
    //homescreen
    Route::post('/verifyUserIdAndIdNumber', [AuthController::class, 'verifyUserIdAndIdNumber'])->name('verifyUserIdAndIdNumber');
    Route::post('/verify-code', [AuthController::class, 'verify_code'])->name('verify.code');

    //farmers
    Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/getFarmers', [FarmerController::class, 'getFarmers']);
Route::get('/getFarmer/{id}', [FarmerController::class, 'getFarmer']);

Route::get('/getSuppliers', [SupplierController::class, 'getSuppliers']);
Route::get('/getSupplier/{id}', [SupplierController::class, 'getSupplier']);
// routes/api.php

Route::get('/search-farmers', [FarmerController::class, 'searchFarmers']);

//


Route::get('/crops/{id}', [CropController::class, 'index']);

Route::get('/getRequirements/{id}', [InspectionController::class, 'getRequirements']);
Route::get('/getRequirementsLarge/{id}', [InspectionController::class, 'getRequirementsLarge']);

Route::get('/existing-map-details/{id}', [InspectionController::class, 'getDetails']);

Route::get('/inspections/{farmerId}', [InspectionController::class, 'index']);
Route::post('/savePolygons/{id}', [InspectionController::class, 'savePolygonData']);
Route::post('/savePoint/{id}', [InspectionController::class, 'savePointData']);

Route::post('/inspections/save', [InspectionController::class, 'store'])->name('inspections.store');
Route::post('/inspections/complete', [InspectionController::class, 'updateStatus'])->name('inspections.complete');

Route::post('/add-crop', [CropController::class, 'store']);
Route::post('/delete-crop/{id}', [CropController::class, 'destroy']);


Route::post('/inspections/store-signature', [InspectionController::class, 'storeSignature']);


Route::get('/getUserDataWithInspectionStatistics', [AuthController::class, 'getUserDataWithInspectionStatistics']);

Route::post('/storeFarmer', [FarmerController::class, 'storeFarmer']);
Route::post('/update-farmer', [FarmerController::class, 'updateField'])->name('update-farmer');


Route::post('/storeSupplier', [FarmerController::class, 'storeSupplier']);
Route::post('/update-supplier', [FarmerController::class, 'updateField'])->name('update-supplier');

//getChapterData
Route::get('/getStatistics', [StatisticsController::class, 'getStatistics']);
Route::get('/getChapterData', [StatisticsController::class, 'getChapterData']);


});
