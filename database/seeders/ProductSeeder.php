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
            // ============ DRESSES (5 products) ============
            [
                'name_en' => 'Minimal Satin Slip Dress',
                'name_ar' => 'فستان ساتان سيمبل',
                'category' => 'Dresses',
                'base_price' => 1500,
                'old_price' => 2100,
                'rating' => 4.9,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL'],
                'colors' => ['Champagne', 'Black'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['New', 'Offer'],
                'stock' => 150,
            ],
            [
                'name_en' => 'Soft Knit Bodycon Dress',
                'name_ar' => 'فستان تريكو بودي كون',
                'category' => 'Dresses',
                'base_price' => 1950,
                'old_price' => null,
                'rating' => 4.6,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'colors' => ['Beige', 'Black'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Trending'],
                'stock' => 200,
            ],
            [
                'name_en' => 'Flowy Layered Maxi Dress',
                'name_ar' => 'فستان ماكسي لايرز',
                'category' => 'Dresses',
                'base_price' => 1400,
                'old_price' => 1750,
                'rating' => 4.8,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Yellow', 'Baby Blue', 'Black'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['New'],
                'stock' => 120,
            ],
            [
                'name_en' => 'Elegant Evening Gown',
                'name_ar' => 'فستان سهرة أنيق',
                'category' => 'Dresses',
                'base_price' => 3500,
                'old_price' => 4200,
                'rating' => 4.9,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Navy', 'Burgundy', 'Black'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Luxury', 'Best Seller'],
                'stock' => 80,
            ],
            [
                'name_en' => 'Casual Summer Dress',
                'name_ar' => 'فستان صيفي كاجوال',
                'category' => 'Dresses',
                'base_price' => 850,
                'old_price' => null,
                'rating' => 4.4,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL'],
                'colors' => ['White', 'Pink', 'Green'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Essential'],
                'stock' => 300,
            ],

            // ============ SUITS (3 products) ============
            [
                'name_en' => 'Oversized Blazer Set',
                'name_ar' => 'طقم بليزر اوفر سايز',
                'category' => 'Suits',
                'base_price' => 2700,
                'old_price' => null,
                'rating' => 4.8,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'colors' => ['Taupe', 'Black'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Best Seller'],
                'stock' => 100,
            ],
            [
                'name_en' => 'Dark Utility Denim Set',
                'name_ar' => 'طقم جينز غامق عملي',
                'category' => 'Suits',
                'base_price' => 2000,
                'old_price' => 2500,
                'rating' => 4.7,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL'],
                'colors' => ['Dark Denim', 'Light Denim'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Denim', 'Offer'],
                'stock' => 180,
            ],
            [
                'name_en' => 'Power Suit Collection',
                'name_ar' => 'طقم باور سوت',
                'category' => 'Suits',
                'base_price' => 3200,
                'old_price' => 3800,
                'rating' => 4.9,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Gray', 'Black', 'Navy'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Premium', 'Office'],
                'stock' => 90,
            ],

            // ============ BAGS (4 products) ============
            [
                'name_en' => 'Structured Shoulder Bag',
                'name_ar' => 'شنطة كتف فورمال',
                'category' => 'Bags',
                'base_price' => 980,
                'old_price' => null,
                'rating' => 4.6,
                'gender' => 'women',
                'sizes' => ['One Size'],
                'colors' => ['Black', 'Brown', 'Beige'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Hot'],
                'stock' => 250,
            ],
            [
                'name_en' => 'Soft Everyday Tote',
                'name_ar' => 'شنطة كتف يومي',
                'category' => 'Bags',
                'base_price' => 900,
                'old_price' => null,
                'rating' => 4.5,
                'gender' => 'women',
                'sizes' => ['One Size'],
                'colors' => ['Cream', 'Chocolate', 'Black'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['New Color'],
                'stock' => 200,
            ],
            [
                'name_en' => 'Mini Crossbody Bag',
                'name_ar' => 'شنطة كروس صغيرة',
                'category' => 'Bags',
                'base_price' => 650,
                'old_price' => 800,
                'rating' => 4.7,
                'gender' => 'women',
                'sizes' => ['One Size'],
                'colors' => ['Pink', 'White', 'Black'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Trending', 'Offer'],
                'stock' => 350,
            ],
            [
                'name_en' => 'Luxury Leather Handbag',
                'name_ar' => 'شنطة جلد فاخرة',
                'category' => 'Bags',
                'base_price' => 2200,
                'old_price' => null,
                'rating' => 4.9,
                'gender' => 'women',
                'sizes' => ['One Size'],
                'colors' => ['Tan', 'Black'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Luxury', 'Premium'],
                'stock' => 60,
            ],

            // ============ CLOTHING (6 products) ============
            [
                'name_en' => 'Classic White Tee',
                'name_ar' => 'تيشيرت أبيض كلاسيك',
                'category' => 'Clothing',
                'base_price' => 650,
                'old_price' => null,
                'rating' => 4.5,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'],
                'colors' => ['White', 'Black', 'Gray'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Essential'],
                'stock' => 500,
            ],
            [
                'name_en' => 'Wide-Leg Pants',
                'name_ar' => 'بنطلون واسع',
                'category' => 'Clothing',
                'base_price' => 1200,
                'old_price' => null,
                'rating' => 4.7,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', '2XL'],
                'colors' => ['Beige', 'Black', 'Navy'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Trending'],
                'stock' => 220,
            ],
            [
                'name_en' => 'Cozy Knit Sweater',
                'name_ar' => 'سويتر تريكو ناعم',
                'category' => 'Clothing',
                'base_price' => 1100,
                'old_price' => 1400,
                'rating' => 4.6,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Cream', 'Brown', 'Gray'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Winter', 'Offer'],
                'stock' => 180,
            ],
            [
                'name_en' => 'High-Waist Jeans',
                'name_ar' => 'جينز هاي ويست',
                'category' => 'Clothing',
                'base_price' => 1350,
                'old_price' => null,
                'rating' => 4.8,
                'gender' => 'women',
                'sizes' => ['24', '26', '28', '30', '32', '34'],
                'colors' => ['Blue', 'Black', 'Light Blue'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Best Seller', 'Denim'],
                'stock' => 400,
            ],
            [
                'name_en' => 'Silk Blouse',
                'name_ar' => 'بلوزة حرير',
                'category' => 'Clothing',
                'base_price' => 1600,
                'old_price' => 2000,
                'rating' => 4.7,
                'gender' => 'women',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'colors' => ['White', 'Blush', 'Black'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Elegant', 'Offer'],
                'stock' => 150,
            ],
            [
                'name_en' => 'Casual Linen Shirt',
                'name_ar' => 'قميص كتان كاجوال',
                'category' => 'Clothing',
                'base_price' => 950,
                'old_price' => null,
                'rating' => 4.5,
                'gender' => 'women',
                'sizes' => ['S', 'M', 'L', 'XL', '2XL'],
                'colors' => ['White', 'Sky Blue', 'Beige'],
                'images' => ['images/Classic White Tee.png'],
                'tags' => ['Summer', 'New'],
                'stock' => 280,
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

            // Create image
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
