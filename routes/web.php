<?php

use Dcblogdev\Dropbox\Dropbox;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


//Route::group(['domain' => 'app.austrianweddingaward.at'], function () {
Route::get('/', function () {
    return view('welcome');
});

// dropbox
Route::get('/first-connect-to-dropbox', 'ProjectController@first_connect_to_dropbox');
Route::get('/first-connect-to-dropbox', 'ProjectController@first_connect_to_dropbox');
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('dropbox', function () {
        $dropBox = new Dropbox();
        if (!$dropBox->isConnected()) {
            return redirect(env('DROPBOX_OAUTH_URL'));
        } else {
            //display your details
            return $dropBox->post('users/get_current_account');
        }
    });

    Route::get('dropbox/connect', function () {
        $dropBox = new Dropbox();
        return $dropBox->connect();
    });

    Route::get('dropbox/disconnect', function () {
        $dropBox = new Dropbox();
        return $dropBox->disconnect('app/dropbox');
    });
});


Route::get('/myform', function () {
    return view('myform');
});

Route::get('/project-insert', function () {
    return view('project-insert');
});
/*
Route::get('/project-show', function () {
    return view('project-show');
});
*/
Route::get('/project-bewerten', function () {
    return view('project-bewerten');
});

/*Route::get('/project-freigeben', function () {
    return view('project-freigeben');
});*/

Route::get('/beschreibung', function () {
    return view('beschreibung');
});

// start Badge

Route::get('/show-badges', 'BadgeController@showBadges');
Route::get('/my-badges', 'BadgeController@myBadges')->name('my-badges');
Route::get('/create-badge', 'BadgeController@createBadge');
Route::get('/show-data/{id}', 'BadgeController@showData');
Route::post('/edit-badge', 'BadgeController@editBadge');
Route::post('/save-badge', 'BadgeController@saveBadge');
Route::get('/delete-badge/{id}', 'BadgeController@deleteBadge');

// end Badge

// start Award

Route::get('/show-awards', 'AwardController@showAwards');
Route::get('/create-award', 'AwardController@createAward');
Route::get('/show-data-award/{id}', 'AwardController@showData');
Route::post('/edit-award', 'AwardController@editAward');
Route::post('/save-award', 'AwardController@saveAward')->name('save-award');
Route::get('/delete-award/{id}', 'AwardController@deleteAward');

// end Award
// Award image gallery
Route::get('/image-gallery/{url}', 'AwardImageController@showGallery');
Route::post('/upload-image', 'AwardImageController@saveGallery');
Route::post('/share-url', 'AwardImageController@share')->name('shareurl');
// Award image gallery

//export score

Route::get('/show-score', 'ExportScoreController@showScore');
Route::get('/export-score', 'ExportScoreController@exportScore');

//export score

//email reminder
Route::get('/email-cron', 'EmailCronController@emailCron');
//email reminder

Auth::routes();

// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

Route::get('/project-rejected', 'ProjectController@projectRejected')->name('project.rejected');
Route::get('/download-certificate', 'ProjectController@download_certificate');
Route::get('/download-certificate-pdf/{rank}/{category}', 'ProjectController@download_certificate_pdf');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/downlaod/my-invoice', 'ProjectController@my_pdf_download');
Route::get('/downlaod/pdf/{id}', 'ProjectController@pdf_download');
Route::get('/pdf_mail/{id}', 'ProjectController@pdf_mail');
Route::post('/send-invoice', 'ProjectController@send_invoice');
Route::get('/downlaod/pdf-by-cat/{id}', 'ProjectController@pdf_download_by_category');
Route::post('/register-new-user', 'ProjectController@registerNewUser');
Route::get('/change-user-status', 'ProjectController@change_user_status')->name('change-user-status');
Route::get('/change-user-status-new', 'ProjectController@change_user_status_new');
Route::post('/status-change-dd', 'ProjectController@status_change_dd');
Route::post('/user-delete/{id}', 'ProjectController@user_delete');
Route::get('/change-status/{type}/{user_id}', 'ProjectController@change_status');
Route::get('/invoice', 'ProjectController@invoice');
Route::get('/cat-invoice', 'ProjectController@catInvoice');
Route::get('/top-five', 'ProjectController@TopFive');
Route::get('/project/edit/{id}', 'ProjectController@edit');
Route::get('/project/{id}', 'ProjectController@singgleAdminProjectShow');

Route::get('/activity', 'ProjectController@activity');
Route::get('/project/single/admin/{id}', 'ProjectController@SingleProject');
Route::get('/project/add-image/{project_id}/{cat_id}/{is_auto?}', 'ProjectController@AddImage');
Route::get('/project/edit-image/{project_id}/{cat_id}/{is_auto?}', 'ProjectController@EditImage');
Route::post('/project/delete-image', 'ProjectController@DeleteImage');
Route::post('/project/delete-image-instant', 'ProjectController@DeleteImageInstant');
Route::get('/project-show-rater/{cat_id?}', 'ProjectController@ProjectBewerten')->name('project-show-rater');

Route::get('/project-show-1st-round/{cat_id?}', 'ProjectController@ProjectFirstRound')->name('project-show-1st-round');
Route::post('/project-accept-1st-round', 'ProjectController@acceptProjectFirstRound');
Route::post('/project-reject-1st-round', 'ProjectController@rejectProjectFirstRound');
Route::get('/round-visibility', 'ProjectController@disableProjectRound')->name('round-visibility');
Route::post('/round-visibility', 'ProjectController@updateProjectRound');
Route::get('/project-votable', 'ProjectController@disableProjectVotable')->name('project-votable');
Route::post('/project-votable', 'ProjectController@updateProjectVotable');
Route::get('/make-public-votable', 'ProjectController@getNonPublicVotableProject')->name('make-public-votable');
Route::get('/public-votable', 'ProjectController@getPublicVotableProject')->name('public-votable');
Route::post('/update-public-votable', 'ProjectController@updatePublicVotableProject');

Route::get('/project-freigeben/{cat_id?}', 'ProjectController@ProjectFreigeben')->name('project-freigeben');
Route::get('/project-rechnung/{cat_id?}', 'ProjectController@ProjectRechnung')->name('project-rechnung');
Route::get('/project-freigegebene/{cat_id?}', 'ProjectController@ProjectFreigegebene')->name('project-freigegebene');
Route::get('/admin-project-show/{project_stat_id}', 'ProjectController@adminProjectShow');
Route::get('/admin-project-show-all', 'ProjectController@adminProjectShowAll');
Route::get('/project-show', 'ProjectController@ProjectShow')->name('project-show');
Route::post('/change-project', 'ProjectController@ProjectChange')->name('change-project');
Route::post('/project-rated', 'ProjectController@ProjectRated')->name('project-rated');
Route::post('/email-senden', 'ProjectController@EmailSenden')->name('email-senden');
Route::post('/project-freigegeben', 'ProjectController@ProjectFreigegeben')->name('project-freigegeben');

Route::get('/user-change', 'HomeController@change')->name('user-change');
Route::get('/add-teilnahmebedingung', 'HomeController@add')->name('add-teilnahmebedingung');
Route::get('/beschreibung', 'HomeController@beschreibung')->name('beschreibung');
Route::post('user-change', 'ChangeController@change');
Route::post('add-teilnahmebedingung', 'ChangeController@add');
Route::post('project-insert', 'ProjectController@insertProjectStepOne');
Route::post('/invoice-paid', 'InvoicesController@invoicePaid');
Route::post('/project-accept-admin', 'ProjectController@acceptProject');
Route::post('/project-reject-admin', 'ProjectController@rejectProject');
Route::post('/project-delete-admin', 'ProjectController@deleteProject');
Route::post('//project-free', 'ProjectController@free');
Route::post('/project-show-rating', 'ProjectController@showRating');
Route::post('/project-service', 'ProjectController@service');
Route::post('/project-special', 'ProjectController@special');
Route::post('/project-jury-admin', 'ProjectController@juryProject');
Route::post('/project-inv-admin', 'ProjectController@invProject');
Route::post('/project-jury-add-admin', 'ProjectController@juryAddProject');
Route::post('/project-inv-add-admin', 'ProjectController@invAddProject');
Route::post('/project-jury-remove-admin', 'ProjectController@juryRemoveProject');
Route::post('/project-inv-remove-admin', 'ProjectController@invRemoveProject');
Route::post('project-change', 'ProjectController@changeProject')->name('project-change');
Route::post('/add-project', 'ProjectController@insertProject')->name('add-project');
Route::post('/images-save', 'ProjectController@upload')->name('/images-save');
Route::post('/images-delete', 'ProjectController@delete')->name('/images-delete');
Route::post('/show-delete', 'ProjectController@show_delete')->name('/show-delete');
Route::post('send-email', 'ProjectController@send-email')->name('send-email');

Route::post('next-view', 'ProjectController@insertProjectStepTwo')->name('next-view');
Route::post('check-userinfo', 'ProjectController@checkUserInfo')->name('checkuserinfo');
Route::post('project-category-show', 'ProjectController@projectCategoryShow')->name('project-category-show');

Route::get('/CoE', 'ProjectController@CoE')->name('CoE');
Route::post('coe', 'ProjectController@addToCoE')->name('addToCoE');
Route::get('/votecoe', 'ProjectController@VoteCoe')->name('votecoe');

Route::get('/coeshow', 'ProjectController@CoEShow')->name('coeshow');
Route::get('/selectuser', 'ProjectController@SelectUserView')->name('selectuser');
Route::post('useraddproject', 'ProjectController@UserAddProject')->name('useraddproject');


Route::post('filter', 'ProjectController@filter');
Route::get('project-insert', 'ProjectController@myform')->name('project-insert');
Route::get('project-category-select', 'ProjectController@categorySelect')->name('project-category-select');

Route::get('/award-upload-by-user', 'ProjectController@awardUploadByUser');
Route::post('/award-upload-by-user', 'ProjectController@awardUploadByUserSubmit');
Route::get('/award-upload-by-user-list', 'ProjectController@awardUploadByUserList');

// Route::prefix('paypal')->group(function () {
//     Route::get('create-transaction/{project_id}', 'PayPalController@createTransaction')->name('createTransaction');
//     Route::get('process-transaction/{amount}', 'PayPalController@processTransaction')->name('payment');
//     Route::get('success-transaction', 'PayPalController@successTransaction')->name('successTransaction');
//     Route::get('cancel-transaction', 'PayPalController@cancelTransaction')->name('cancelTransaction');
// });
//});


Route::get('/all-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    return "Cache is cleared";
});


//Route::get('/updates', function () {
//    try {
//        $deleteCats = [
//            'Ausstattung, Dekoartikel & -Verleih',
//            'Braut & Herrenmode',
//            'Entertainment | Show-Act',
//            'Entertainment | Kinderanimation',
//            'Fotografie | Ambiente',
//            'Innovation',
// 'Tortendesign | Sweet Table'
//        ];
//        \App\Cat::whereIn('name', $deleteCats)->delete();
//
//        $editCats = [
//            'Entertainment | Live-Musik Abend' => 'Entertainment | Live-Musik Party',
//            'Catering | Highlights' => 'Food & Beverage | Highlight',
//            'Tortendesign | Hochzeitstorten-Design' => 'Patisserie | Tortendesign',
//            'Tortendesign | Sweet Table' => 'Patisserie | Highlight',
//            'Schmuckdesign | Ringe' => 'Ringdesign',
//            'Video | Highlight Video' => 'Video | Highlight'
//        ];
//        foreach ($editCats as $old => $new) {
//            \App\Cat::where('name', $old)->update(['name' => $new]);
//        }
//
//        return 'Updated';
//    } catch (\Exception $e) {
//        return $e->getMessage();
//    }
//});

Route::get('/jury-x-category', function () {
    $categories = \App\Cat::with(['juryCategoryPermission', 'juryCategoryPermission.user'])->orderBy('name')->get();
    return view('developer.jury-x-category', compact('categories'));
});

Route::delete('/delete-category/{id}', function ($id) {
    $cat = \App\Cat::findOrFail($id);
    $cat->delete();

    return redirect()->back()->with('success', 'Category deleted successfully');
})->name('delete-category');

Route::match(['GET', 'POST'], '/developer/update-categories', function () {
    try {
        if (request()->isMethod('POST')) {
            $data = request()->all();
            if (request('type') === 'update') {
                $name = trim($data['search_name']);
                $cat = \App\Cat::where('name', $name)->first();
                if (!$cat) {
                    return redirect()->back()->with('error', 'Category not found');
                }
                $cat->update([
                    'name' => $data['name'] ?? $name,
                    'fulldescription' => $data['description']
                ]);
                return view('developer.update-category', compact('cat'))
                    ->with('success', 'Category updated successfully');
            }
            if (request('type') === 'create') {
                $name = trim($data['name']);
                $cat = \App\Cat::where('name', $name)->first();
                if ($cat) {
                    return redirect()->back()->with('error', 'Category already exists');
                }
                \App\Cat::create([
                    'name' => $name,
                    'code' => $data['code'],
                    'fulldescription' => $data['description']
                ]);
                return redirect()->back()->with('success', 'Category created successfully');
            }
        }

        if (request('search_name')) {
            $name = trim(request('search_name'));
            $cat = \App\Cat::where('name', $name)->first();
            if (!$cat) {
                return redirect()->back()->with('error', 'Category not found');
            }
            return view('developer.update-category', compact('cat'));
        }
        return view('developer.update-category');
    } catch (\Exception $e) {
        return $e->getMessage();
    }
})->name('developer-update-category');