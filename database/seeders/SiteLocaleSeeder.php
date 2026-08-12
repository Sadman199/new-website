<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class SiteLocaleSeeder extends Seeder
{
    public function run(): void
    {
        Language::query()->where('short_name', '!=', 'en')->delete();

        Language::query()->updateOrCreate(
            ['short_name' => 'en'],
            [
                'name' => 'English',
                'is_default' => 'Yes',
            ]
        );
    }
}
