<?php
use App\user;
use App\admins;
use App\Fees;
use App\Payment;
use App\sugestion;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('payit');
});



Route::get('/Register', function () {
    $courses=DB::table('fees')->distinct()->select('department')->get();
    return view('Register',['courses'=>$courses]);
});

Route::get('/updatefees16', function () {
    $courses=DB::table('fees')->distinct()->select('department')->get();
    $semesters=DB::table('fees')->distinct()->select('sem')->get();
    return view('updatefees16',['courses'=>$courses,'semesters'=>$semesters]);
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/sugestion', function () {
    return view('sugestion');
});

Route::get('/sugestion1', function () {
     $masters=DB::table('fees')->distinct()->select('department')->get();
    return view('sugestion1',['masters'=>$masters]);
});

Route::get('/addcourse', function () {
    return view('addcourse');
});

Route::post('/addcourse',function(Request $request){
    $department=$request->get('department');
    $semester=$request->get('sem');
    $university=$request->get('university');
    $library=$request->get('library');
    $sports=$request->get('sports');
    $total=$university + $library + $sports;
        $fees=New fees;
        $fees->department=$department;
        $fees->sem=$semester;
        $fees->university=$university;
        $fees->library=$library;
        $fees->sports=$sports;
        $fees->total=$total;
        $fees->save();        
        return redirect('/adminhome')
        ->with('success','Fees updated successfuly');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/index', function () {
    return view('index');
});
Route::get('/about5', function () {
    return view('about5');
});

Route::get('/contact4', function () {
    return view('contact4');
});

Route::get('/balance6', function () {
     $semesters=DB::table('fees')->distinct()->select('sem')->get();
    return view('balance6',['semesters'=>$semesters]);
});

Route::get('/forgot', function () {
    return view('forgot');
});

Route::get('/setting', function () {
    return view('setting');
});

Route::get('/feedback', function () {
    return view('feedback');
});

Route::get('/payit', function () {
    return view('payit');
});

Route::get('/manu', function () {
    return view('manu');
});




Route::get('/payment', function () {
    $semesters=DB::table('fees')->distinct()->select('sem')->get();
    return view('payment',['semesters'=>$semesters]);
});

Route::get('/balance6', function (Request $request) {
    $semesters=DB::table('fees')->distinct()->select('sem')->get();
    $sem=$request->get('sem');
    $admissionid=$request->get('admission');
    $student=DB::table('payment')
    ->where('admission',$admissionid)
    ->where('sem',$sem)
    ->get();

    $fees=DB::table('fees')
    ->where('sem',$sem)
    ->where('department',$request->get('dept'))
    ->get();
     $paid=$student->get('amount');
     $total=$fees->get('total');
     $balance=$total - $paid;
    return view('balance6',['student'=>$student,'fees'=>$fees,'semesters'=>$semesters]);
});

Route::get('/sugestion1', function (Request $request) {
  $masters=DB::table('fees')->distinct()->select('department')->get();
    $department=$request->get('department');
    $admissionid=$request->get('admission');
    $sugestion=DB::table('sugestion')
    ->where('department',$request->get('dept'))
    ->get();
     $message=$sugestion->get('message');
      $admissionid=$request->get('admission');
     $name=$sugestion->get('name');
      $department=$sugestion->get('department');
    return view('sugestion1',['sugestion'=>$sugestion,'masters'=>$masters]);
});




Route::get('/adminindex', function () {
    return view('adminindex');
});

Route::get('/feeinfo', function () {
    return view('feeinfo');
});


Route::get('/adminregister', function () {
    return view('adminregister');
});

Route::get('/adminlogin', function () {
    return view('adminlogin');
});

Route::get('/sample', function () {
    return view('sample');
});

Route::get('/try', function () {
    return view('try');
});
Route::get('/adminhome', function () {
    return view('adminhome');
});



Route::get('/adminfeescheck', function (Request $request) {
     $courses=DB::table('fees')->distinct()->select('department')->get();
    $semesters=DB::table('fees')->distinct()->select('sem')->get();
    $dept=$request->get('department');
    $sem=$request->get('sem');
    $students=DB::table('users')
    ->where('department',$dept)
    ->get();
    $payments=DB::table('payment')
    ->where('sem',$sem)
    ->get();
    $balances=DB::table('fees')
    ->where('department',$dept)
    ->where('sem',$sem)
    ->get();
    return view('adminfeescheck',['students'=>$students,'payments'=>$payments,'balances'=>$balances,'courses'=>$courses,'semesters'=>$semesters]);
});












Route::get('/feeinfo', function (Request $request) {
     $courses=DB::table('fees')->distinct()->select('department')->get();
    $semesters=DB::table('fees')->distinct()->select('sem')->get();
    $fees=DB::table('fees')
        ->where('department',$request->get('department'))
        ->where('sem',$request->get('sem'))
        ->get();
    return view('feeinfo',['fees'=>$fees,'courses'=>$courses,'semesters'=>$semesters]);
});

Route::post('/Register',function(Request $request){
    $validator=Validator::make($request->all(),array(
        'email' => 'required|email|max:255|unique:users',
        'password'=>'required|min:6|confirmed',
        'password_confirmation'=>'required|min:6'));
    if($validator->fails()){
        return redirect('/Register')
        ->withErrors($validator)
        ->with('error','Password mismatch')
        ->withInput();
    }
    $user = New User();
    $user->name=$request->get('name');
    $user->admission=$request->get('admission');
    $user->department=$request->get('department');
    $user->mobile=$request->get('mobile');
    $user->email=$request->get('email');
    $user->password=Hash::make($request->password);
       if($user->save()){
        return redirect('/login')
        ->with('sucess','ACCOUNT CREATED SUCESSFULLY');
    }else{
        return redirect('/')
        ->with('error','ACCOUNT CREATED UNSUCESSFULLY');
    }
});


Route::post('/login',function(Request $request)
{
    $email=$request->get('email');
    $password=$request->get('password');
    if(Auth::attempt(['email'=>$email,'password'=>$password])) {
       return redirect('/manu'); 
    }else{
        return redirect('/login')
        ->with('error','Invalid admission number or password');
    }
    });

Route::get('/logout',function(){
    Auth::logout();
    return redirect('/login');
});


Route::post('/adminregister',function(Request $request){
    $admins = New admins();
    $admins->adminid=$request->get('adminid');
    $admins->mobile=$request->get('mobile');
    $admins->email=$request->get('email');
    $admins->password=Hash::make($request->password);
       if($admins->save()){
        return redirect('/adminlogin')
        ->with('sucess','ACCOUNT CREATED SUCESSFULLY');
    }else{
        return redirect('/adminregister')
        ->with('error','ACCOUNT CREATED UNSUCESSFULLY');
    }
});


Route::post('/adminlogin',function(Request $request){
    $email=$request->get('email');
    $password=$request->get('password');
    $auth=Auth::guard('admins')->attempt(['email'=>$email,'password'=>$password]);
    if($auth){
       return redirect('/adminhome'); 
    }else{
        return redirect('/adminlogin')
        ->with('error','Invalid admission number or password');
    }
    });

Route::get('/adminlogout',function(){
    Auth::logout();
    return redirect('/adminlogin');
});








Route::post('/payment',function(Request $request)
{
    $semesters=DB::table('fees')->distinct()->select('sem')->get();
    $admissionid=$request->get('admissionno');
    $auth=DB::table('users')
    ->where('admission',$admissionid)
    ->get();
    $pay=DB::table('payment')
    ->where('admission',$admissionid)
    ->where('sem',$request->get('sem'))
    ->get();

    if(count($auth)!=0){
        if(count($pay)==0){
                $payment = New payment;
                $payment->admission=$admissionid;
                $payment->sem=$request->get('sem');
                $payment->amount=$request->get('amount');
                $payment->save();
                return redirect('/balance6');
            }else{
                DB::table('payment')
                ->where('admission',$admissionid)
                ->where('sem',$request->get('sem'))
                ->increment('amount',$request->get('amount'));
                return redirect('/balance6');
            }
    }else{
        return redirect('/payment')
        ->with('error','Invalid admission number');
    }
    });




Route::post('/adminlogin',function(Request $request){
    $email=$request->get('email');
    $password=$request->get('password');
    $auth=Auth::guard('admins')->attempt(['email'=>$email,'password'=>$password]);
    if($auth){
       return redirect('/adminhome'); 
    }else{
        return redirect('/adminlogin')
        ->with('error','Invalid admission number or password');
    }
    });

Route::get('/adminlogout',function(){
    Auth::logout();
    return redirect('/adminlogin');
});



Route::post('/fees',function(Request $request){
    $department=$request->get('department');
    $semester=$request->get('sem');
    
    $exists=DB::table('fees')
            ->where('department',$department)
            ->where('sem',$semester)
            ->get();
    
    $university=$request->get('university');
    $library=$request->get('library');
    $sports=$request->get('sports');
    $total=$university + $library + $sports;
    
    if(count($exists)==0){
        $fees=New fees;
        $fees->department=$department;
        $fees->sem=$semester;
        $fees->university=$university;
        $fees->library=$library;
        $fees->sports=$sports;
        $fees->total=$total;
        $fees->save();        
        return redirect('/adminhome')
        ->with('success','Fees updated successfuly');
    }else{
        DB::table('fees')
            ->where('department',$department)
            ->where('sem',$semester)
            ->update(['university'=>$university,'library'=>$library,'sports'=>$sports,'total'=>$total]);
            return redirect('/adminhome')
            ->with('success','Fees added successfuly');

    }
});



Route::post('/sugestion',function(Request $request){
    $admission=$request->get('admission');
    $name=$request->get('name');
    $department=$request->get('department');
    $email=$request->get('email');
    $message=$request->get('message');
         $sugestion=New  sugestion;
         $sugestion->admission=$admission;
         $sugestion->name=$name;
         $sugestion->department=$department;
         $sugestion->email=$email;
         $sugestion->message=$message;
         $sugestion->save();        
        return redirect('/manu')
        ->with('success','Fees updated successfuly');
   });








