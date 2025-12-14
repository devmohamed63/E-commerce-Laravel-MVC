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
            [
                'name_en' => 'Minimal Satin Slip Dress (H&M insp.)',
                'name_ar' => 'فستان ساتان سيمبل',
                'category' => 'Dresses',
                'base_price' => 1500,
                'old_price' => 2100,
                'rating' => 4.9,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'],
                'colors' => ['Champagne', 'Black'],
                'images' => ['images/Minimal Satin Slip Dress.png'],
                'tags' => ['New', 'Offer'],
            ],
            [
                'name_en' => 'Soft Knit Bodycon Dress (H&M insp.)',
                'name_ar' => 'فستان سيمبل تريكو',
                'category' => 'Dresses',
                'base_price' => 1950,
                'old_price' => null,
                'rating' => 4.6,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'],
                'colors' => ['Beige'],
                'images' => ['images/Soft Knit Bodycon Dress.png'],
                'tags' => ['Trending'],
            ],
            [
                'name_en' => 'Flowy Layered Dress',
                'name_ar' => 'فستان كلوش لايرز',
                'category' => 'Dresses',
                'base_price' => 1400,
                'old_price' => 1750,
                'rating' => 4.8,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Yellow', 'Baby Blue', 'Black', 'Blue', 'Brown'],
                'images' => ['images/yellow_dress_1.webp'],
                'colorImages' => [
                    'Yellow' => ['images/yellow_dress_1.webp', 'images/yellow_dress_2.webp', 'images/yellow_dress_3.webp', 'images/yellow_dress_4.webp', 'images/yellow_dress_5.webp', 'images/yellow_dress_6.webp', 'images/yellow_dress_7.webp'],
                    'Baby Blue' => ['images/babyblue_dress_1.webp'],
                    'Black' => ['images/black_dress_1.webp'],
                    'Blue' => ['images/blue_dress_1.webp'],
                    'Brown' => ['images/brown_dress_1.webp'],
                ],
                'tags' => ['New'],
            ],
            [
                'name_en' => 'Oversized Blazer Set (Zara style)',
                'name_ar' => 'طقم بليزر اوفر سايز',
                'category' => 'Suits',
                'base_price' => 2700,
                'old_price' => null,
                'rating' => 4.8,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'],
                'colors' => ['Taupe'],
                'images' => ['images/Oversized Blazer Set.png'],
                'tags' => ['Best Seller'],
            ],
            [
                'name_en' => 'Dark Utility Denim Set (Zara denim vibe)',
                'name_ar' => 'طقم جينز غامق عملي',
                'category' => 'Suits',
                'base_price' => 2000,
                'old_price' => 2500,
                'rating' => 4.7,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'],
                'colors' => ['Dark Denim'],
                'images' => ['images/Dark Utility Denim Set.png'],
                'tags' => ['Denim', 'Offer'],
            ],
            [
                'name_en' => 'Structured Shoulder Bag (Shein insp.)',
                'name_ar' => 'شنطة كتف فورمال',
                'category' => 'Bags',
                'base_price' => 980,
                'old_price' => null,
                'rating' => 4.6,
                'gender' => 'women',
                'sizes' => ['One Size'],
                'colors' => ['Black', 'Brown'],
                'images' => ['images/Structured Shoulder Bag.png'],
                'tags' => ['Hot'],
            ],
            [
                'name_en' => 'Soft Everyday Tote (Shein insp.)',
                'name_ar' => 'شنطة كتف يومي',
                'category' => 'Bags',
                'base_price' => 900,
                'old_price' => null,
                'rating' => 4.5,
                'gender' => 'women',
                'sizes' => ['One Size'],
                'colors' => ['Cream', 'Chocolate'],
                'images' => ['images/Soft Everyday Tote.png'],
                'tags' => ['New Color'],
            ],
            [
                'name_en' => 'Classic White Tee (Basic)',
                'name_ar' => 'تيشيرت أبيض كلاسيك',
                'category' => 'Clothing',
                'base_price' => 650,
                'old_price' => null,
                'rating' => 4.5,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'],
                'colors' => ['White'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Essential'],
            ],
            [
                'name_en' => 'Wide-Leg Pants (Beige, Zara vibe)',
                'name_ar' => 'بنطلون واسع بيچ',
                'category' => 'Clothing',
                'base_price' => 1200,
                'old_price' => null,
                'rating' => 4.7,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'],
                'colors' => ['Beige'],
                'images' => ['images/Wide-Leg Pants.png'],
                'tags' => ['Trending'],
            ],
        ];

        foreach ($products as $index => $productData) {
            $category = $categories[$productData['category']];
            
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

            // Create images
            $allImages = [];
            if (isset($productData['colorImages'])) {
                foreach ($productData['colorImages'] as $colorImages) {
                    $allImages = array_merge($allImages, $colorImages);
                }
                $allImages = array_unique($allImages);
            } else {
                $allImages = $productData['images'];
            }

            foreach ($allImages as $sortOrder => $imagePath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $imagePath,
                    'is_primary' => $sortOrder === 0,
                    'sort_order' => $sortOrder,
                ]);
            }

            // Create variants
            foreach ($productData['sizes'] as $size) {
                foreach ($productData['colors'] as $color) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $color,
                        'price' => null,
                        'stock_quantity' => 100,
                    ]);
                }
            }
        }
    }
}
