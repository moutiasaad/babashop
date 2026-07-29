<?php

namespace Database\Seeders;

use App\Models\DeliveryZone;
use Illuminate\Database\Seeder;

class TunisianGovernoratesSeeder extends Seeder
{
    /**
     * Seed the 24 Tunisian governorates with default delivery fees (TND).
     * Fees are conservative estimates — admins can tune per-zone in the panel.
     * Safe to re-run: upserts by ISO 3166-2 code.
     */
    public function run(): void
    {
        $order = 0;
        foreach ($this->governorates() as $g) {
            DeliveryZone::updateOrCreate(
                ['code' => $g['code']],
                [
                    'name'                    => $g['name'],
                    'name_ar'                 => $g['name_ar'],
                    'delivery_fee'            => $g['fee'],
                    'estimated_days_min'      => $g['days_min'],
                    'estimated_days_max'      => $g['days_max'],
                    'is_active'               => true,
                    'display_order'           => $order++,
                ]
            );
        }
    }

    private function governorates(): array
    {
        // Grand Tunis
        return [
            ['code' => 'TN-11', 'name' => 'Tunis',       'name_ar' => 'تونس',       'fee' => 7.000, 'days_min' => 1, 'days_max' => 2],
            ['code' => 'TN-12', 'name' => 'Ariana',      'name_ar' => 'أريانة',     'fee' => 7.000, 'days_min' => 1, 'days_max' => 2],
            ['code' => 'TN-13', 'name' => 'Ben Arous',   'name_ar' => 'بن عروس',    'fee' => 7.000, 'days_min' => 1, 'days_max' => 2],
            ['code' => 'TN-14', 'name' => 'Manouba',     'name_ar' => 'منوبة',      'fee' => 7.000, 'days_min' => 1, 'days_max' => 2],

            // North
            ['code' => 'TN-21', 'name' => 'Nabeul',      'name_ar' => 'نابل',       'fee' => 8.000, 'days_min' => 2, 'days_max' => 3],
            ['code' => 'TN-22', 'name' => 'Bizerte',     'name_ar' => 'بنزرت',      'fee' => 8.000, 'days_min' => 2, 'days_max' => 3],
            ['code' => 'TN-23', 'name' => 'Zaghouan',    'name_ar' => 'زغوان',      'fee' => 8.000, 'days_min' => 2, 'days_max' => 3],
            ['code' => 'TN-31', 'name' => 'Béja',        'name_ar' => 'باجة',       'fee' => 9.000, 'days_min' => 2, 'days_max' => 3],
            ['code' => 'TN-32', 'name' => 'Jendouba',    'name_ar' => 'جندوبة',     'fee' => 9.000, 'days_min' => 2, 'days_max' => 4],
            ['code' => 'TN-33', 'name' => 'Kef',         'name_ar' => 'الكاف',      'fee' => 9.000, 'days_min' => 2, 'days_max' => 4],
            ['code' => 'TN-34', 'name' => 'Siliana',     'name_ar' => 'سليانة',     'fee' => 9.000, 'days_min' => 2, 'days_max' => 4],

            // Coastal middle (Sahel)
            ['code' => 'TN-51', 'name' => 'Sousse',      'name_ar' => 'سوسة',       'fee' => 8.000, 'days_min' => 2, 'days_max' => 3],
            ['code' => 'TN-52', 'name' => 'Monastir',    'name_ar' => 'المنستير',   'fee' => 8.000, 'days_min' => 2, 'days_max' => 3],
            ['code' => 'TN-53', 'name' => 'Mahdia',      'name_ar' => 'المهدية',    'fee' => 8.000, 'days_min' => 2, 'days_max' => 3],

            // Center
            ['code' => 'TN-41', 'name' => 'Kairouan',    'name_ar' => 'القيروان',   'fee' => 9.000, 'days_min' => 2, 'days_max' => 4],
            ['code' => 'TN-42', 'name' => 'Kasserine',   'name_ar' => 'القصرين',    'fee' => 9.000, 'days_min' => 3, 'days_max' => 4],
            ['code' => 'TN-43', 'name' => 'Sidi Bouzid', 'name_ar' => 'سيدي بوزيد', 'fee' => 9.000, 'days_min' => 3, 'days_max' => 4],

            // Sfax
            ['code' => 'TN-61', 'name' => 'Sfax',        'name_ar' => 'صفاقس',      'fee' => 9.000, 'days_min' => 2, 'days_max' => 3],

            // South
            ['code' => 'TN-71', 'name' => 'Gafsa',       'name_ar' => 'قفصة',       'fee' => 10.000, 'days_min' => 3, 'days_max' => 5],
            ['code' => 'TN-72', 'name' => 'Tozeur',      'name_ar' => 'توزر',       'fee' => 10.000, 'days_min' => 3, 'days_max' => 5],
            ['code' => 'TN-73', 'name' => 'Kebili',      'name_ar' => 'قبلي',       'fee' => 10.000, 'days_min' => 3, 'days_max' => 5],
            ['code' => 'TN-81', 'name' => 'Gabès',       'name_ar' => 'قابس',       'fee' => 10.000, 'days_min' => 3, 'days_max' => 5],
            ['code' => 'TN-82', 'name' => 'Medenine',    'name_ar' => 'مدنين',      'fee' => 10.000, 'days_min' => 3, 'days_max' => 5],
            ['code' => 'TN-83', 'name' => 'Tataouine',   'name_ar' => 'تطاوين',     'fee' => 10.000, 'days_min' => 4, 'days_max' => 6],
        ];
    }
}
