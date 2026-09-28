<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

namespace Database\Seeders;

=======
>>>>>>> origin/main
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
<<<<<<< HEAD
            AreaSeeder::class,
        ]);
    }
}
=======
            RoleSeeder::class,
            AreaSeeder::class,
        ]);
    }
}
>>>>>>> origin/main
