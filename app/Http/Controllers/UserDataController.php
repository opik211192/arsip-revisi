<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Struktural;
use Illuminate\Http\Request;
use App\Models\Struktural_detail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserDataController extends Controller
{
    public function __construct()
    {
         $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        $strukturals = Struktural::all();
        $struktural_detail = Struktural_detail::all();
        return view('auth.user', compact('users', 'strukturals', 'struktural_detail'));
    }

    public function create()
    {
        $strukturals = Struktural::all();
        $struktural_detail = Struktural_detail::all();
        return view('auth.register', compact('strukturals', 'struktural_detail'));
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed',
            'struktural_id' => 'required',
            'struktural_detail_id' => 'required',
        ]);

        $user = User::create([
            'name' => $validateData['name'],
            'username' => $validateData['username'],
            'email' => $validateData['email'],
            'password' => bcrypt($validateData['password']),
            'struktural_id' => $validateData['struktural_id'],
            'struktural_detail_id' => $validateData['struktural_detail_id'],
        ]);

        // return "berhasil";

        return redirect()->route('user.data')->with('success', 'Data User berhasi ditambahkan');
    }

    public function edit(User $user)
    {
         $strukturals = Struktural::all();
        $struktural_detail = Struktural_detail::all();
        $struktur = DB::select('SELECT a.id,
                    a.name,
                    b.id AS "struktural_id",
                    b.name AS "struktural",
                    c.id AS "struktural_detail_id",
                    c.name AS "struktural_detail"
                FROM users a
                LEFT OUTER JOIN strukturals b ON a.struktural_id = b.id
                LEFT OUTER JOIN struktural_details c ON a.struktural_detail_id = c.id
                WHERE a.id ='.$user->id);
        $get_struktural_detail = Struktural_detail::where('struktural_id', $struktur[0]->struktural_id)->get();
        return view('auth.edit_user', compact('user', 'strukturals', 'struktural_detail', 'struktur','get_struktural_detail'));
        //dd($struktur);
        //dd($get_struktural_detail);
        //dd($get);
    }

    // public function update(Request $request, User $user)
    // {
    //     request()->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255',
    //         'username' => 'required',
    //         'struktural_id' => 'required',
    //         'struktural_detail_id' => 'required',
    //     ]);

        
    //     //ambil password user
    //     $hashedPassword = $user->password;

    //     //validasi jika ganti password
    //     if($request->oldpassword){  
    //         //validasi cek oassword 
    //         if (\Hash::check($request->oldpassword, $hashedPassword)) {
    //             if (!Hash::check($request->newpassword, $hashedPassword)) {
    //                 // $users = Auth::user()->id;
    //                 // $users->password = bcrypt($request->newpassword);
    //                 // User::where('id', Auth::user()->id)->update( array(
    //                 //     'password' => $users->password
    //                 // ));
    //                 //simppan perubahan dengan password baru
    //                 $user->update([
    //                     'name' => $request->name,
    //                     'email' => $request->email,
    //                     'username' => $request->username,
    //                     'password' => bcrypt($request->newpassword),
    //                     'struktural_id' => $request->struktural_id,
    //                     'struktural_detail_id' => $request->struktural_detail_id,
                       
    //                 ]);
    //                 return redirect()->route('user.data')->with('success', 'Berhasil Ganti password');
    //             }
    //             else {
    //                 //echo "new password can not be the old password!";
    //                 return back()->with('error', 'new password can not be the old password!');
    //             }
    //         }
    //         else {
    //             //echo "Old Password dosent matched";
    //             return back()->with('error', 'Old password doesnt matched');
    //         }
    //     }else{

    //         //simpan data tanpa ubah paswword
    //         $user->update([
    //             'name' => request('name'),
    //             'email' => request('email'),
    //             'username' => request('username'),
    //             'struktural_id' => request('struktural_id'),
    //             'struktural_detail_id' => request('struktural_detail_id'),
    //         ]);
    //         //echo "berhasil update tanpa isi password";
    //         return redirect()->route('user.data')->with('success', 'Berhasil update data');
    //     }

    // }

    public function update(Request $request, User $user)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        if ($request->oldpassword) {
            if (!Hash::check($request->get('oldpassword'), $user->password)) {
                return redirect()->back()->with("error","Password saat ini tidak cocok dengan password baru, silahkan coba lagi");
            }

            if (strcmp($request->get('oldpassword'), $request->get('newpassword')) == 0) {
                 return redirect()->back()->with("error","Password baru tidak boleh sama dengan password saat ini");
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => bcrypt($request->get('newpassword')),
                'struktural_id' => $request->struktural_id,
                'struktural_detail_id' => $request->struktural_detail_id,
                       
            ]);

            return redirect()->back()->with("success","Data/password berhasil diubah");
        }

        $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'struktural_id' => $request->struktural_id,
                'struktural_detail_id' => $request->struktural_detail_id,
                       
            ]);
        return redirect()->back()->with("success","Data Berhasil diubah");
        
    }


    public function delete(User $user)
    {
        $user->delete();
        return redirect()->route('user.data')->with('danger', "Hapus $user->nama berhasil");
    }

    public function ambilData(Request $request)
    {

        $struktur = Struktural_detail::where('struktural_id', $request->get('id'))->pluck('name', 'id');
        return response()->json($struktur);

    } 


}
