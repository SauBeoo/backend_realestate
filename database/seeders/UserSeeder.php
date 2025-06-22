<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo admin user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@giatrithuoc.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '0901234567',
            'address' => 'Quận 1, TP. Hồ Chí Minh',
            'user_type' => 'admin',
            'status' => 'active',
            'is_verified' => true,
            'created_at' => now()->subDays(365),
            'updated_at' => now()->subDays(1),
        ]);

        // Tạo dữ liệu fake cho users
        $vietnameseNames = [
            ['Nguyễn', 'Văn Minh'], ['Trần', 'Thị Hoa'], ['Lê', 'Hoàng Nam'], ['Phạm', 'Thị Lan'],
            ['Hoàng', 'Văn Đức'], ['Vũ', 'Thị Mai'], ['Đặng', 'Quốc Anh'], ['Bùi', 'Thị Linh'],
            ['Ngô', 'Văn Tùng'], ['Đỗ', 'Thị Nga'], ['Lý', 'Hoàng Khôi'], ['Tô', 'Thị Xuân'],
            ['Đinh', 'Văn Lâm'], ['Cao', 'Thị Thảo'], ['Dương', 'Quang Hải'], ['Phan', 'Thị Kim'],
            ['Lương', 'Văn Phú'], ['Mạc', 'Thị Hồng'], ['Võ', 'Minh Tuấn'], ['Lê', 'Thị Yến'],
            ['Trương', 'Văn Hùng'], ['Nguyễn', 'Thị Oanh'], ['Lại', 'Hoàng Dũng'], ['Thái', 'Thị Bích'],
            ['Huỳnh', 'Văn Long'], ['Đinh', 'Thị Thu'], ['Phan', 'Quang Minh'], ['Đào', 'Thị Lan'],
            ['Vương', 'Văn Khoa'], ['Lê', 'Thị Phương'], ['Trần', 'Minh Đức'], ['Nguyễn', 'Thị Trang'],
            ['Bùi', 'Văn Sơn'], ['Hoàng', 'Thị Thủy'], ['Đỗ', 'Quang Vinh'], ['Phạm', 'Thị Hằng'],
            ['Lý', 'Văn Kiệt'], ['Cao', 'Thị Hải'], ['Ngô', 'Hoàng Bảo'], ['Vũ', 'Thị Loan'],
            ['Đặng', 'Văn Thành'], ['Mạc', 'Thị Dung'], ['Lương', 'Quang Huy'], ['Đinh', 'Thị Nhung'],
            ['Tô', 'Văn Tài'], ['Thái', 'Thị Liên'], ['Võ', 'Hoàng Phúc'], ['Phan', 'Thị Diệu'],
            ['Trương', 'Văn Đại'], ['Lại', 'Thị Nga'], ['Huỳnh', 'Minh Quân'], ['Đào', 'Thị Sen']
        ];

        $phoneNumbers = [
            '0901234567', '0912345678', '0923456789', '0934567890', '0945678901',
            '0956789012', '0967890123', '0978901234', '0989012345', '0390123456',
            '0381234567', '0372345678', '0363456789', '0354567890', '0345678901',
            '0336789012', '0327890123', '0318901234', '0309012345', '0705123456',
            '0706234567', '0707345678', '0708456789', '0709567890', '0561234567',
            '0562345678', '0563456789', '0564567890', '0565678901', '0566789012'
        ];

        $addresses = [
            'Quận 1, TP. Hồ Chí Minh',
            'Quận 2, TP. Hồ Chí Minh',
            'Quận 3, TP. Hồ Chí Minh',
            'Quận 4, TP. Hồ Chí Minh',
            'Quận 5, TP. Hồ Chí Minh',
            'Quận 7, TP. Hồ Chí Minh',
            'Quận Bình Thạnh, TP. Hồ Chí Minh',
            'Quận Phú Nhuận, TP. Hồ Chí Minh',
            'Quận Tân Bình, TP. Hồ Chí Minh',
            'Quận Gò Vấp, TP. Hồ Chí Minh',
            'Ba Đình, Hà Nội',
            'Hoàn Kiếm, Hà Nội',
            'Hai Bà Trưng, Hà Nội',
            'Đống Đa, Hà Nội',
            'Tây Hồ, Hà Nội',
            'Cầu Giấy, Hà Nội',
            'Thanh Xuân, Hà Nội',
            'Hoàng Mai, Hà Nội',
            'Long Biên, Hà Nội',
            'Nam Từ Liêm, Hà Nội',
            'Hải Châu, Đà Nẵng',
            'Thanh Khê, Đà Nẵng',
            'Sơn Trà, Đà Nẵng',
            'Ngũ Hành Sơn, Đà Nẵng',
            'Liên Chiểu, Đà Nẵng'
        ];

        $userTypes = ['buyer', 'seller', 'agent'];

        // Tạo 50 users với dữ liệu Vietnamese realistic
        for ($i = 0; $i < 50; $i++) {
            $createdAt = Carbon::now()->subDays(rand(1, 365));
            $updatedAt = $createdAt->copy()->addDays(rand(0, 30));
            
            $nameData = $vietnameseNames[array_rand($vietnameseNames)];
            $firstName = $nameData[0];
            $lastName = $nameData[1];
            $fullName = $firstName . ' ' . $lastName;
            $email = $this->generateVietnameseEmail($fullName, $i);
            
            User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'email_verified_at' => rand(0, 1) ? $createdAt->addDays(rand(0, 7)) : null,
                'password' => Hash::make('password123'),
                'phone' => $phoneNumbers[array_rand($phoneNumbers)],
                'address' => $addresses[array_rand($addresses)],
                'user_type' => $userTypes[array_rand($userTypes)],
                'status' => 'active',
                'is_verified' => rand(0, 1),
                'country' => 'Vietnam',
                'receive_notifications' => rand(0, 1),
                'receive_marketing' => rand(0, 1) == 1,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }
    }

    /**
     * Generate Vietnamese-style email from name
     */
    private function generateVietnameseEmail(string $name, int $index): string
    {
        // Remove Vietnamese accents and convert to lowercase
        $email = $this->removeVietnameseAccents($name);
        $email = strtolower(str_replace(' ', '.', $email));
        
        $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'fpt.vn', 'vnn.vn'];
        $domain = $domains[array_rand($domains)];
        
        // Add number to avoid duplicates
        $suffix = $index > 0 ? rand(1, 999) : '';
        
        return $email . $suffix . '@' . $domain;
    }

    /**
     * Remove Vietnamese accents from string
     */
    private function removeVietnameseAccents(string $str): string
    {
        $accents = [
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ'
        ];
        
        $replacements = [
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd'
        ];
        
        return str_replace($accents, $replacements, $str);
    }
}