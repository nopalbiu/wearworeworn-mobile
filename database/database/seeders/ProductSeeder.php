<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. daftar produk yang mau di-seed
        // catatan id kategori: 1: T-Shirt, 2: Shirt, 3: Polo, 4: Crewneck, 5: Hoodie, 6: Jacket, 7: Jeans, 8: Pants, 9: Accessories
        
        $products = [
            // --- CINCIN (Accessories - ID 9) ---
            [
                'nama_product' => 'Cincin Hitam Type A',
                'deskripsi' => 'Cincin pria warna hitam desain elegan minimalis.',
                'material_pakaian' => 'Titanium',
                'harga' => 75000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'cincin-hitam-a',
                'jumlah_gambar' => 1,
            ],
            [
                'nama_product' => 'Cincin Hitam Type B',
                'deskripsi' => 'Cincin pria warna hitam dengan motif garis di tengah.',
                'material_pakaian' => 'Titanium',
                'harga' => 80000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'cincin-hitam-b',
                'jumlah_gambar' => 2,
            ],
            [
                'nama_product' => 'Cincin Silver Type A',
                'deskripsi' => 'Cincin silver polos desain klasik.',
                'material_pakaian' => 'Stainless Steel',
                'harga' => 65000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'cincin-silver-a',
                'jumlah_gambar' => 2,
            ],
            [
                'nama_product' => 'Cincin Silver Type B',
                'deskripsi' => 'Cincin silver desain tebal dan maskulin.',
                'material_pakaian' => 'Stainless Steel',
                'harga' => 70000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'cincin-silver-b',
                'jumlah_gambar' => 2,
            ],
            [
                'nama_product' => 'Cincin Silver Type C',
                'deskripsi' => 'Cincin silver unik dengan tekstur matte.',
                'material_pakaian' => 'Stainless Steel',
                'harga' => 85000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'cincin-silver-c',
                'jumlah_gambar' => 2,
            ],

            // --- CREWNECK (ID 4) ---
            [
                'nama_product' => 'Crewneck Black Premium',
                'deskripsi' => 'Sweater crewneck hitam bahan tebal dan lembut.',
                'material_pakaian' => 'Cotton Fleece',
                'harga' => 199000,
                'id_category' => 4,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'crewneck-black',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Crewneck Brown Earthy',
                'deskripsi' => 'Sweater crewneck warna coklat earth tone.',
                'material_pakaian' => 'Cotton Fleece',
                'harga' => 199000,
                'id_category' => 4,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'crewneck-brown',
                'jumlah_gambar' => 2,
            ],
            [
                'nama_product' => 'Crewneck Grey Classic',
                'deskripsi' => 'Sweater crewneck abu-abu misty klasik yang mudah di-mix & match.',
                'material_pakaian' => 'Cotton Fleece',
                'harga' => 199000,
                'id_category' => 4,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'crewneck-grey',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Crewneck White Clean',
                'deskripsi' => 'Sweater crewneck warna putih bersih.',
                'material_pakaian' => 'Cotton Fleece',
                'harga' => 199000,
                'id_category' => 4,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'crewneck-white',
                'jumlah_gambar' => 3,
            ],

            // --- GELANG (Accessories - ID 9) ---
            [
                'nama_product' => 'Gelang Hitam Polos',
                'deskripsi' => 'Gelang paracord hitam polos elegan.',
                'material_pakaian' => 'Paracord',
                'harga' => 35000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'gelang-hitam-polos',
                'jumlah_gambar' => 2,
            ],
            [
                'nama_product' => 'Gelang Plus Hitam',
                'deskripsi' => 'Gelang hitam dengan ornamen plus logam.',
                'material_pakaian' => 'Leather & Metal',
                'harga' => 45000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'gelang-plus-hitam',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Gelang Plus Putih',
                'deskripsi' => 'Gelang putih dengan ornamen plus logam.',
                'material_pakaian' => 'Leather & Metal',
                'harga' => 45000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'gelang-plus-putih',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Gelang Silver Polos',
                'deskripsi' => 'Gelang rantai silver polos.',
                'material_pakaian' => 'Stainless Steel',
                'harga' => 55000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'gelang-silver-polos',
                'jumlah_gambar' => 2,
            ],

            // --- HOODIE (ID 5) ---
            [
                'nama_product' => 'Hoodie Black Essential',
                'deskripsi' => 'Jaket hoodie hitam dengan saku kangguru.',
                'material_pakaian' => 'Heavy Cotton Fleece',
                'harga' => 249000,
                'id_category' => 5,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'hoodie-black',
                'jumlah_gambar' => 3,
            ],
            [
                'nama_product' => 'Hoodie Grey Streetwear',
                'deskripsi' => 'Jaket hoodie abu-abu tebal cocok untuk gaya streetwear.',
                'material_pakaian' => 'Heavy Cotton Fleece',
                'harga' => 249000,
                'id_category' => 5,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'hoodie-grey',
                'jumlah_gambar' => 3,
            ],

            // --- JAKET (ID 6) ---
            [
                'nama_product' => 'Jaket Jeans Light Blue',
                'deskripsi' => 'Jaket bahan denim warna biru muda (light blue).',
                'material_pakaian' => 'Denim 14oz',
                'harga' => 289000,
                'id_category' => 6,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'jaket-jeans',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Jaket Jeans Black Washed',
                'deskripsi' => 'Jaket bahan denim warna hitam washed.',
                'material_pakaian' => 'Denim 14oz',
                'harga' => 289000,
                'id_category' => 6,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'jaket-jeans-hitam',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Jaket Kulit Black Rider',
                'deskripsi' => 'Jaket bahan semi kulit sintetis model rider.',
                'material_pakaian' => 'Synthetic Leather (PU)',
                'harga' => 320000,
                'id_category' => 6,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'jaket-kulit-black',
                'jumlah_gambar' => 3,
            ],
            [
                'nama_product' => 'Jaket Harrington Navy',
                'deskripsi' => 'Jaket model harrington warna navy blue.',
                'material_pakaian' => 'Cotton Drill',
                'harga' => 250000,
                'id_category' => 6,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'jaket-navy',
                'jumlah_gambar' => 3,
            ],

            // --- JEANS (ID 7) ---
            [
                'nama_product' => 'Slim Fit Jeans Black',
                'deskripsi' => 'Celana panjang jeans hitam pekat potongan slim.',
                'material_pakaian' => 'Stretch Denim',
                'harga' => 210000,
                'id_category' => 7,
                'tipe_ukuran' => 'celana',
                'prefix_gambar' => 'jeans-black',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Regular Fit Jeans Blue',
                'deskripsi' => 'Celana panjang jeans warna biru terang.',
                'material_pakaian' => 'Denim',
                'harga' => 210000,
                'id_category' => 7,
                'tipe_ukuran' => 'celana',
                'prefix_gambar' => 'jeans-blue',
                'jumlah_gambar' => 4, // Asumsi ini -1 sampai -4 sesuai penamaan webp
            ],
            [
                'nama_product' => 'Jeans Navy Relaxed',
                'deskripsi' => 'Celana panjang jeans warna biru dongker gelap.',
                'material_pakaian' => 'Stretch Denim',
                'harga' => 220000,
                'id_category' => 7,
                'tipe_ukuran' => 'celana',
                'prefix_gambar' => 'jeans-navy',
                'jumlah_gambar' => 4,
            ],

            // --- PANTS (ID 8) ---
            [
                'nama_product' => 'Chino Pants Black',
                'deskripsi' => 'Celana panjang chino warna hitam formal.',
                'material_pakaian' => 'Cotton Twill Stretch',
                'harga' => 189000,
                'id_category' => 8,
                'tipe_ukuran' => 'celana',
                'prefix_gambar' => 'pants-black',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Chino Pants Brown',
                'deskripsi' => 'Celana panjang chino warna coklat khas warna bumi.',
                'material_pakaian' => 'Cotton Twill Stretch',
                'harga' => 189000,
                'id_category' => 8,
                'tipe_ukuran' => 'celana',
                'prefix_gambar' => 'pants-brown',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Chino Pants Dark Grey',
                'deskripsi' => 'Celana panjang chino warna abu-abu gelap.',
                'material_pakaian' => 'Cotton Twill Stretch',
                'harga' => 189000,
                'id_category' => 8,
                'tipe_ukuran' => 'celana',
                'prefix_gambar' => 'pants-dark-grey',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Chino Pants Light Grey',
                'deskripsi' => 'Celana panjang chino warna abu-abu terang.',
                'material_pakaian' => 'Cotton Twill Stretch',
                'harga' => 189000,
                'id_category' => 8,
                'tipe_ukuran' => 'celana',
                'prefix_gambar' => 'pants-grey',
                'jumlah_gambar' => 3,
            ],

            // --- POLO (ID 3) ---
            [
                'nama_product' => 'Polo Shirt Black',
                'deskripsi' => 'Kaos berkerah (polo) warna hitam bahan adem.',
                'material_pakaian' => 'Lacoste CVC',
                'harga' => 149000,
                'id_category' => 3,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'polo-black',
                'jumlah_gambar' => 3,
            ],
            [
                'nama_product' => 'Polo Shirt Brown',
                'deskripsi' => 'Kaos berkerah (polo) warna coklat elegan.',
                'material_pakaian' => 'Lacoste CVC',
                'harga' => 149000,
                'id_category' => 3,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'polo-brown',
                'jumlah_gambar' => 2,
            ],
            [
                'nama_product' => 'Polo Shirt Navy',
                'deskripsi' => 'Kaos berkerah (polo) warna biru dongker profesional.',
                'material_pakaian' => 'Lacoste CVC',
                'harga' => 149000,
                'id_category' => 3,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'polo-navy',
                'jumlah_gambar' => 3,
            ],
            [
                'nama_product' => 'Polo Shirt White Clean',
                'deskripsi' => 'Kaos berkerah (polo) warna putih polos dasar.',
                'material_pakaian' => 'Lacoste CVC',
                'harga' => 149000,
                'id_category' => 3,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'polo-white',
                'jumlah_gambar' => 2,
            ],

            // --- SHIRT/KEMEJA (ID 2) ---
            [
                'nama_product' => 'Kemeja Polos Black',
                'deskripsi' => 'Kemeja lengan panjang hitam formal dan kasual.',
                'material_pakaian' => 'Katun Poplin',
                'harga' => 175000,
                'id_category' => 2,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'shirt-black',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Kemeja Polos Blue',
                'deskripsi' => 'Kemeja lengan panjang biru cerah.',
                'material_pakaian' => 'Katun Poplin',
                'harga' => 175000,
                'id_category' => 2,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'shirt-blue',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Kemeja Polos Brown',
                'deskripsi' => 'Kemeja lengan panjang coklat hangat.',
                'material_pakaian' => 'Katun Poplin',
                'harga' => 175000,
                'id_category' => 2,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'shirt-brown',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Kemeja Polos Grey',
                'deskripsi' => 'Kemeja lengan panjang abu-abu netral.',
                'material_pakaian' => 'Katun Poplin',
                'harga' => 175000,
                'id_category' => 2,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'shirt-grey',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Kemeja Polos Khaki',
                'deskripsi' => 'Kemeja lengan panjang warna khaki krem.',
                'material_pakaian' => 'Katun Poplin',
                'harga' => 175000,
                'id_category' => 2,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'shirt-khaki',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Kemeja Polos Maroon',
                'deskripsi' => 'Kemeja lengan panjang merah maroon mewah.',
                'material_pakaian' => 'Katun Poplin',
                'harga' => 175000,
                'id_category' => 2,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'shirt-maroon',
                'jumlah_gambar' => 3,
            ],
            [
                'nama_product' => 'Kemeja Polos Olive',
                'deskripsi' => 'Kemeja lengan panjang warna hijau olive tentara.',
                'material_pakaian' => 'Katun Poplin',
                'harga' => 175000,
                'id_category' => 2,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'shirt-olive',
                'jumlah_gambar' => 4, // Ada file -4.webp walaupun -3 terlewat (dihandle khusus di bawah)
            ],
            [
                'nama_product' => 'Kemeja Polos White',
                'deskripsi' => 'Kemeja lengan panjang putih bersih esensial.',
                'material_pakaian' => 'Katun Poplin',
                'harga' => 175000,
                'id_category' => 2,
                'tipe_ukuran' => 'panjang',
                'prefix_gambar' => 'shirt-white',
                'jumlah_gambar' => 4,
            ],

            // --- TAS (Accessories - ID 9) ---
            [
                'nama_product' => 'Tas Ransel Big Black',
                'deskripsi' => 'Tas punggung (backpack) warna hitam kapasitas besar.',
                'material_pakaian' => 'Cordura Nylon',
                'harga' => 210000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'tas-big-black',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Tas Ransel Big Navy',
                'deskripsi' => 'Tas punggung warna biru navy kapasitas ekstra besar.',
                'material_pakaian' => 'Cordura Nylon',
                'harga' => 210000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'tas-big-navy',
                'jumlah_gambar' => 3,
            ],
            [
                'nama_product' => 'Slingbag Medium Black',
                'deskripsi' => 'Tas selempang medium warna hitam, cocok untuk hangout.',
                'material_pakaian' => 'Canvas Waterproof',
                'harga' => 120000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'tas-medium-black',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Slingbag Medium Brown',
                'deskripsi' => 'Tas selempang ukuran medium warna coklat tua.',
                'material_pakaian' => 'Canvas Waterproof',
                'harga' => 120000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'tas-medium-brown',
                'jumlah_gambar' => 2,
            ],
            [
                'nama_product' => 'Pouch Bag Small Black',
                'deskripsi' => 'Tas kecil (pouch) hitam cocok untuk HP dan dompet.',
                'material_pakaian' => 'Nylon',
                'harga' => 85000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'tas-small',
                'jumlah_gambar' => 1,
            ],

            // --- TOPI (Accessories - ID 9) ---
            [
                'nama_product' => 'Topi Baseball Coklat',
                'deskripsi' => 'Topi baseball warna coklat.',
                'material_pakaian' => 'Katun Drill',
                'harga' => 65000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'topi-coklat',
                'jumlah_gambar' => 2,
            ],
            [
                'nama_product' => 'Topi Baseball Hitam',
                'deskripsi' => 'Topi baseball warna hitam polos dengan strap.',
                'material_pakaian' => 'Katun Drill',
                'harga' => 65000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'topi-hitam',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'Topi Trucker Hitam Full',
                'deskripsi' => 'Topi desain jaring trucker hitam seluruhnya.',
                'material_pakaian' => 'Mesh & Drill',
                'harga' => 55000,
                'id_category' => 9,
                'tipe_ukuran' => 'aksesoris',
                'prefix_gambar' => 'topi-hitam-full',
                'jumlah_gambar' => 4,
            ],

            // --- T-SHIRT (ID 1) ---
            [
                'nama_product' => 'T-Shirt Black Combed',
                'deskripsi' => 'Kaos lengan pendek hitam bahan combed 30s premium.',
                'material_pakaian' => 'Cotton Combed 30s',
                'harga' => 99000,
                'id_category' => 1,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'tshirt-black',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'T-Shirt Blue Light',
                'deskripsi' => 'Kaos warna biru terang.',
                'material_pakaian' => 'Cotton Combed 30s',
                'harga' => 99000,
                'id_category' => 1,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'tshirt-blue',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'T-Shirt Brown Classic',
                'deskripsi' => 'Kaos pendek coklat earth tone.',
                'material_pakaian' => 'Cotton Combed 30s',
                'harga' => 99000,
                'id_category' => 1,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'tshirt-brown',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'T-Shirt Grey Misty',
                'deskripsi' => 'Kaos pendek warna abu-abu tekstur misty.',
                'material_pakaian' => 'Cotton Blend',
                'harga' => 99000,
                'id_category' => 1,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'tshirt-grey',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'T-Shirt Khaki Sand',
                'deskripsi' => 'Kaos pendek warna khaki nuansa pasir.',
                'material_pakaian' => 'Cotton Combed 30s',
                'harga' => 99000,
                'id_category' => 1,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'tshirt-khaki',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'T-Shirt Maroon Red',
                'deskripsi' => 'Kaos warna merah maroon.',
                'material_pakaian' => 'Cotton Combed 30s',
                'harga' => 99000,
                'id_category' => 1,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'tshirt-maroon',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'T-Shirt Olive Green',
                'deskripsi' => 'Kaos hijau olive ala warna army.',
                'material_pakaian' => 'Cotton Combed 30s',
                'harga' => 99000,
                'id_category' => 1,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'tshirt-olive',
                'jumlah_gambar' => 4,
            ],
            [
                'nama_product' => 'T-Shirt White Basic',
                'deskripsi' => 'Kaos putih pendek dasar wajib punya.',
                'material_pakaian' => 'Cotton Combed 30s',
                'harga' => 99000,
                'id_category' => 1,
                'tipe_ukuran' => 'pendek',
                'prefix_gambar' => 'tshirt-white',
                'jumlah_gambar' => 4,
            ],
        ];

        // 2. logic otomatis buat nyebarin array di atas ke 4 tabel database
        foreach ($products as $item) {
            
            // a. tentukan url size chart sesuai tipe ukuran
            $sizeChart = null;
            if ($item['tipe_ukuran'] === 'celana') {
                $sizeChart = 'sizecharts/size-cart-celana.png';
            } elseif ($item['tipe_ukuran'] === 'pendek') {
                $sizeChart = 'sizecharts/size-chart-lengan-pendek.png';
            } elseif ($item['tipe_ukuran'] === 'panjang') {
                $sizeChart = 'sizecharts/size-chart-lengan-panjang.png';
            }

            // b. insert data ke tabel products utama
            $productId = DB::table('products')->insertGetId([
                'nama_product' => $item['nama_product'],
                'deskripsi' => $item['deskripsi'],
                'material_pakaian' => $item['material_pakaian'],
                'url_size_chart' => $sizeChart,
                'harga' => $item['harga'],
            ]);

            // c. sambungin produk ke tabel product_category
            DB::table('product_category')->insert([
                'id_product' => $productId,
                'id_category' => $item['id_category'],
            ]);

            // d. insert looping foto ke tabel product_images
            // Handle khusus untuk shirt-olive karena ada file yang nomor 3 nya lompat
            if ($item['prefix_gambar'] === 'shirt-olive') {
                $images = [1, 2, 4];
                foreach ($images as $index => $imgNum) {
                    DB::table('product_images')->insert([
                        'id_product' => $productId,
                        'url_gambar' => 'products/' . $item['prefix_gambar'] . '-' . $imgNum . '.webp',
                        'is_primary' => ($index === 0) ? 1 : 0, 
                    ]);
                }
            } else {
                for ($i = 1; $i <= $item['jumlah_gambar']; $i++) {
                    DB::table('product_images')->insert([
                        'id_product' => $productId,
                        'url_gambar' => 'products/' . $item['prefix_gambar'] . '-' . $i . '.webp',
                        'is_primary' => ($i == 1) ? 1 : 0, 
                    ]);
                }
            }

            // Khusus handle jeans-blue karena nama file dari kamu start dari 2
            if ($item['prefix_gambar'] === 'jeans-blue') {
                 // reset dan ganti dengan foto yang bener 
                 DB::table('product_images')->where('id_product', $productId)->delete();
                 $blueJeansImgs = [2, 3, 4];
                 foreach ($blueJeansImgs as $index => $imgNum) {
                    DB::table('product_images')->insert([
                        'id_product' => $productId,
                        'url_gambar' => 'products/jeans-blue-' . $imgNum . '.webp',
                        'is_primary' => ($index === 0) ? 1 : 0, 
                    ]);
                }
            }
            
            // Khusus handle jeans-black (2)
            if ($item['prefix_gambar'] === 'jeans-black') {
                // nambahin foto dengan nama beda
                 DB::table('product_images')->insert([
                     'id_product' => $productId,
                     'url_gambar' => 'products/jeans-black-1 (2).webp',
                     'is_primary' => 0, 
                 ]);
             }

            // e. generate stok di tabel product_variants
            if ($item['tipe_ukuran'] === 'aksesoris') {
                DB::table('product_variants')->insert([
                    'id_product' => $productId,
                    'id_size' => 6, 
                    'stok' => 50, 
                ]);
            } else {
                for ($sizeId = 1; $sizeId <= 5; $sizeId++) {
                    DB::table('product_variants')->insert([
                        'id_product' => $productId,
                        'id_size' => $sizeId,
                        'stok' => rand(10, 25), 
                    ]);
                }
            }
        }
    }
}