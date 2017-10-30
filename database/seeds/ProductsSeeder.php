<?php
use Database\traits\TruncateTable;
use Database\traits\DisableForeignKeys;

use Faker\Generator;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
	use DisableForeignKeys, TruncateTable;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker\Generator $faker)
    {

        $this->disableForeignKeys();
        $this->truncate('products');

        $products = [
            [
                'name' => 'Pack de 5 post',
                'description' => $faker->text($maxNbChars = 200),
                'price' => '100',
                'video_route' => $faker->url,
                'item_id' => "2",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Pack de 10 post',
                'description' => $faker->text($maxNbChars = 200),
                'price' => '190',
                'video_route' => $faker->url,
                'item_id' => "2",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Pack de 15 post',
                'description' => $faker->text($maxNbChars = 200),
                'price' => '125',
                'video_route' => $faker->url,
                'item_id' => "2",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Pack de 20 post',
                'description' => $faker->text($maxNbChars = 200),
                'price' => '180',
                'video_route' => $faker->url,
                'item_id' => "2",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Vídeo de 15 seg',
                'description' => $faker->text($maxNbChars = 200),
                'price' => '100',
                'video_route' => $faker->url,
                'item_id' => "2",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Vídeos de 30 seg',
                'description' => $faker->text($maxNbChars = 200),
                'price' => '190',
                'video_route' => $faker->url,
                'item_id' => "1",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Vídeo de 45 seg',
                'description' => $faker->text($maxNbChars = 200),
                'price' => '125',
                'video_route' => $faker->url,
                'item_id' => "1",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Vídeo de 60 seg',
                'description' => $faker->text($maxNbChars = 200),
                'price' => '180',
                'video_route' => $faker->url,
                'item_id' => "1",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]            

        ];

        DB::table('products')->insert($products);

        $this->enableForeignKeys();
    }
}
