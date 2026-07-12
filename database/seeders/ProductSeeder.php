<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = [
            // 1. Koperasi Al-Amanah
            'Koperasi Al-Amanah' => [
                ['name' => 'Seragam Putih Biru', 'price' => 100000, 'description' => 'Seragam putih biru resmi sekolah'],
                ['name' => 'Seragam Pramuka', 'price' => 110000, 'description' => 'Pakaian seragam pramuka lengkap'],
                ['name' => 'Kaos Kaki Sekolah', 'price' => 10000, 'description' => 'Kaos kaki logo sekolah resmi'],
                ['name' => 'Ikat Pinggang', 'price' => 15000, 'description' => 'Ikat pinggang hitam berlogo sekolah'],
                ['name' => 'Bed Sekolah', 'price' => 5000, 'description' => 'Bed/atribut logo sekolah bordir'],
                ['name' => 'Bed Kelas', 'price' => 5000, 'description' => 'Bed/atribut nomor kelas'],
                ['name' => 'Pensil', 'price' => 3000, 'description' => 'Pensil 2B untuk kegiatan belajar'],
                ['name' => 'Pulpen', 'price' => 4000, 'description' => 'Pulpen tinta hitam berkualitas'],
                ['name' => 'Penghapus', 'price' => 2000, 'description' => 'Penghapus bersih ramah kertas'],
                ['name' => 'Penggaris', 'price' => 3000, 'description' => 'Penggaris plastik transparan 30cm'],
                ['name' => 'Correction Tape', 'price' => 8000, 'description' => 'Tip-ex kertas/correction tape praktis'],
                ['name' => 'Buku Tulis', 'price' => 5000, 'description' => 'Buku tulis bergaris ukuran standar'],
                ['name' => 'Spidol', 'price' => 8000, 'description' => 'Spidol whiteboard aneka warna'],
                ['name' => 'Penghapus Papan Tulis', 'price' => 7000, 'description' => 'Penghapus spidol whiteboard bersih'],
                ['name' => 'Tinta Spidol', 'price' => 15000, 'description' => 'Tinta spidol whiteboard refill'],
                ['name' => 'Topi Sekolah', 'price' => 15000, 'description' => 'Topi sekolah resmi untuk upacara'],
                ['name' => 'Dasi Sekolah', 'price' => 12000, 'description' => 'Dasi berlogo sekolah resmi'],
                ['name' => 'HVS', 'price' => 45000, 'description' => 'Satu rim kertas HVS A4 70gsm/80gsm'],
                ['name' => 'Kertas Folio', 'price' => 10000, 'description' => 'Satu pak kertas folio bergaris'],
                ['name' => 'Buku Gambar', 'price' => 6000, 'description' => 'Buku gambar ukuran A4'],
                ['name' => 'Rautan', 'price' => 4000, 'description' => 'Rautan pensil tajam dan praktis'],
                ['name' => 'Pensil Warna', 'price' => 20000, 'description' => 'Set pensil warna untuk mewarnai'],
                ['name' => 'Buku Paket dan LKS', 'price' => 15000, 'description' => 'Buku paket pelajaran atau Lembar Kerja Siswa'],
                ['name' => 'Sampul Buku', 'price' => 1000, 'description' => 'Sampul buku plastik/kertas cokelat per lembar'],
            ],
            // 2. Warung Bakso & Mie Ayam
            'Warung Bakso & Mie Ayam' => [
                ['name' => 'Bakso Biasa', 'price' => 10000, 'description' => '8 butir bakso dengan kuah kaldu segar'],
                ['name' => 'Bakso Urat', 'price' => 12000, 'description' => 'Bakso urat kenyal dengan kuah gurih'],
                ['name' => 'Mie Ayam', 'price' => 10000, 'description' => 'Mie ayam dengan topping ayam dan caisim'],
                ['name' => 'Mie Ayam Bakso', 'price' => 13000, 'description' => 'Mie ayam lengkap dengan tambahan bakso'],
                ['name' => 'Es Teh Manis', 'price' => 3000, 'description' => 'Es teh manis menyegarkan'],
                ['name' => 'Air Putih', 'price' => 2000, 'description' => 'Air putih gelas'],
            ],
            // 3. Warung Gorengan
            'Warung Gorengan' => [
                ['name' => 'Tahu Goreng', 'price' => 1000, 'description' => 'Tahu goreng crispy, per biji'],
                ['name' => 'Tempe Goreng', 'price' => 1000, 'description' => 'Tempe goreng renyah, per biji'],
                ['name' => 'Pisang Goreng', 'price' => 2000, 'description' => 'Pisang goreng manis, per biji'],
                ['name' => 'Ubi Goreng', 'price' => 2000, 'description' => 'Ubi goreng manis legit, per biji'],
                ['name' => 'Risol Mayonaise', 'price' => 3000, 'description' => 'Risol isi sayuran dengan mayonaise'],
                ['name' => 'Bakwan Sayur', 'price' => 2000, 'description' => 'Bakwan sayur crispy, per biji'],
                ['name' => 'Combro', 'price' => 2000, 'description' => 'Combro oncom pedas, per biji'],
            ],
            // 4. Warung Sempol
            'Warung Sempol' => [
                ['name' => 'Sempol Ayam', 'price' => 2000, 'description' => 'Sempol ayam goreng dengan bumbu kacang, per tusuk'],
                ['name' => 'Cilok Biasa', 'price' => 1000, 'description' => 'Cilok kenyal dengan bumbu kacang, per biji'],
                ['name' => 'Cilok Kuah', 'price' => 5000, 'description' => 'Cilok kuah pedas nikmat, 1 porsi (8 biji)'],
                ['name' => 'Sosis Goreng Tusuk', 'price' => 3000, 'description' => 'Sosis goreng crispy, per tusuk'],
                ['name' => 'Pentol Goreng', 'price' => 1000, 'description' => 'Pentol goreng renyah, per biji'],
                ['name' => 'Nugget Tusuk', 'price' => 3000, 'description' => 'Nugget ayam crispy, per tusuk'],
            ],
            // 5. Warung Madura
            'Warung Madura' => [
                ['name' => 'Nasi Campur Madura', 'price' => 12000, 'description' => 'Nasi dengan lauk khas Madura (ayam, tahu, tempe)'],
                ['name' => 'Sate Ayam (5 tusuk)', 'price' => 10000, 'description' => 'Sate ayam bumbu kacang/kecap, 5 tusuk'],
                ['name' => 'Soto Madura', 'price' => 10000, 'description' => 'Soto madura dengan kuah bening gurih'],
                ['name' => 'Lontong Sayur', 'price' => 7000, 'description' => 'Lontong dengan sayur lodeh khas'],
                ['name' => 'Es Jeruk', 'price' => 4000, 'description' => 'Es jeruk peras segar'],
                ['name' => 'Es Teh Madura', 'price' => 3000, 'description' => 'Teh manis khas Madura yang kental'],
            ],
            // 6. Warung Nasi
            'Warung Nasi' => [
                ['name' => 'Nasi + Ayam Goreng', 'price' => 13000, 'description' => 'Nasi putih dengan ayam goreng crispy + lalapan'],
                ['name' => 'Nasi + Ikan Goreng', 'price' => 12000, 'description' => 'Nasi putih dengan ikan goreng + sambal'],
                ['name' => 'Nasi + Tempe & Tahu', 'price' => 8000, 'description' => 'Nasi putih dengan tempe tahu goreng + sayur'],
                ['name' => 'Nasi Goreng Biasa', 'price' => 10000, 'description' => 'Nasi goreng dengan telur dan kerupuk'],
                ['name' => 'Nasi Goreng Ayam', 'price' => 13000, 'description' => 'Nasi goreng dengan potongan ayam'],
                ['name' => 'Es Teh Manis', 'price' => 3000, 'description' => 'Es teh manis segar'],
                ['name' => 'Air Putih', 'price' => 2000, 'description' => 'Air putih gelas'],
            ],
            // 7. Warung Batagor Cireng Pempek
            'Warung Batagor Cireng Pempek' => [
                ['name' => 'Batagor (1 porsi)', 'price' => 8000, 'description' => 'Batagor tahu isi ikan dengan bumbu kacang'],
                ['name' => 'Cireng Bumbu Rujak', 'price' => 5000, 'description' => 'Cireng crispy dengan bumbu rujak pedas manis'],
                ['name' => 'Pempek Kapal Selam', 'price' => 12000, 'description' => 'Pempek kapal selam besar isi telur + cuko'],
                ['name' => 'Pempek Lenjer', 'price' => 6000, 'description' => 'Pempek lenjer gurih + cuko asam pedas'],
                ['name' => 'Pempek Adaan', 'price' => 5000, 'description' => 'Pempek bulat khas Palembang + cuko'],
                ['name' => 'Tekwan Kuah', 'price' => 10000, 'description' => 'Tekwan kuah udang hangat dan gurih'],
            ],
        ];

        foreach ($products as $storeName => $items) {
            $store = Store::where('name', $storeName)->first();
            if (!$store) continue;

            foreach ($items as $index => $item) {
                Product::create([
                    'store_id'    => $store->id,
                    'name'        => $item['name'],
                    'price'       => $item['price'],
                    'description' => $item['description'],
                    'is_available' => true,
                    'stock'       => rand(15, 80),
                    'sort_order'  => $index + 1,
                ]);
            }
        }
    }
}
