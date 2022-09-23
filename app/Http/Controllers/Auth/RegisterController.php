<?php

namespace App\Http\Controllers\Auth;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Struktural;
use App\Models\Struktural_detail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    //disabled route bawaan ketika disimpan
    //protected $redirectTo = RouteServiceProvider::HOME;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
        $this->middleware('auth');

    }

    public function index()
    {
        $users = User::all();
        $units = Unit::all();
        $strukturals = Struktural::all();
        $struktural_detail = Struktural_detail::all();
        return view('auth.user', compact('users', 'units','strukturals', 'struktural_detail'));
    }

    public function showRegistrationForm()
    {
        $units=Unit::all();
         $strukturals = Struktural::all();
        $struktural_detail = Struktural_detail::all();
        return view('auth.register', compact('units','strukturals', 'struktural_detail'));
    }

    public function ambilData(Request $request)

    {

        $struktur = Struktural_detail::where('struktural_id', $request->get('id'))->pluck('name', 'id');
        return response()->json($struktur);

    } 



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'username' => ['required', 'string', 'min:4', 'max:255' , 'unique:users'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'username' => $data['username'],
        ]);
    }

    //merubah route ketika simpan 
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered(
            $user = $this->create($request->all())
        ));



        // return $this->registered($request, $user)
        //     ?: redirect($this->redirectPath());
        return $this->registered($request, $user)
            ?: redirect('register')->with('pesan', "Data berhasil ditambah");
        
    }

    public function edit(User $user)
    {
        $units = Unit::all();
        return view('auth.edit_user', compact('user', 'units'));
    }

    public function update(Request $request, User $user)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        // $user->update([
        //     'name' => request('name'),
        //     'email' => request('email'),
        // ]);
        
        // $data = User::findOrFail($id);
        // $data->name = request('name');
        // $data->email = request('email');
        // $data->save();

        // return redirect()->route('register.index');
        
        //ambil password user
        $hashedPassword = $user->password;

        //validasi jika ganti password
        if($request->oldpassword){  
            //validasi cek oassword 
            if (\Hash::check($request->oldpassword, $hashedPassword)) {
                if (!Hash::check($request->newpassword, $hashedPassword)) {
                    // $users = Auth::user()->id;
                    // $users->password = bcrypt($request->newpassword);
                    // User::where('id', Auth::user()->id)->update( array(
                    //     'password' => $users->password
                    // ));
                    //simppan perubahan dengan password baru
                    $user->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'unit_id' => $request->unit_id,
                        'password' => bcrypt($request->newpassword),  
                    ]);
                    return redirect()->route('register.index')->with('success', 'Berhasil Ganti password');
                }
                else {
                    //echo "new password can not be the old password!";
                    return back()->with('error', 'new password can not be the old password!');
                }
            }
            else {
                //echo "Old Password dosent matched";
                return back()->with('error', 'Old password doesnt matched');
            }
        }else{

            //simpan data tanpa ubah paswword
            $user->update([
                'name' => request('name'),
                'email' => request('email'),
                'unit_id' => request('unit_id'),
            ]);
            //echo "berhasil update tanpa isi password";
            return redirect()->route('register.index')->with('success', 'Berhasil update data');
        }

    }

    public function delete(User $user)
    {
        $user->delete();
        return redirect()->route('register.index')->with('pesan', "Hapus $user->nama berhasil");
    }
    
}
