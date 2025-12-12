<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DetailUser;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 


class UserController extends Controller
{
  // GET /api/users?role=Volunteer
  public function index(Request $request)
  {
    $role = $request->query('role');

    $query = User::query()->with('detailUser'); // eager load relationship

    if ($role) {
      $query->where('role', $role);
    }

    $users = $query->paginate(20);

    return response()->json($users);
  }

  // GET /api/users/{id}
  public function show($id)
  {
    $user = User::with('detailUser')->find($id);
    if (!$user) {
      return response()->json(['message' => 'User not found'], 404);
    }
    return response()->json($user);
  }

public function store(Request $request)
{
    $rules = [
        'nama' => 'required|string|max:50',
        'email' => 'required|email|max:120|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => ['required', Rule::in(['Admin','Volunteer','Organizer'])],

        // detail user opsional
        'tanggal_lahir' => 'nullable|date',
        'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan,Tidak Ingin Memberi Tahu',
        'no_telepon' => 'nullable|string|max:20|unique:detail_users,no_telepon',
        'domisili' => 'nullable|string|max:100',
    ];

    $validated = $request->validate($rules);

    DB::beginTransaction();
    try {
        // CREATE USER
        $user = new User();
        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role = $validated['role'];
        $user->save();

        // CREATE DETAIL USER
        $detail = new DetailUser();
        $detail->user_id = $user->id;

        if (!empty($validated['tanggal_lahir'])) {
            $detail->tanggal_lahir = $validated['tanggal_lahir'];
        }
        if (!empty($validated['jenis_kelamin'])) {
            $detail->jenis_kelamin = $validated['jenis_kelamin'];
        }
        if (!empty($validated['no_telepon'])) {
            $detail->no_telepon = $validated['no_telepon'];
        }
        if (!empty($validated['domisili'])) {
            $detail->domisili = $validated['domisili'];
        }

        $detail->save();

        DB::commit();

        return response()->json(
            User::with('detailUser')->find($user->id),
            201
        );

    } catch (\Throwable $e) {
        DB::rollBack();

            // Pastikan kita selalu log string — gunakan toString agar aman
            $message = method_exists($e, 'getMessage') ? $e->getMessage() : (string)$e;

            // Catatan: menggunakan Log::error (imported) lebih jelas
            Log::error('Create user failed: ' . $message, [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
        return response()->json([
            'message' => 'Gagal membuat user',
            'error' => $e->getMessage(),
        ], 500);
    }
}


  // PUT /api/users/{id}
  public function update(Request $request, $id)
  {
    $user = User::with('detailUser')->find($id);
    if (!$user) {
      return response()->json(['message' => 'User not found'], 404);
    }

    $validator = Validator::make($request->all(), [
      'nama' => 'required|string|max:50',
      'email' => ['required', 'email', 'max:120', Rule::unique('users')->ignore($user->id)],
      // optional fields:
      'tanggal_lahir' => 'nullable|date',
      'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan,Tidak Ingin Memberi Tahu',
      'no_telepon' => 'nullable|string|max:20|unique:detail_users,no_telepon,' . $user->id . ',user_id',
      'domisili' => 'nullable|string|max:100',
      // password optional
      'password' => 'nullable|string|min:6',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    // update user
    $user->nama = $request->input('nama');
    $user->email = $request->input('email');
    if ($request->filled('password')) {
      $user->password = Hash::make($request->input('password'));
    }
    $user->save();

    // update or create detail_user
    $detail = $user->detailUser;
    if (!$detail) {
      $detail = new DetailUser();
      $detail->user_id = $user->id;
    }

    if ($request->filled('tanggal_lahir')) $detail->tanggal_lahir = $request->input('tanggal_lahir');
    if ($request->filled('jenis_kelamin')) $detail->jenis_kelamin = $request->input('jenis_kelamin');
    if ($request->filled('no_telepon')) $detail->no_telepon = $request->input('no_telepon');
    if ($request->filled('domisili')) $detail->domisili = $request->input('domisili');

    $detail->save();

    // return updated user
    $user = User::with('detailUser')->find($user->id);
    return response()->json($user);
  }

  // DELETE /api/users/{id}
  public function destroy($id)
  {
    $user = User::find($id);
    if (!$user) return response()->json(['message' => 'User not found'], 404);

    $user->delete(); // akan cascade ke detail_users jika migrasi onDelete('cascade')
    return response()->json(['message' => 'User deleted']);
  }
}
