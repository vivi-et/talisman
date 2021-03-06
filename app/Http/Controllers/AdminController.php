<?php

namespace App\Http\Controllers;

use App\User;
use App\Category;
use App\Role;
use Illuminate\Http\Request;
use App\Tag;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{


  public function test()
  {
    $password = bcrypt('zxcvzxcv');

    $user = User::create([
      'fullName' => 'zxcv',
      'email' => 'zxcv@zxcv.zxcv', //bail : required에서 실패할경우 email validation을 생략하고 바로 에러를 반환
      'permission' => '[{"resourceName":"Tags","read":true,"write":true,"update":true,"delete":true,"name":"tags"},{"resourceName":"Category","read":true,"write":true,"update":true,"delete":true,"name":"category"},{"resourceName":"Admin users","read":true,"write":true,"update":true,"delete":true,"name":"adminusers"},{"resourceName":"Role","read":true,"write":true,"update":true,"delete":true,"name":"role"},{"resourceName":"AssignRole","read":true,"write":true,"update":true,"delete":true,"name":"assignRole"},{"resourceName":"/","read":true,"write":true,"update":true,"delete":true,"name":"/"}]     ',
      'password' => $password,
      'role_id' => 1,
      'userType' =>  'Admin',
    ]);

    return $user;
  }
  public function index(Request $request)
  {


    // check if you are logged in and admin user
    if (!Auth::check() && $request->path() != 'login') {
      return redirect('/login');
    }

    if (!Auth::check() && $request->path() == 'login') {
      return view('welcome');
    }

    // you are already logged in ... so check for if you are an admin user
    $user = Auth::user();
    if ($user->role_id == 4) {
      return redirect('/login');
    }

    if ($request->path() == 'login') {
      return redirect('/');
    }

    return $this->checkForPermission($user, $request);

    return view('notfound');

    return view('welcome');
  }

  public function checkForPermission($user, $request)
  {
    // read 권한이 없을경우 모든 기능을 사용할수 없어야함

    $permission = json_decode($user->role->permission);

    $hasPermission = false;
    if (!$permission) {
      return view('welcome');
    }
    foreach ($permission as $perm) {
      if ($perm->name == $request->path()) {
        if ($perm->read) {
          $hasPermission = true;
        }
      }
    }
    if ($hasPermission) {
      return view('welcome');
    } else {
      return abort(404);
      return view('notfound');
    }

    echo $permission[0]->name;
    echo $request->path();
  }

  public function logout()
  {
    Auth::logout();
    return redirect('/login');
  }

  public function addTag(Request $request)
  {

    //validate request
    $this->validate($request, [
      'tagName' => 'required'
    ]);

    return Tag::create([
      'tagName' => $request->tagName
    ]);
  }
  public function addCategory(Request $request)
  {

    //validate request
    $this->validate($request, [
      'categoryName' => 'required',
      'iconImage' => 'required'
    ]);

    return Category::create([
      'categoryName' => $request->categoryName,
      'iconImage' => $request->iconImage
    ]);
  }
  public function editTag(Request $request)
  {

    //validate request
    $this->validate($request, [
      'tagName' => 'required',
      'id' => 'required',
    ]);

    return Tag::where('id', $request->id)->update([
      'tagName' => $request->tagName
    ]);
  }
  public function editCategory(Request $request)
  {

    //validate request
    $this->validate($request, [
      'categoryName' => 'required',
      'iconImage' => 'required',
    ]);

    return Category::where('id', $request->id)->update([
      'categoryName' => $request->categoryName,
      'iconImage' => $request->iconImage
    ]);
  }
  public function deleteTag(Request $request)
  {
    return Tag::where('id', $request->id)->delete();
  }
  public function deleteCategory(Request $request)
  {
    //delete image file from the server
    $this->deleteFileFromServer($request->iconImage, true);

    $this->validate($request, [
      'id' => 'required',
    ]);

    return Category::where('id', $request->id)->delete();
  }
  public function getTag()
  {
    return Tag::orderBy('id', 'desc')->get();
  }
  public function getCategory()
  {
    return Category::orderBy('id', 'desc')->get();
  }
  public function upload(Request $request)
  {
    $this->validate($request, [
      'file' => 'required|mimes:jpeg,jpg,png'
    ]);
    $picName = time() . '.' . $request->file->extension();
    $request->file->move(public_path('uploads'), $picName);
    return $picName;
  }
  public function deleteImage(Request $request)
  {
    $fileName = $request->imageName;
    $this->deleteFileFromServer($fileName, false);
    return 'done';
  }
  public function deleteFileFromServer($fileName, $hasFullPath = false)
  {
    if (!$hasFullPath) {
      $filePath = public_path() . '/uploads/' . $fileName;
    } else {
      $filePath = public_path() . $fileName;
    }
    if (file_exists($filePath)) {
      @unlink($filePath);
    }
    return;
  }
  public function addUser(Request $request)
  {
    $this->validate($request, [
      'fullName' => 'required',
      'email' => 'bail|required|email|unique:users', //bail : required에서 실패할경우 email validation을 생략하고 바로 에러를 반환
      'password' => 'required|min:6',
      'role_id' => 'required'
    ]);

    // bcrypt 사용
    $password = bcrypt($request->password);

    $user = User::create([
      'fullName' => $request->fullName,
      'email' => $request->email, //bail : required에서 실패할경우 email validation을 생략하고 바로 에러를 반환
      'password' => $password,
      'role_id' =>  $request->role_id,
    ]);

    return $user;
  }
  public function getUser()
  {
    return User::where('role_id', '!=', '4')->get();
  }

  public function editUser(Request $request)
  {
    $this->validate($request, [
      'fullName' => 'required',                       // if not unique, check for owner of that email with id
      'email' => "bail|required|email|unique:users,email,$request->id", //bail : required에서 실패할경우 email validation을 생략하고 바로 에러를 반환
      'password' => 'min:6',
      'role_id' => 'required'
    ]);


    $data = [
      'fullName' => $request->fullName,
      'email' => $request->email,
      'role_id' => $request->role_id,
    ];
    if ($request->password) {
      $password = bcrypt($request->password);
      $data['password']   = $password;
    }

    $user = User::where('id', $request->id)->update($data);


    return $user;

    /*
            1. email may change
                new email is unique

            2. email won't change
                email stays as is
        */

    // bcrypt 사용
    $password = bcrypt($request->password);

    $user = User::create([
      'fullName' => $request->fullName,
      'email' => $request->email, //bail : required에서 실패할경우 email validation을 생략하고 바로 에러를 반환
      'password' => $password,
      'role_id' =>  $request->role_id,
    ]);

    return $user;
  }
  public function adminLogin(Request $request)
  {
    $this->validate($request, [
      'email' => 'bail|required|email',
      'password' => 'bail|required|min:6'
    ]);

    if (Auth::attempt([
      'email' => $request->email,
      'password' => $request->password
    ])) {
      $user = Auth::user();

      \Log::info($user->role);

      if ($user->role->isAdmin == 0) {
        Auth::logout();

        return response()->json([
          'msg' => 'Incorrect login details'
        ], 401);
      }

      return response()->json([
        'msg' => 'Login successful',
        'user' => $user
      ], 200);
    } else {
      return response()->json([
        'msg' => 'Incorrect login details'
      ], 401);
    }
  }
  public function addRole(Request $request)
  {
    $this->validate($request, [
      'roleName' => 'required'
    ]);
    return Role::create([
      'roleName' => $request->roleName,
    ]);
  }

  public function getRole()
  {
    return Role::orderBy('id')->get();
  }

  public function editRole(Request $request)
  {
    $this->validate($request, [
      'id'  => 'required',
      'roleName' => 'required'
    ]);

    return Role::where('id', $request->id)->update([
      'roleName' => $request->roleName
    ]);
  }

  public function deleteRole(Request $request)
  {
    return Role::where('id', $request->id)->delete();
  }
  public function assignRole(Request $request)
  {
    $this->validate($request, [
      'id' => 'required',
      'permission' => 'required'
    ]);
    return Role::where('id', $request->id)->update([
      'permission' => $request->permission
    ]);
  }
}
