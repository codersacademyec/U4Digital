<?php
use Database\traits\TruncateTable;
use Database\traits\DisableForeignKeys;

use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsSeeder extends Seeder
{
	use DisableForeignKeys, TruncateTable;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker\Generator $faker)
    {
        //factory(App\Models\Item::class, 4)->create();

        $this->disableForeignKeys();
        $this->truncate('items');

        $items = [
            [
                'name' => 'Vídeo',
                'description' => $faker->text($maxNbChars = 200)
            ],
            [
                'name' => 'Post',
                'description' => $faker->text($maxNbChars = 200)
            ],
            [
                'name' => 'Informe',
                'description' => $faker->text($maxNbChars = 200)
            ],
            [
                'name' => 'Otro',
                'description' => $faker->text($maxNbChars = 200)
            ]            

        ];

        DB::table('items')->insert($items);

        $this->enableForeignKeys();
    }
}
