<?php

use Illuminate\Database\Seeder;

class ReportTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'name' => 'It\'s spam'
            ],
            [
                'name' => 'Nudity or pornography'
            ],
            [
                'name' => 'Hate speech or symbols'
            ]
        ];

        foreach ($types as $type) {
            \App\Models\ReportType::create($type);
        }
    }
}
