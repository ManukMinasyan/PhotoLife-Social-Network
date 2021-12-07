<?php

use App\Models\Member;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Members Seeder
         */
        $membersCount = 30;
        factory(Member::class, $membersCount)->make()->each(function ($member, $key) {
            $member->id = $key + 1;
            $member->save();
        });

        /**
         * Posts Seeder
         */
        $dir = resource_path('demo_images');
        $filenames = scandir($dir);
        factory(App\Models\Post::class, 57)->create()->each(function ($post, $key) use ($filenames) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                $fileKey = 2 + $key + $i;
                $filename = $filenames[$fileKey];
                $filePath = resource_path('demo_images/'.$filename);
                $post->addMedia($filePath)->preservingOriginal()->toMediaCollection('uploads');
            }
//            $post->media()->saveMany(factory(\Spatie\MediaLibrary\Models\Media::class, rand(1, 5))->make());
        });

        /**
         * Demo Admin Seed
         */
        $admin = [
            'name' => 'Superadmin',
            'email' => 'admin@photolife.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'role' => 'superadmin',
            'remember_token' => \Illuminate\Support\Str::random(10),
        ];
        \App\User::create($admin);

        /**
         * Demo Member Seed
         */
        $member = [
            'id' => $membersCount + 1,
            'name' => 'John Doe',
            'username' => 'john_doe',
            'email' => 'john.doe@photolife.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => \Illuminate\Support\Str::random(10),
        ];
        $member = \App\Models\Member::create($member);
        Member::limit(10)->get()->each(function ($secondMember) use ($member) {
            $member->follow($secondMember);
        });
    }
}
