<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    // Data persis dari database.sql asli (data dummy produk)
    public function run(): void
    {
        $products = [
            [
                'name' => 'Kerupuk Udang Original',
                'category' => 'original',
                'price' => 15000,
                'stock' => 100,
                'berat_gram' => 250,
                'description' => 'Kerupuk udang renyah dengan cita rasa original. Dibuat dari udang pilihan yang segar dan diproses secara higienis. Cocok untuk camilan keluarga maupun teman makan.',
            ],
            [
                'name' => 'Kerupuk Ikan Pedas',
                'category' => 'pedas',
                'price' => 18000,
                'stock' => 80,
                'berat_gram' => 250,
                'description' => 'Kerupuk ikan dengan bumbu pedas yang menggugah selera. Menggunakan ikan laut segar pilihan dengan tambahan cabai merah berkualitas tinggi.',
            ],
            [
                'name' => 'Kerupuk Bawang Renyah',
                'category' => 'gurih',
                'price' => 12000,
                'stock' => 150,
                'berat_gram' => 200,
                'description' => 'Kerupuk bawang dengan aroma dan rasa bawang yang kuat. Tekstur renyah dan gurih, cocok sebagai pelengkap nasi atau camilan.',
            ],
            [
                'name' => 'Kerupuk Udang Jumbo',
                'category' => 'original',
                'price' => 28000,
                'stock' => 60,
                'berat_gram' => 500,
                'description' => 'Kerupuk udang ukuran jumbo dengan rasa yang lebih kuat. Kemasan premium 500 gram untuk keluarga besar.',
            ],
            [
                'name' => 'Kerupuk Manis Susu',
                'category' => 'manis',
                'price' => 14000,
                'stock' => 90,
                'berat_gram' => 200,
                'description' => 'Kerupuk unik dengan rasa manis susu. Cocok untuk anak-anak dan yang menyukai camilan manis.',
            ],
            [
                'name' => 'Kerupuk Ikan Original',
                'category' => 'original',
                'price' => 16000,
                'stock' => 120,
                'berat_gram' => 250,
                'description' => 'Kerupuk ikan dengan bumbu original tanpa bahan pengawet. Menggunakan resep tradisional turun-temurun keluarga.',
            ],
        ];

        foreach ($products as $product) {
            $product['is_active'] = true;
            Product::create($product);
        }
    }
}
