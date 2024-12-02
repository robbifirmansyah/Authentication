<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendMailJob;
use Illuminate\Support\Facades\Mail; // Import untuk pengiriman email
use App\Mail\SendEmail; // Import Mail class yang telah dibuat

class LoginRegisterController extends Controller
{
    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout', 'dashboard'
        ]);
    }

    /**
     * Display a registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:250',
        'email' => 'required|email|max:250|unique:users',
        'password' => 'required|min:8|confirmed',
        'photo' => 'image|nullable|max:1999'
    ]);

    // Memeriksa apakah ada file yang di-upload dan menyimpannya
    if ($request->hasFile('photo')) {
        $filenameWithExt = $request->file('photo')->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('photo')->getClientOriginalExtension();
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        $path = $request->file('photo')->storeAs('photos', $fileNameToStore);
    } else {
        $path = null;
    }

    // Membuat user baru
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'photo' => $path
    ]);

    // Melakukan login setelah registrasi berhasil
    $credentials = $request->only('email', 'password');
    Auth::attempt($credentials);

    // Kirim email notifikasi setelah registrasi berhasil
    Mail::to($request->email)->send(new SendEmail([
        'name' => $request->name,
        'email' => $request->email,
        'subject' => 'Registration Successful!', // Menambahkan subject
        'registration_date' => now()->format('Y-m-d H:i:s'),
    ]));

    // Regenerasi session dan redirect ke dashboard
    $request->session()->regenerate();
    return redirect()->route('dashboard')
        ->withSuccess('You have successfully registered & logged in!');
}

    

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek apakah user admin atau bukan
            if (auth()->user()->level === 'admin') {
                return redirect()->route('dashboard')
                    ->with('message', 'Anda berhasil login sebagai admin.');
            } else {
                // Jika bukan admin, arahkan ke halaman welcome
                Auth::logout(); // logout user yang bukan admin
                return redirect()->route('welcome')
                    ->with('error', 'Anda bukan admin.');
            }
        }

        return back()->withErrors([
            'email' => 'Your provided credentials do not match our records.'
        ])->onlyInput('email');
    }

    /**
     * Display a dashboard to authenticated users.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if (Auth::check()) {
            return view('auth.dashboard');
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.'
            ])->onlyInput('email');
    }

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');
    }

    public function uploadFile(Request $request)
    {
        // Memeriksa apakah ada file yang di-upload
        if ($request->hasFile('photo')) { // Sesuaikan dengan name="photo" di form
            // Simpan file dan dapatkan path-nya
            $path = $request->file('photo')->store('uploads'); // Simpan di folder 'uploads' dalam storage
            return response()->json(['message' => 'File berhasil di-upload!', 'path' => $path]);
        } else {
            return response()->json(['message' => 'Tidak ada file yang di-upload.'], 400);
        }
    }
}
