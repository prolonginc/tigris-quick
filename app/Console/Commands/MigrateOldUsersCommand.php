<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MigrateOldUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Users migrate';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $users = [
            [
                'name' => 'Ali',
                'email' => 'alialani14@yahoo.com',
                'business_name' => 'business name',
                'is_approved' => 1,
                'password' => '$2y$10$yxgJhNdSaiD7bu4acWQ1se3ke4sBuVO8Ck3945C2FTh9tfi2VI2VS',
                'phone_number' => '55555555'
            ],

            [
                'name' => 'Dove Auto Glass',
                'email' => 'doveautoglassoffice@gmail.com',
                'business_name' => 'Dove Auto Glass',
                'is_approved' => 1,
                'password' => '$2y$10$a5KoZQZ4Q8ht.aUHZt131.G.njhFDTpOjUbTFR8VtWGiBDbzLlila',
                'phone_number' => '55555555'

            ],
            [
                'name' => 'Premium Glass',
                'email' => 'premiumglasstint@gmail.com',
                'business_name' => 'Premium Glass',
                'is_approved' => 1,
                'password' => '$2y$10$G4hywDz2FB9pecUG49ZUUeRQhfTue0Z6mt01ASDKWkTqtpJk73tmW',
                'phone_number' => '55555555'

            ],
            [
                'name' => 'A Tek Glass',
                'email' => 'atekglass@aol.com',
                'business_name' => 'A Tek Glass',
                'is_approved' => 1,
                'password' => '$2y$10$qFL8098THWD5RBJnzR7FFeWQ5LH2DlgU9BQPcwHi/Csikvzw4yO8q',
                'phone_number' => '55555555'

            ],

            [
                'name' => 'Scott',
                'email' => 'scottsmobileglass89@yahoo.com',
                'business_name' => 'Scott Mobile Glass',
                'is_approved' => 1,
                'password' => '$2y$10$zDzb.Hzpn..vofU3BQqvkuExWdbsDmJ94wE8aiB/iAYM6NqPUOFUG',
                'phone_number' => '55555555'

            ],

            [
                'name' => 'Hicks',
                'email' => 'hicks22@gmail.com',
                'business_name' => 'Hicks Glass',
                'is_approved' => 1,
                'password' => '$2y$10$wvQhlp896dQnu7uwq7kWMu9u5FKpx60OTgqHlXUaWpSmY3jatYOWq',
                'phone_number' => '55555555'

            ],
            [
                'name' => 'Clearview',
                'email' => 'clearviewautoglasssac@gmail.com',
                'business_name' => 'Clearview Glass',
                'is_approved' => 1,
                'password' => '$2y$10$ToiJoNsePC.y/RDYMJxkBes88dy4HR3VNolzCGTho5q.ra4eLomua',
                'phone_number' => '55555555'

            ],
            [
                'name' => 'Low Cost',
                'email' => 'lcag.sac@gmail.com',
                'business_name' => 'Low Cost Glass',
                'is_approved' => 1,
                'password' => '$2y$10$PmkFgSI4k.p93FXnmlkBmuODRK9vTEqbGcvfBaS.vjH8390/o42.S',
                'phone_number' => '55555555'

            ],

            [
                'name' => 'Vitti Auto Glass',
                'email' => 'iffi_916@yahoo.com',
                'business_name' => 'Vitti Auto Glass',
                'is_approved' => 1,
                'password' => '$2y$10$IZwoHqqzRRoqYctCpyfC0.ByN1IKbpOcXNh0qTmxdaKzZkeDCTjJO',
                'phone_number' => '55555555'

            ],

            [
                'name' => 'Alpha Auto Glass',
                'email' => 'alphaautoglass81@gmail.com',
                'business_name' => 'Alpha Auto Glass',
                'is_approved' => 1,
                'password' => '$2y$10$EHjO5a6rvV26uVeBmNrTLeGk/WObUzsgQHuc3fAXtxkOu3lILLlxG',
                'phone_number' => '55555555'

            ],
            [
                'name' => 'Carlos',
                'email' => 'carlosgasca6472@gmail.com',
                'business_name' => 'Carlog Glass',
                'is_approved' => 1,
                'password' => '$2y$10$xuhYb70l1qaI16YCe4CnOO2eZA1oEN9AXtxYgpbQCrgj59WwuFB0y',
                'phone_number' => '55555555'

            ],
            [
                'name' => 'Gold Rush',
                'email' => 'goldrushautoglass@gmail.com',
                'business_name' => 'Gold Rush Glass',
                'is_approved' => 1,
                'password' => '$2y$10$UPbpe7SK8ouc8lv7J3bu8O91dtfPb53ryLIDhfjJZiEFw606P3yia',
                'phone_number' => '55555555'

            ]
        ];

            foreach ($users as $user) {
                User::create($user);
            }

    }
}
