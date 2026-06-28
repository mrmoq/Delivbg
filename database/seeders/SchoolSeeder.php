<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            'ظبيه بنت البراء',
            'سهله بنت عاصم',
            'نوريه الصبيح',
            'ام زياد الاشجعيه',
            'منيره الصباح',
            'برقان',
            'الصباحيه',
            'انيسه بنت خبيب',
            'اسرار القبندي',
            'ابرق خيطان',
            'امامه بنت بشر',
            'دلال احمد البشر الرومي',
            'دره الهاشميه',
            'خديجه بنت الزبير',
            'اميمه بنت ربيعه',
            'الشرقيه',
            'Agbs',
        ];

        foreach ($schools as $name) {
            School::query()->updateOrCreate(
                ['name_ar' => $name],
                ['name_en' => $name, 'is_active' => true]
            );
        }
    }
}
