<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Property\Models\Property;
use App\Domain\User\Models\User;
use App\Domain\Property\Enums\PropertyType;
use App\Domain\Property\Enums\PropertyStatus;
use Carbon\Carbon;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả users để làm owner
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->error('No users found. Please run UserSeeder first.');
            return;
        }

        // Dữ liệu bất động sản thực tế cho thị trường Việt Nam
        $properties = [
            // Căn hộ chung cư
            [
                'title' => 'Căn hộ cao cấp Vinhomes Central Park',
                'description' => 'Căn hộ 2 phòng ngủ view sông Sài Gòn, nội thất cao cấp, tiện ích đầy đủ. Gần trung tâm thành phố, an ninh 24/7.',
                'type' => PropertyType::APARTMENT->value,
                'price' => 5200000000, // 5.2 tỷ VND
                'area' => 75,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'address' => 'Vinhomes Central Park, Bình Thạnh, TP. Hồ Chí Minh',
                'latitude' => 10.7879,
                'longitude' => 106.7205,
                'features' => ['Hồ bơi', 'Gym', 'Sân chơi trẻ em', 'Siêu thị', 'Bảo vệ 24/7', 'View sông'],
                'status' => PropertyStatus::FOR_SALE->value,
            ],
            [
                'title' => 'Chung cư Times City Hà Nội',
                'description' => 'Căn hộ 3 phòng ngủ tại Times City, tầng cao view đẹp, đầy đủ nội thất. Khu vực sầm uất, giao thông thuận lợi.',
                'type' => PropertyType::APARTMENT->value,
                'price' => 4800000000, // 4.8 tỷ VND
                'area' => 95,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'address' => 'Times City, Hai Bà Trưng, Hà Nội',
                'latitude' => 20.9915,
                'longitude' => 105.8676,
                'features' => ['AEON Mall', 'Công viên nước', 'Trường học', 'Bệnh viện', 'Khu vui chơi'],
                'status' => PropertyStatus::FOR_SALE->value,
            ],
            [
                'title' => 'Căn hộ Masteri Thảo Điền',
                'description' => 'Studio sang trọng tại Masteri Thảo Điền, full nội thất, view landmark 81. Phù hợp cho người trẻ và gia đình nhỏ.',
                'type' => PropertyType::APARTMENT->value,
                'price' => 35000000, // 35 triệu/tháng
                'area' => 45,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'address' => 'Masteri Thảo Điền, Quận 2, TP. Hồ Chí Minh',
                'latitude' => 10.8067,
                'longitude' => 106.7441,
                'features' => ['Sky bar', 'Hồ bơi vô cực', 'Gym', 'Yoga', 'Co-working space'],
                'status' => PropertyStatus::FOR_RENT->value,
            ],

            // Nhà riêng
            [
                'title' => 'Nhà phố 4 tầng Cầu Giấy',
                'description' => 'Nhà phố 4 tầng mặt tiền 4m, thiết kế hiện đại. Gần trường đại học, bệnh viện, trung tâm thương mại.',
                'type' => PropertyType::HOUSE->value,
                'price' => 8500000000, // 8.5 tỷ VND
                'area' => 120,
                'bedrooms' => 4,
                'bathrooms' => 4,
                'address' => 'Phố Trung Kính, Cầu Giấy, Hà Nội',
                'latitude' => 21.0133,
                'longitude' => 105.7939,
                'features' => ['Garage ô tô', 'Sân thượng', 'Thang máy', 'Hầm rượu', 'Khu vườn nhỏ'],
                'status' => PropertyStatus::FOR_SALE->value,
            ],
            [
                'title' => 'Nhà mặt tiền Nguyễn Huệ Q1',
                'description' => 'Nhà mặt tiền đường Nguyễn Huệ, vị trí đắc địa cho kinh doanh. 5 tầng, thang máy, điều hòa central.',
                'type' => PropertyType::HOUSE->value,
                'price' => 45000000000, // 45 tỷ VND
                'area' => 200,
                'bedrooms' => 6,
                'bathrooms' => 6,
                'address' => 'Đường Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh',
                'latitude' => 10.7744,
                'longitude' => 106.7010,
                'features' => ['Vị trí vàng', 'Mặt tiền rộng', 'Thang máy', 'Hầm để xe', 'Sân thượng'],
                'status' => PropertyStatus::FOR_SALE->value,
            ],
            [
                'title' => 'Nhà ngõ Láng Hạ 3 tầng',
                'description' => 'Nhà trong ngõ ô tô vào được, 3 tầng kiên cố, sân để xe. Gần công viên Thống Nhất, trường học quốc tế.',
                'type' => PropertyType::HOUSE->value,
                'price' => 25000000, // 25 triệu/tháng
                'area' => 85,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'address' => 'Ngõ Láng Hạ, Đống Đa, Hà Nội',
                'latitude' => 21.0167,
                'longitude' => 105.8116,
                'features' => ['Sân để xe', 'Gần trường học', 'An ninh tốt', 'Khu dân cư văn minh'],
                'status' => PropertyStatus::FOR_RENT->value,
            ],

            // Biệt thự
            [
                'title' => 'Biệt thự Vinhomes Riverside',
                'description' => 'Biệt thự đơn lập 300m² tại Vinhomes Riverside, sân vườn rộng, hồ bơi riêng. Thiết kế tân cổ điển sang trọng.',
                'type' => PropertyType::VILLA->value,
                'price' => 18000000000, // 18 tỷ VND
                'area' => 300,
                'bedrooms' => 5,
                'bathrooms' => 6,
                'address' => 'Vinhomes Riverside, Long Biên, Hà Nội',
                'latitude' => 21.0538,
                'longitude' => 105.9088,
                'features' => ['Hồ bơi riêng', 'Sân golf mini', 'BBQ area', 'Garage 2 xe', 'Khu vườn lớn', 'Hầm rượu'],
                'status' => PropertyStatus::FOR_SALE->value,
            ],
            [
                'title' => 'Biệt thự Phú Mỹ Hưng Q7',
                'description' => 'Biệt thự 2 mặt tiền tại Phú Mỹ Hưng, hoàn thiện cao cấp, hồ bơi, sân vườn. Khu an ninh 24/7.',
                'type' => PropertyType::VILLA->value,
                'price' => 120000000, // 120 triệu/tháng
                'area' => 400,
                'bedrooms' => 6,
                'bathrooms' => 7,
                'address' => 'Phú Mỹ Hưng, Quận 7, TP. Hồ Chí Minh',
                'latitude' => 10.7212,
                'longitude' => 106.6999,
                'features' => ['Hồ bơi', 'Sân tennis', 'Phòng karaoke', 'Thang máy', 'Sân vườn BBQ'],
                'status' => PropertyStatus::FOR_RENT->value,
            ],

            // Đất nền
            [
                'title' => 'Đất nền KDC Vạn Phúc Riverside',
                'description' => 'Lô đất góc 2 mặt tiền tại khu đô thị Vạn Phúc Riverside, đã có sổ đỏ. Hạ tầng hoàn thiện, pháp lý rõ ràng.',
                'type' => PropertyType::LAND->value,
                'price' => 6500000000, // 6.5 tỷ VND
                'area' => 150,
                'bedrooms' => 0,
                'bathrooms' => 0,
                'address' => 'Vạn Phúc Riverside, Thủ Đức, TP. Hồ Chí Minh',
                'latitude' => 10.8721,
                'longitude' => 106.7516,
                'features' => ['Lô góc', 'Sổ đỏ sẵn', 'Hạ tầng đầy đủ', 'Gần sông', 'Quy hoạch 1/500'],
                'status' => PropertyStatus::FOR_SALE->value,
            ],
            [
                'title' => 'Đất thổ cư Đông Anh Hà Nội',
                'description' => 'Đất thổ cư 100% tại Đông Anh, mặt tiền đường 6m, điện nước đầy đủ. Phù hợp xây nhà ở hoặc đầu tư.',
                'type' => PropertyType::LAND->value,
                'price' => 2800000000, // 2.8 tỷ VND
                'area' => 120,
                'bedrooms' => 0,
                'bathrooms' => 0,
                'address' => 'Đông Anh, Hà Nội',
                'latitude' => 21.1373,
                'longitude' => 105.8227,
                'features' => ['Thổ cư 100%', 'Mặt tiền rộng', 'Đường xe hơi', 'Điện 3 pha', 'Gần trường học'],
                'status' => PropertyStatus::FOR_SALE->value,
            ],
        ];

        // Tạo dữ liệu sample
        foreach ($properties as $index => $propertyData) {
            // Random owner
            $owner = $users->random();
            
            // Random creation date trong 6 tháng qua
            $createdAt = Carbon::now()->subDays(rand(1, 180));
            $updatedAt = $createdAt->copy()->addDays(rand(0, 30));
            
            // Random images URLs (Unsplash real estate images)
            $images = $this->getRandomImages($propertyData['type']);
            
            Property::create([
                'title' => $propertyData['title'],
                'description' => $propertyData['description'],
                'price' => $propertyData['price'],
                'type' => $propertyData['type'],
                'area' => $propertyData['area'],
                'bedrooms' => $propertyData['bedrooms'],
                'bathrooms' => $propertyData['bathrooms'],
                'address' => $propertyData['address'],
                'latitude' => $propertyData['latitude'],
                'longitude' => $propertyData['longitude'],
                'features' => $propertyData['features'],
                'images' => $images,
                'status' => $propertyData['status'],
                'owner_id' => $owner->id,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }

        // Tạo thêm random properties
        $this->createRandomProperties($users, 40);
    }

    /**
     * Create additional random properties
     */
    private function createRandomProperties($users, int $count): void
    {
        $propertyTypes = [
            PropertyType::APARTMENT->value,
            PropertyType::HOUSE->value,
            PropertyType::VILLA->value,
            PropertyType::LAND->value,
        ];

        $statuses = [
            PropertyStatus::FOR_SALE->value,
            PropertyStatus::FOR_RENT->value,
            PropertyStatus::SOLD->value,
            PropertyStatus::RENTED->value,
        ];

        $locations = [
            ['name' => 'Quận 1, TP. Hồ Chí Minh', 'lat' => 10.7756, 'lng' => 106.7019],
            ['name' => 'Quận 2, TP. Hồ Chí Minh', 'lat' => 10.8067, 'lng' => 106.7441],
            ['name' => 'Quận 3, TP. Hồ Chí Minh', 'lat' => 10.7860, 'lng' => 106.6917],
            ['name' => 'Quận 7, TP. Hồ Chí Minh', 'lat' => 10.7373, 'lng' => 106.7017],
            ['name' => 'Bình Thạnh, TP. Hồ Chí Minh', 'lat' => 10.8050, 'lng' => 106.7130],
            ['name' => 'Ba Đình, Hà Nội', 'lat' => 21.0341, 'lng' => 105.8372],
            ['name' => 'Hoàn Kiếm, Hà Nội', 'lat' => 21.0285, 'lng' => 105.8542],
            ['name' => 'Cầu Giấy, Hà Nội', 'lat' => 21.0313, 'lng' => 105.7860],
            ['name' => 'Thanh Xuân, Hà Nội', 'lat' => 20.9885, 'lng' => 105.8056],
            ['name' => 'Hải Châu, Đà Nẵng', 'lat' => 16.0471, 'lng' => 108.2068],
        ];

        for ($i = 0; $i < $count; $i++) {
            $type = $propertyTypes[array_rand($propertyTypes)];
            $status = $statuses[array_rand($statuses)];
            $location = $locations[array_rand($locations)];
            
            $propertyData = $this->generateRandomPropertyData($type, $status, $location);
            
            $createdAt = Carbon::now()->subDays(rand(1, 365));
            $updatedAt = $createdAt->copy()->addDays(rand(0, 60));
            
            if ($status === PropertyStatus::SOLD->value || $status === PropertyStatus::RENTED->value) {
                $updatedAt = $createdAt->copy()->addDays(rand(30, 180));
            }

            Property::create([
                'title' => $propertyData['title'],
                'description' => $propertyData['description'],
                'price' => $propertyData['price'],
                'type' => $type,
                'area' => $propertyData['area'],
                'bedrooms' => $propertyData['bedrooms'],
                'bathrooms' => $propertyData['bathrooms'],
                'address' => $location['name'],
                'latitude' => $location['lat'] + (rand(-100, 100) / 10000),
                'longitude' => $location['lng'] + (rand(-100, 100) / 10000),
                'features' => $propertyData['features'],
                'images' => $this->getRandomImages($type),
                'status' => $status,
                'owner_id' => $users->random()->id,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }
    }

    /**
     * Generate random property data based on type
     */
    private function generateRandomPropertyData(string $type, string $status, array $location): array
    {
        switch ($type) {
            case PropertyType::APARTMENT->value:
                return [
                    'title' => $this->getRandomApartmentTitle($location['name']),
                    'description' => 'Căn hộ cao cấp với thiết kế hiện đại, tiện ích đầy đủ. Vị trí thuận lợi, giao thông dễ dàng.',
                    'price' => $status === PropertyStatus::FOR_RENT->value ? rand(15, 80) * 1000000 : rand(2000, 8000) * 1000000,
                    'area' => rand(45, 120),
                    'bedrooms' => rand(1, 3),
                    'bathrooms' => rand(1, 3),
                    'features' => ['Hồ bơi', 'Gym', 'Bảo vệ 24/7', 'Thang máy', 'Parking'],
                ];

            case PropertyType::HOUSE->value:
                return [
                    'title' => $this->getRandomHouseTitle($location['name']),
                    'description' => 'Nhà phố thiết kế đẹp, không gian thoáng mát. Phù hợp cho gia đình hoặc kinh doanh.',
                    'price' => $status === PropertyStatus::FOR_RENT->value ? rand(20, 150) * 1000000 : rand(3000, 15000) * 1000000,
                    'area' => rand(80, 200),
                    'bedrooms' => rand(2, 5),
                    'bathrooms' => rand(2, 5),
                    'features' => ['Sân để xe', 'Sân thượng', 'Khu vực BBQ', 'An ninh tốt'],
                ];

            case PropertyType::VILLA->value:
                return [
                    'title' => $this->getRandomVillaTitle($location['name']),
                    'description' => 'Biệt thự sang trọng với sân vườn rộng rãi, thiết kế đẳng cấp. Không gian sống lý tưởng.',
                    'price' => $status === PropertyStatus::FOR_RENT->value ? rand(80, 300) * 1000000 : rand(10000, 50000) * 1000000,
                    'area' => rand(200, 500),
                    'bedrooms' => rand(4, 8),
                    'bathrooms' => rand(4, 10),
                    'features' => ['Hồ bơi riêng', 'Sân vườn', 'Garage', 'BBQ area', 'Phòng gym'],
                ];

            case PropertyType::LAND->value:
                return [
                    'title' => $this->getRandomLandTitle($location['name']),
                    'description' => 'Đất nền vị trí đẹp, pháp lý rõ ràng, hạ tầng đầy đủ. Tiềm năng đầu tư cao.',
                    'price' => rand(1500, 10000) * 1000000,
                    'area' => rand(80, 300),
                    'bedrooms' => 0,
                    'bathrooms' => 0,
                    'features' => ['Sổ đỏ', 'Mặt tiền', 'Điện nước', 'Đường xe hơi'],
                ];

            default:
                return [
                    'title' => 'Bất động sản tại ' . $location['name'],
                    'description' => 'Mô tả bất động sản.',
                    'price' => rand(1000, 5000) * 1000000,
                    'area' => rand(50, 150),
                    'bedrooms' => rand(1, 3),
                    'bathrooms' => rand(1, 2),
                    'features' => ['Tiện ích cơ bản'],
                ];
        }
    }

    private function getRandomImages(string $type): array
    {
        $imageIds = [
            PropertyType::APARTMENT->value => [1715, 2157, 2189, 2190, 2196],
            PropertyType::HOUSE->value => [106, 186, 164, 209, 259],
            PropertyType::VILLA->value => [280, 358, 430, 1546, 1613],
            PropertyType::LAND->value => [96, 97, 158, 441, 500],
        ];

        $ids = $imageIds[$type] ?? $imageIds[PropertyType::APARTMENT->value];
        shuffle($ids);
        
        return array_map(function($id) {
            return "https://images.unsplash.com/photo-{$id}?w=800&h=600&fit=crop";
        }, array_slice($ids, 0, rand(3, 5)));
    }

    private function getRandomApartmentTitle(string $location): string
    {
        $prefixes = ['Căn hộ cao cấp', 'Chung cư', 'Apartment', 'Căn hộ dịch vụ'];
        $names = ['Vinhomes', 'Masteri', 'The Manor', 'Saigon Pearl', 'Landmark', 'Times City'];
        
        return $prefixes[array_rand($prefixes)] . ' ' . $names[array_rand($names)] . ' ' . explode(',', $location)[0];
    }

    private function getRandomHouseTitle(string $location): string
    {
        $types = ['Nhà phố', 'Nhà mặt tiền', 'Townhouse', 'Nhà riêng'];
        return $types[array_rand($types)] . ' tại ' . explode(',', $location)[0];
    }

    private function getRandomVillaTitle(string $location): string
    {
        $types = ['Biệt thự', 'Villa sang trọng', 'Biệt thự đơn lập', 'Villa cao cấp'];
        return $types[array_rand($types)] . ' ' . explode(',', $location)[0];
    }

    private function getRandomLandTitle(string $location): string
    {
        $types = ['Đất nền', 'Lô đất', 'Đất thổ cư', 'Đất dự án'];
        return $types[array_rand($types)] . ' ' . explode(',', $location)[0];
    }
}