<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        $products = [
            // Product 1 - Multiple images
            [
                'name_en' => 'Elegant Floral Dress',
                'name_ar' => 'فستان زهور أنيق',
                'category' => 'Dresses',
                'base_price' => 1800,
                'old_price' => 2200,
                'rating' => 4.9,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Blue', 'Pink'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 6.40.15 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.40.40 AM.jpeg',
                ],
                'tags' => ['New', 'Best Seller'],
                'stock' => 150,
            ],
            // Product 2 - Multiple images
            [
                'name_en' => 'Classic Casual Outfit',
                'name_ar' => 'طقم كاجوال كلاسيك',
                'category' => 'Clothing',
                'base_price' => 1500,
                'old_price' => null,
                'rating' => 4.7,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'colors' => ['Beige', 'Black'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 6.40.40 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.41.28 AM.jpeg',
                ],
                'tags' => ['Trending'],
                'stock' => 200,
            ],
            // Product 3 - Multiple images
            [
                'name_en' => 'Stylish Summer Look',
                'name_ar' => 'إطلالة صيفية أنيقة',
                'category' => 'Clothing',
                'base_price' => 1350,
                'old_price' => 1650,
                'rating' => 4.8,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['White', 'Cream'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 6.41.28 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.41.47 AM.jpeg',
                ],
                'tags' => ['Summer', 'Offer'],
                'stock' => 180,
            ],
            // Product 4 - Multiple images
            [
                'name_en' => 'Modern Street Style',
                'name_ar' => 'ستايل شارع عصري',
                'category' => 'Clothing',
                'base_price' => 1200,
                'old_price' => null,
                'rating' => 4.6,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL'],
                'colors' => ['Black', 'Gray'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 6.41.47 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.40.15 AM.jpeg',
                ],
                'tags' => ['Essential'],
                'stock' => 250,
            ],
            // Product 5 - Multiple images
            [
                'name_en' => 'Premium Fashion Set',
                'name_ar' => 'طقم أزياء فاخر',
                'category' => 'Suits',
                'base_price' => 2500,
                'old_price' => 3000,
                'rating' => 4.9,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Navy', 'Black'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 6.40.15 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 7.01.28 AM.jpeg',
                ],
                'tags' => ['Premium', 'Best Seller'],
                'stock' => 100,
            ],
            // Product 6 - Multiple images with new photos
            [
                'name_en' => 'Chic Office Blazer',
                'name_ar' => 'بليزر مكتبي أنيق',
                'category' => 'Suits',
                'base_price' => 2200,
                'old_price' => 2700,
                'rating' => 4.8,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Gray', 'Black'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 7.01.28 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 7.01.25 AM.jpeg',
                ],
                'tags' => ['Office', 'Offer'],
                'stock' => 120,
            ],
            // Product 7 - Multiple images
            [
                'name_en' => 'Casual Day Dress',
                'name_ar' => 'فستان يومي كاجوال',
                'category' => 'Dresses',
                'base_price' => 950,
                'old_price' => null,
                'rating' => 4.5,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL'],
                'colors' => ['White', 'Pink', 'Blue'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 7.01.25 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.41.28 AM.jpeg',
                ],
                'tags' => ['Casual'],
                'stock' => 300,
            ],
            // Product 8 - Multiple images
            [
                'name_en' => 'Evening Gown Collection',
                'name_ar' => 'فستان سهرة مميز',
                'category' => 'Dresses',
                'base_price' => 3500,
                'old_price' => 4200,
                'rating' => 4.9,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Navy', 'Burgundy', 'Black'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 6.41.47 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 7.01.28 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 7.01.25 AM.jpeg',
                ],
                'tags' => ['Luxury', 'New'],
                'stock' => 80,
            ],
            // Product 9 - Multiple images
            [
                'name_en' => 'Trendy Coordinates Set',
                'name_ar' => 'طقم كورديناتس ترندي',
                'category' => 'Clothing',
                'base_price' => 1650,
                'old_price' => 2000,
                'rating' => 4.7,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Beige', 'Brown'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 6.40.15 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 7.01.25 AM.jpeg',
                ],
                'tags' => ['Trending', 'Offer'],
                'stock' => 150,
            ],
            // Product 10 - Multiple images
            [
                'name_en' => 'Minimal Chic Outfit',
                'name_ar' => 'طقم مينيمال شيك',
                'category' => 'Clothing',
                'base_price' => 1400,
                'old_price' => null,
                'rating' => 4.6,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'colors' => ['White', 'Black'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 6.40.40 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 7.01.28 AM.jpeg',
                ],
                'tags' => ['Minimal', 'Essential'],
                'stock' => 200,
            ],
            // ========== NEW 5 PRODUCTS WITH NEW IMAGES ==========
            // Product 11
            [
                'name_en' => 'Cozy Winter Hoodie',
                'name_ar' => 'هودي شتوي دافئ',
                'category' => 'Clothing',
                'base_price' => 1100,
                'old_price' => 1400,
                'rating' => 4.8,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L', 'XL', '2XL'],
                'colors' => ['Gray', 'Black', 'Navy'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 7.01.28 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.40.15 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.40.40 AM.jpeg',
                ],
                'tags' => ['Winter', 'Best Seller'],
                'stock' => 250,
            ],
            // Product 12
            [
                'name_en' => 'Casual Denim Set',
                'name_ar' => 'طقم جينز كاجوال',
                'category' => 'Clothing',
                'base_price' => 1750,
                'old_price' => 2100,
                'rating' => 4.7,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'colors' => ['Blue', 'Light Blue'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 7.01.25 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.41.47 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.41.28 AM.jpeg',
                ],
                'tags' => ['Denim', 'Offer'],
                'stock' => 180,
            ],
            // Product 13
            [
                'name_en' => 'Elegant Party Dress',
                'name_ar' => 'فستان حفلات أنيق',
                'category' => 'Dresses',
                'base_price' => 2800,
                'old_price' => 3400,
                'rating' => 4.9,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Black', 'Red', 'Gold'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 7.01.28 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 7.01.25 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.40.15 AM.jpeg',
                ],
                'tags' => ['Party', 'Luxury', 'New'],
                'stock' => 90,
            ],
            // Product 14
            [
                'name_en' => 'Smart Casual Pants',
                'name_ar' => 'بنطلون سمارت كاجوال',
                'category' => 'Clothing',
                'base_price' => 980,
                'old_price' => null,
                'rating' => 4.6,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL'],
                'colors' => ['Black', 'Beige', 'Navy'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 7.01.25 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 7.01.28 AM.jpeg',
                ],
                'tags' => ['Essential', 'Office'],
                'stock' => 350,
            ],
            // Product 15
            [
                'name_en' => 'Summer Maxi Dress',
                'name_ar' => 'فستان ماكسي صيفي',
                'category' => 'Dresses',
                'base_price' => 1550,
                'old_price' => 1900,
                'rating' => 4.8,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['White', 'Floral', 'Blue'],
                'images' => [
                    'images/WhatsApp Image 2025-12-21 at 7.01.28 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.40.40 AM.jpeg',
                    'images/WhatsApp Image 2025-12-21 at 6.41.28 AM.jpeg',
                ],
                'tags' => ['Summer', 'New', 'Trending'],
                'stock' => 200,
            ],
        ];

        foreach ($products as $index => $productData) {
            $category = $categories[$productData['category']] ?? null;
            
            if (!$category) {
                continue;
            }
            
            $product = Product::create([
                'category_id' => $category->id,
                'name_en' => $productData['name_en'],
                'name_ar' => $productData['name_ar'],
                'base_price' => $productData['base_price'],
                'old_price' => $productData['old_price'],
                'rating' => $productData['rating'],
                'gender' => $productData['gender'],
                'tags' => isset($productData['tags']) ? implode(',', $productData['tags']) : null,
            ]);

            // Create images - now supports multiple images per product
            $defaultColor = $productData['colors'][0] ?? null;
            foreach ($productData['images'] as $sortOrder => $imagePath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $imagePath,
                    'color' => $defaultColor,
                    'is_primary' => $sortOrder === 0,
                    'sort_order' => $sortOrder,
                ]);
            }

            // Create variants with stock
            $stockPerVariant = $productData['stock'] ?? 100;
            foreach ($productData['sizes'] as $size) {
                foreach ($productData['colors'] as $color) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $color,
                        'price' => null,
                        'stock_quantity' => $stockPerVariant,
                    ]);
                }
            }
        }
    }
}
