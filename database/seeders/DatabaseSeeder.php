<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Governorate;
use App\Models\PaymentMethod;
use App\Models\School;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@delivbg.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        $knetGuest = User::query()->updateOrCreate(
            ['email' => 'knet@delivbg.com'],
            [
                'name' => 'KNET Guest',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        Setting::setValue('default_delivery_fee', '3');
        Setting::setValue('knet_guest_user_id', (string) $knetGuest->id);

        PaymentMethod::query()->updateOrCreate(
            ['name' => 'KNET'],
            [
                'test_url' => 'https://kpaytest.com.kw/kpg/PaymentHTTP.htm',
                'live_url' => 'https://kpay.com.kw/kpg/PaymentHTTP.htm',
                'mode' => 'test_mode',
                'status' => false,
            ]
        );

        $governorate = Governorate::query()->updateOrCreate(
            ['name_ar' => 'العاصمة'],
            ['name_en' => 'Capital', 'is_active' => true]
        );

        Area::query()->updateOrCreate(
            ['governorate_id' => $governorate->id, 'name_ar' => 'السالمية'],
            ['name_en' => 'Salmiya', 'delivery_fee' => 3.000, 'is_active' => true]
        );

        Area::query()->updateOrCreate(
            ['governorate_id' => $governorate->id, 'name_ar' => 'حولي'],
            ['name_en' => 'Hawally', 'delivery_fee' => 3.500, 'is_active' => true]
        );

        School::query()->updateOrCreate(
            ['name_ar' => 'مدرسة نموذجية'],
            ['name_en' => 'Sample School', 'is_active' => true]
        );

        $this->call(SchoolSeeder::class);

        $this->command?->info('Admin: admin@delivbg.com / password');
        $this->command?->info('KNET Guest User ID: '.$knetGuest->id);
    }
}
