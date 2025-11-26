<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Enums\AccountTypeEnum;
use App\Models\Doctor;
use App\Models\DoctorAccount;
use App\Models\User;
use App\Services\RandomStringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class DoctorAccountController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'doctor' => ['required', 'exists:doctors,id'],
            'role' => ['required', 'in:Doktor,Doktor Gigi'], 
        ]);
        
        $doctorId = $data['doctor'];
        $roleName = $data['role'];
        $secret = RandomStringService::numericOnly(6);
        $account = DoctorAccount::where('doctor_id', $doctorId)->first();
        
        if (!$account) {
            try {
                DB::transaction(function () use ($doctorId, $roleName, $secret) {
                    $doctor = Doctor::find($doctorId);
                    $encryptSecret = Crypt::encrypt($secret);
                    
                    $username = $this->generateUsername($roleName);
                    
                    $userData = [
                        'username' => $username,
                        'name' => $doctor->name,
                        'password' => bcrypt($secret),
                        'secret' => $encryptSecret,
                        'type' => AccountTypeEnum::Doctor,
                    ];
                    
                    $user = User::create($userData);
                    
                    $role = Role::firstOrCreate(['name' => $roleName]);
                    $user->assignRole($role);
                    
                    $accountData = [
                        'user_id' => $user->id,
                        'doctor_id' => $doctorId,
                        'role' => $roleName,
                    ];
                    
                    DoctorAccount::create($accountData);
                });
                
                return back()->with('success', 'Doctor account has been created');
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                return back()->with('error', 'Failed to create doctor account: ' . $th->getMessage());
            }
        }
        
        return back()->with('info', 'Doctor account already exists');
    }
    
    private function generateUsername(string $role): string
    {
        $prefix = ($role === 'Doktor') ? 'KSD01' : 'KSD02';
        
        $lastUser = User::where('username', 'LIKE', $prefix . '%')
            ->orderBy('username', 'desc')
            ->first();
        
        if ($lastUser) {
            $lastNumber = intval(substr($lastUser->username, -2));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 0;
        }
        
        return $prefix . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }
    
    public function show(Doctor $doctor): JsonResponse
    {
        $account = DoctorAccount::where('doctor_id', $doctor->id)->first();
        
        if ($account) {
            $user = User::find($account->user_id);
            $user->uncrypted = Crypt::decrypt($user->secret);
            $user->role_name = $account->role; 
            
            return response()->json(['data' => $user]);
        }
        
        return response()->json(['message' => 'Account not found'], 404);
    }
    
    public function reset(Doctor $doctor): RedirectResponse
    {
        $account = DoctorAccount::where('doctor_id', $doctor->id)->first();
        
        if (!$account) {
            return back()->with('error', 'Account not found');
        }
        
        try {
            $newSecret = RandomStringService::numericOnly(6);
            
            DB::transaction(function () use ($account, $newSecret) {
                $user = User::find($account->user_id);
                
                $user->update([
                    'password' => bcrypt($newSecret),
                    'secret' => Crypt::encrypt($newSecret),
                ]);
            });
            
            return back()->with('success', 'Password has been reset successfully');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return back()->with('error', 'Failed to reset password');
        }
    }
}