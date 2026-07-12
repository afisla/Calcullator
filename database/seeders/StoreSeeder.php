<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('PRAGMA foreign_keys = OFF;');
        Store::truncate();
        \DB::statement('PRAGMA foreign_keys = ON;');

        $stores = [
            [
                'name'        => 'Koperasi Al-Amanah',
                'category'    => 'Alat Tulis & Snack',
                'icon_emoji'  => '🏪',
                'pin'         => Hash::make('202619'),
                'description' => 'Menyediakan alat tulis, snack, dan minuman kemasan untuk kebutuhan sehari-hari',
                'sort_order'  => 1,
                'unit'        => 'koperasi',
            ],
            [
                'name'        => 'Warung Bakso & Mie Ayam',
                'category'    => 'Bakso & Mie',
                'icon_emoji'  => '🍜',
                'pin'         => Hash::make('202628'),
                'description' => 'Bakso segar dan mie ayam lezat dengan kuah gurih khas',
                'sort_order'  => 2,
                'unit'        => 'kantin',
            ],
            [
                'name'        => 'Warung Gorengan',
                'category'    => 'Gorengan',
                'icon_emoji'  => '🍤',
                'pin'         => Hash::make('202637'),
                'description' => 'Aneka gorengan hangat dan renyah — tahu, tempe, pisang, dan lainnya',
                'sort_order'  => 3,
                'unit'        => 'kantin',
            ],
            [
                'name'        => 'Warung Sempol',
                'category'    => 'Sempol & Tusukan',
                'icon_emoji'  => '🍡',
                'pin'         => Hash::make('202646'),
                'description' => 'Sempol ayam, cilok, sosis, dan aneka jajanan tusukan favorit siswa',
                'sort_order'  => 4,
                'unit'        => 'kantin',
            ],
            [
                'name'        => 'Warung Madura',
                'category'    => 'Masakan Madura',
                'icon_emoji'  => '🍛',
                'pin'         => Hash::make('202655'),
                'description' => 'Nasi campur, sate ayam, dan soto madura dengan cita rasa autentik',
                'sort_order'  => 5,
                'unit'        => 'kantin',
            ],
            [
                'name'        => 'Warung Nasi',
                'category'    => 'Nasi & Lauk',
                'icon_emoji'  => '🍱',
                'pin'         => Hash::make('202664'),
                'description' => 'Nasi dengan berbagai pilihan lauk pauk lengkap dan bergizi',
                'sort_order'  => 6,
                'unit'        => 'kantin',
            ],
            [
                'name'        => 'Warung Batagor Cireng Pempek',
                'category'    => 'Batagor & Pempek',
                'icon_emoji'  => '🥟',
                'pin'         => Hash::make('202673'),
                'description' => 'Batagor, cireng bumbu, pempek kapal selam, dan tekwan segar',
                'sort_order'  => 7,
                'unit'        => 'kantin',
            ],
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}
