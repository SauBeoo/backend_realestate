# Hướng dẫn Seeder - Giá Trị Thực Platform

## Mô tả

Hệ thống seeder đã được tạo để sinh dữ liệu mẫu cho nền tảng bất động sản Giá Trị Thực với dữ liệu thực tế phù hợp với thị trường Việt Nam.

## Dữ liệu được tạo

### 1. UserSeeder
- **51 users** bao gồm:
  - 1 admin user (`admin@giatrithuoc.com` / `password123`)
  - 50 users với tên Tiếng Việt thực tế
  - Email, số điện thoại, địa chỉ Việt Nam
  - Ngày tạo ngẫu nhiên trong 1 năm qua

**Tên mẫu:**
- Nguyễn Văn Minh, Trần Thị Hoa, Lê Hoàng Nam, Phạm Thị Lan...
- Email: nguyen.van.minh123@gmail.com, tran.thi.hoa456@yahoo.com...
- Địa chỉ: Quận 1 TP.HCM, Ba Đình Hà Nội, Hải Châu Đà Nẵng...

### 2. PropertySeeder
- **50 properties** bao gồm:
  - 10 properties mẫu với dữ liệu thực tế chi tiết
  - 40 properties ngẫu nhiên

**Loại bất động sản:**
- **Căn hộ (Apartment)**: Vinhomes Central Park, Times City, Masteri Thảo Điền...
- **Nhà riêng (House)**: Nhà phố Cầu Giấy, mặt tiền Nguyễn Huệ...
- **Biệt thự (Villa)**: Vinhomes Riverside, Phú Mỹ Hưng...
- **Đất nền (Land)**: KDC Vạn Phúc Riverside, Đông Anh Hà Nội...

**Trạng thái:**
- Đang bán (for_sale)
- Cho thuê (for_rent)  
- Đã bán (sold)
- Đã cho thuê (rented)

**Giá cả thực tế:**
- Căn hộ: 2-8 tỷ VND (bán), 15-80 triệu/tháng (thuê)
- Nhà riêng: 3-15 tỷ VND (bán), 20-150 triệu/tháng (thuê)
- Biệt thự: 10-50 tỷ VND (bán), 80-300 triệu/tháng (thuê)
- Đất nền: 1.5-10 tỷ VND

## Cách chạy Seeder

### Lệnh cơ bản
```bash
# Chạy tất cả seeders
php artisan db:seed

# Hoặc chạy từng seeder riêng lẻ
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=PropertySeeder
```

### Reset và seed lại
```bash
# Xóa tất cả dữ liệu và chạy lại migration + seeder
php artisan migrate:fresh --seed

# Chỉ chạy lại seeder (giữ nguyên cấu trúc DB)
php artisan db:seed --force
```

### Môi trường Production
```bash
# Chạy seeder trong production (cần --force)
php artisan db:seed --force --env=production
```

## Tính năng đặc biệt

### 1. Dữ liệu Vietnamese
- Tên người Việt thực tế với đầy đủ họ, đệm, tên
- Địa chỉ các quận/huyện chính tại TP.HCM, Hà Nội, Đà Nẵng
- Số điện thoại theo định dạng Việt Nam (090x, 091x, 038x...)
- Email được tạo từ tên tiếng Việt không dấu

### 2. Dữ liệu BDS thực tế
- Tên dự án thực tế: Vinhomes, Masteri, Times City...
- Giá theo thị trường hiện tại
- Địa chỉ cụ thể với tọa độ GPS
- Mô tả chi tiết bằng tiếng Việt
- Tiện ích phù hợp với từng loại BDS

### 3. Ảnh từ Unsplash
- Ảnh BDS chất lượng cao từ Unsplash
- Phân loại theo từng loại property
- 3-5 ảnh mỗi BDS

### 4. Timestamps thực tế
- Ngày tạo user: 1-365 ngày trước
- Ngày tạo property: 1-180 ngày trước  
- Ngày cập nhật: sau ngày tạo 0-60 ngày
- Properties đã bán/thuê: cập nhật sau 30-180 ngày

## Kiểm tra dữ liệu

### Sau khi chạy seeder, kiểm tra:

```sql
-- Kiểm tra users
SELECT COUNT(*) as total_users FROM users;
SELECT name, email, created_at FROM users LIMIT 5;

-- Kiểm tra properties  
SELECT COUNT(*) as total_properties FROM properties;
SELECT title, type, status, price FROM properties LIMIT 10;

-- Thống kê theo loại
SELECT type, COUNT(*) as count FROM properties GROUP BY type;
SELECT status, COUNT(*) as count FROM properties GROUP BY status;

-- Kiểm tra owner relationships
SELECT u.name, COUNT(p.id) as property_count 
FROM users u 
LEFT JOIN properties p ON u.id = p.owner_id 
GROUP BY u.id, u.name 
HAVING property_count > 0 
ORDER BY property_count DESC;
```

## Tùy chỉnh Seeder

### Thay đổi số lượng dữ liệu:

**UserSeeder.php:**
```php
// Dòng 51: Thay đổi số lượng users
for ($i = 0; $i < 100; $i++) { // Tăng từ 50 lên 100
```

**PropertySeeder.php:**
```php  
// Dòng 167: Thay đổi số lượng properties random
$this->createRandomProperties($users, 80); // Tăng từ 40 lên 80
```

### Thêm loại BDS mới:
```php
// Trong PropertySeeder, thêm vào $propertyTypes array
$propertyTypes = [
    PropertyType::APARTMENT->value,
    PropertyType::HOUSE->value, 
    PropertyType::VILLA->value,
    PropertyType::LAND->value,
    'warehouse', // Thêm kho xưởng
    'office',    // Thêm văn phòng
];
```

### Thêm địa điểm mới:
```php
// Trong $locations array
$locations = [
    // ... existing locations
    ['name' => 'Quận 10, TP. Hồ Chí Minh', 'lat' => 10.7598, 'lng' => 106.6667],
    ['name' => 'Bắc Ninh', 'lat' => 21.1864, 'lng' => 106.0740],
];
```

## Troubleshooting

### Lỗi thường gặp:

1. **Foreign key constraint fails**
```bash
# Đảm bảo chạy UserSeeder trước PropertySeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=PropertySeeder
```

2. **Class not found**
```bash
# Chạy composer autoload
composer dump-autoload
```

3. **Memory limit**
```bash
# Tăng memory limit nếu tạo nhiều dữ liệu
php -d memory_limit=512M artisan db:seed
```

4. **Duplicate email**
```bash
# Xóa dữ liệu cũ trước khi seed
php artisan migrate:fresh --seed
```

## Sử dụng trong Development

### Test dashboard với dữ liệu thực:
1. Chạy seeder để có dữ liệu
2. Truy cập `/admin/dashboard` 
3. Kiểm tra các biểu đồ, thống kê
4. Test các chức năng filter, search
5. Kiểm tra export data

### Demo cho client:
- Dữ liệu Vietnamese thực tế 
- Giá cả phù hợp thị trường
- Hình ảnh đẹp, chuyên nghiệp
- Đủ đa dạng để demo các tính năng

---

**Lưu ý:** Seeder này được thiết kế đặc biệt cho thị trường bất động sản Việt Nam với dữ liệu thực tế và phù hợp văn hóa địa phương.