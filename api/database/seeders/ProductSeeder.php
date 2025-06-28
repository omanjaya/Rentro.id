<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define Unsplash images by category
        $categoryImages = [
            1 => [ // Laptop & Computer
                'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1587614203976-365c74645e83?w=500&h=300&fit=crop'
            ],
            2 => [ // Camera & Photography
                'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1581591524425-c7e0978865fc?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1567450156085-b5ecfad4c3e3?w=500&h=300&fit=crop'
            ],
            3 => [ // Audio & Sound
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1558618666-fbd647c5cd82?w=500&h=300&fit=crop'
            ],
            4 => [ // Gaming
                'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1592840062661-eb5d9bc05499?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1601656002819-0d27847c3c82?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=500&h=300&fit=crop'
            ],
            5 => [ // Smartphone & Tablet
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1574944985070-8f3ebc6b79d2?w=500&h=300&fit=crop',
                'https://images.unsplash.com/photo-1512499617640-c74ae3a79d37?w=500&h=300&fit=crop'
            ]
        ];

        $products = [
            // Laptop & Computer (Category ID 1)
            [
                'category_id' => 1,
                'name' => 'MacBook Pro 16"',
                'description' => 'Latest MacBook Pro with M3 Max chip, perfect for professional video editing, programming, and creative work. Features stunning 16-inch Liquid Retina XDR display.',
                'specifications' => json_encode([
                    'processor' => 'Apple M3 Max 16-core CPU',
                    'memory' => '32GB Unified Memory',
                    'storage' => '1TB SSD',
                    'display' => '16.2-inch Liquid Retina XDR',
                    'graphics' => '40-core GPU',
                    'battery' => 'Up to 22 hours',
                    'connectivity' => 'Thunderbolt 4, HDMI, SDXC'
                ]),
                'price_per_day' => 150000,
                'stock' => 5
            ],
            [
                'category_id' => 1,
                'name' => 'Dell XPS 15',
                'description' => 'Premium ultraportable laptop with InfinityEdge display and powerful performance for professionals and content creators.',
                'specifications' => json_encode([
                    'processor' => 'Intel Core i7-13700H',
                    'memory' => '32GB DDR5',
                    'storage' => '1TB NVMe SSD',
                    'display' => '15.6-inch 4K OLED Touch',
                    'graphics' => 'NVIDIA RTX 4070',
                    'weight' => '2.0kg',
                    'battery' => 'Up to 13 hours'
                ]),
                'price_per_day' => 120000,
                'stock' => 4
            ],
            [
                'category_id' => 1,
                'name' => 'HP Spectre x360',
                'description' => '2-in-1 convertible laptop with premium design and versatile functionality for business and creative professionals.',
                'specifications' => json_encode([
                    'processor' => 'Intel Core i7-1355U',
                    'memory' => '16GB LPDDR5',
                    'storage' => '512GB PCIe SSD',
                    'display' => '13.5-inch 3K2K OLED Touch',
                    'graphics' => 'Intel Iris Xe',
                    'features' => '360° hinge, HP Pen support',
                    'battery' => 'Up to 17 hours'
                ]),
                'price_per_day' => 100000,
                'stock' => 6
            ],
            [
                'category_id' => 1,
                'name' => 'Lenovo ThinkPad X1',
                'description' => 'Business-grade ultrabook with legendary ThinkPad durability and enterprise security features.',
                'specifications' => json_encode([
                    'processor' => 'Intel Core i7-1365U',
                    'memory' => '32GB LPDDR5',
                    'storage' => '1TB SSD',
                    'display' => '14-inch 2.8K OLED',
                    'graphics' => 'Intel Iris Xe',
                    'security' => 'Fingerprint reader, IR camera',
                    'certification' => 'MIL-STD-810H tested'
                ]),
                'price_per_day' => 110000,
                'stock' => 7
            ],

            // Camera & Photography (Category ID 2)
            [
                'category_id' => 2,
                'name' => 'Canon EOS R5',
                'description' => 'Professional mirrorless camera with 45MP full-frame sensor and 8K video recording capabilities for photographers and videographers.',
                'specifications' => json_encode([
                    'sensor' => '45MP Full-Frame CMOS',
                    'video' => '8K RAW, 4K 120p',
                    'autofocus' => '1053 AF points with Dual Pixel CMOS AF II',
                    'display' => '3.2-inch Vari-angle Touchscreen',
                    'stabilization' => '8-stop In-Body Image Stabilization',
                    'connectivity' => 'Wi-Fi 6, Bluetooth, USB-C'
                ]),
                'price_per_day' => 200000,
                'stock' => 3
            ],
            [
                'category_id' => 2,
                'name' => 'Sony A7 IV',
                'description' => 'Versatile full-frame mirrorless camera with advanced hybrid autofocus and professional video features.',
                'specifications' => json_encode([
                    'sensor' => '33MP Full-Frame Exmor R BSI',
                    'video' => '4K 60p, 10-bit 4:2:2',
                    'autofocus' => '759 phase-detection AF points',
                    'display' => '3.0-inch Vari-angle LCD',
                    'stabilization' => '5.5-stop Image Stabilization',
                    'storage' => 'Dual CFexpress A/SD slots'
                ]),
                'price_per_day' => 180000,
                'stock' => 4
            ],
            [
                'category_id' => 2,
                'name' => 'Nikon Z6 II',
                'description' => 'Full-frame mirrorless camera with excellent low-light performance and dual processors for enhanced speed.',
                'specifications' => json_encode([
                    'sensor' => '24.5MP Full-Frame BSI CMOS',
                    'video' => '4K UHD 60p, Full HD 120p',
                    'autofocus' => '273 hybrid AF points',
                    'display' => '3.2-inch Tilting Touchscreen',
                    'stabilization' => '5-stop VR Image Stabilization',
                    'processor' => 'Dual EXPEED 6'
                ]),
                'price_per_day' => 160000,
                'stock' => 5
            ],
            [
                'category_id' => 2,
                'name' => 'Fujifilm X-T4',
                'description' => 'APS-C mirrorless camera with film simulation modes and excellent build quality for creative photography.',
                'specifications' => json_encode([
                    'sensor' => '26.1MP APS-C X-Trans CMOS 4',
                    'video' => '4K DCI/UHD 60p, Full HD 240p',
                    'autofocus' => '425 phase-detection points',
                    'display' => '3.0-inch Vari-angle Touchscreen',
                    'stabilization' => '6.5-stop In-Body IS',
                    'features' => 'Film Simulation modes, Weather sealing'
                ]),
                'price_per_day' => 140000,
                'stock' => 6
            ],

            // Audio & Sound (Category ID 3)
            [
                'category_id' => 3,
                'name' => 'Sony WH-1000XM4',
                'description' => 'Industry-leading noise-canceling wireless headphones with exceptional sound quality and 30-hour battery life.',
                'specifications' => json_encode([
                    'type' => 'Over-ear Wireless',
                    'noise_canceling' => 'Dual Noise Sensor Technology',
                    'battery' => '30 hours with ANC on',
                    'quick_charge' => '10 min = 5 hours playback',
                    'connectivity' => 'Bluetooth 5.0, NFC, 3.5mm',
                    'features' => 'Touch controls, Speak-to-Chat, LDAC'
                ]),
                'price_per_day' => 75000,
                'stock' => 8
            ],
            [
                'category_id' => 3,
                'name' => 'Bose QuietComfort 35',
                'description' => 'Premium wireless headphones with world-class noise cancellation and comfortable over-ear design.',
                'specifications' => json_encode([
                    'type' => 'Over-ear Wireless',
                    'noise_canceling' => 'Three levels of noise cancellation',
                    'battery' => '20 hours wireless, 40 hours wired',
                    'connectivity' => 'Bluetooth 4.1, NFC, 3.5mm',
                    'features' => 'Google Assistant, Alexa built-in',
                    'weight' => '310g'
                ]),
                'price_per_day' => 65000,
                'stock' => 10
            ],
            [
                'category_id' => 3,
                'name' => 'JBL Charge 5 Speaker',
                'description' => 'Powerful portable Bluetooth speaker with built-in powerbank and IP67 waterproof rating.',
                'specifications' => json_encode([
                    'power' => '40W RMS',
                    'battery' => '20 hours playtime',
                    'features' => 'Powerbank function, PartyBoost',
                    'connectivity' => 'Bluetooth 5.1, USB-C',
                    'waterproof' => 'IP67 rated',
                    'size' => '220 x 96 x 93mm'
                ]),
                'price_per_day' => 50000,
                'stock' => 12
            ],
            [
                'category_id' => 3,
                'name' => 'Audio-Technica ATH-M50x',
                'description' => 'Professional monitor headphones with exceptional clarity and deep, accurate bass response.',
                'specifications' => json_encode([
                    'type' => 'Closed-back Over-ear',
                    'drivers' => '45mm large-aperture',
                    'frequency_response' => '15Hz - 28kHz',
                    'impedance' => '38 ohms',
                    'features' => 'Swiveling earcups, Detachable cables',
                    'accessories' => '3 cables included (coiled, straight, portable)'
                ]),
                'price_per_day' => 45000,
                'stock' => 15
            ],

            // Gaming (Category ID 4)
            [
                'category_id' => 4,
                'name' => 'PlayStation 5',
                'description' => 'Latest generation gaming console with ultra-high-speed SSD, ray tracing, and haptic feedback controller.',
                'specifications' => json_encode([
                    'cpu' => 'AMD Zen 2, 8 cores @ 3.5GHz',
                    'gpu' => 'AMD RDNA 2, 10.28 TFLOPs',
                    'memory' => '16GB GDDR6',
                    'storage' => '825GB Custom NVMe SSD',
                    'features' => 'Ray tracing, 3D Audio, DualSense haptic feedback',
                    'resolution' => 'Up to 8K, 4K @ 120fps'
                ]),
                'price_per_day' => 100000,
                'stock' => 8
            ],
            [
                'category_id' => 4,
                'name' => 'Xbox Series X',
                'description' => 'Microsoft\'s most powerful console with 4K gaming, Quick Resume, and backward compatibility.',
                'specifications' => json_encode([
                    'cpu' => 'AMD Zen 2, 8 cores @ 3.8GHz',
                    'gpu' => 'AMD RDNA 2, 12 TFLOPs',
                    'memory' => '16GB GDDR6',
                    'storage' => '1TB Custom NVMe SSD',
                    'features' => 'Quick Resume, Smart Delivery, Auto HDR',
                    'backward_compatibility' => 'Xbox One, Xbox 360, Original Xbox'
                ]),
                'price_per_day' => 95000,
                'stock' => 6
            ],
            [
                'category_id' => 4,
                'name' => 'Nintendo Switch OLED',
                'description' => 'Hybrid gaming console with vibrant 7-inch OLED screen for both portable and docked gaming.',
                'specifications' => json_encode([
                    'display' => '7-inch OLED touchscreen',
                    'resolution' => '1280 x 720 handheld, 1920 x 1080 docked',
                    'storage' => '64GB internal + microSDXC support',
                    'battery' => '4.5-9 hours (model dependent)',
                    'features' => 'Detachable Joy-Con, HD Rumble',
                    'modes' => 'TV mode, Tabletop mode, Handheld mode'
                ]),
                'price_per_day' => 80000,
                'stock' => 10
            ],
            [
                'category_id' => 4,
                'name' => 'Steam Deck',
                'description' => 'Portable gaming PC that runs your Steam library with desktop-class performance on the go.',
                'specifications' => json_encode([
                    'processor' => 'AMD APU (Zen 2 + RDNA 2)',
                    'memory' => '16GB LPDDR5 RAM',
                    'storage' => '512GB NVMe SSD',
                    'display' => '7-inch LCD touchscreen 1280x800',
                    'controls' => 'Gamepad controls + trackpads',
                    'os' => 'SteamOS 3.0 (Arch Linux based)'
                ]),
                'price_per_day' => 85000,
                'stock' => 5
            ],

            // Smartphone & Tablet (Category ID 5)
            [
                'category_id' => 5,
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest flagship iPhone with titanium design, advanced camera system, and A17 Pro chip.',
                'specifications' => json_encode([
                    'processor' => 'A17 Pro chip (3nm)',
                    'display' => '6.1-inch Super Retina XDR ProMotion',
                    'camera' => '48MP Main + 12MP Ultra Wide + 12MP Telephoto',
                    'storage' => '128GB, 256GB, 512GB, 1TB',
                    'features' => 'Action Button, USB-C, Always-On display',
                    'material' => 'Grade 5 Titanium frame'
                ]),
                'price_per_day' => 120000,
                'stock' => 6
            ],
            [
                'category_id' => 5,
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Premium Android flagship with S Pen, advanced AI features, and professional-grade cameras.',
                'specifications' => json_encode([
                    'processor' => 'Snapdragon 8 Gen 3 for Galaxy',
                    'display' => '6.8-inch Dynamic AMOLED 2X 120Hz',
                    'camera' => '200MP Main + 50MP Periscope + 12MP Ultra Wide',
                    'memory' => '12GB RAM',
                    'storage' => '256GB, 512GB, 1TB',
                    'features' => 'S Pen included, Galaxy AI, Titanium frame'
                ]),
                'price_per_day' => 110000,
                'stock' => 8
            ],
            [
                'category_id' => 5,
                'name' => 'iPad Pro 12.9"',
                'description' => 'Professional tablet with M2 chip, Liquid Retina XDR display, and Apple Pencil support.',
                'specifications' => json_encode([
                    'processor' => 'Apple M2 chip',
                    'display' => '12.9-inch Liquid Retina XDR mini-LED',
                    'storage' => '128GB, 256GB, 512GB, 1TB, 2TB',
                    'camera' => '12MP Wide + 10MP Ultra Wide + LiDAR',
                    'features' => 'Apple Pencil 2 compatible, Face ID',
                    'connectivity' => 'Wi-Fi 6E + 5G (cellular models)'
                ]),
                'price_per_day' => 90000,
                'stock' => 7
            ],
            [
                'category_id' => 5,
                'name' => 'Samsung Galaxy Tab S9',
                'description' => 'Premium Android tablet with S Pen, AMOLED display, and desktop-class productivity features.',
                'specifications' => json_encode([
                    'processor' => 'Snapdragon 8 Gen 2 for Galaxy',
                    'display' => '11-inch Dynamic AMOLED 2X 120Hz',
                    'memory' => '8GB/12GB RAM',
                    'storage' => '128GB/256GB + microSD',
                    'features' => 'S Pen included, DeX mode, IP68 rated',
                    'battery' => '8,000mAh with 45W fast charging'
                ]),
                'price_per_day' => 75000,
                'stock' => 9
            ]
        ];

        foreach ($products as $index => $productData) {
            // Smart image assignment based on category
            $categoryId = $productData['category_id'];
            $images = $categoryImages[$categoryId];
            $imageIndex = $index % count($images); // Cycle through images for each category
            
            Product::create([
                'category_id' => $productData['category_id'],
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'specifications' => $productData['specifications'],
                'price_per_day' => $productData['price_per_day'],
                'stock' => $productData['stock'],
                'image' => $images[$imageIndex], // Assign Unsplash image
                'status' => 'active'
            ]);
        }

        $this->command->info('Created ' . count($products) . ' products with high-quality Unsplash images');
    }
}
