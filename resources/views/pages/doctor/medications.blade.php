<?php
$medicineTypes = [
    ["name" => "Giảm đau", "description" => "Nhóm thuốc hỗ trợ giảm đau, hạ sốt"],
    ["name" => "Kháng sinh", "description" => "Nhóm thuốc điều trị nhiễm khuẩn"],
    ["name" => "Vitamin", "description" => "Nhóm thuốc bổ sung vitamin và khoáng chất"],
    ["name" => "Tim mạch", "description" => "Nhóm thuốc hỗ trợ điều trị tim mạch"],
    ["name" => "Tiêu hóa", "description" => "Nhóm thuốc hỗ trợ điều trị tiêu hóa"],
];

$medicines = [
    [
        "name" => "Paracetamol 500mg",
        "type" => "Giảm đau",
        "ingredient" => "Acetaminophen",
        "dosage" => "500 MG / VIÊN",
        "image" => "https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=300&q=80"
    ],
    [
        "name" => "Amoxicillin 500mg",
        "type" => "Kháng sinh",
        "ingredient" => "Amoxicillin",
        "dosage" => "500 MG / VIÊN",
        "image" => "https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?auto=format&fit=crop&w=300&q=80"
    ],
    [
        "name" => "Vitamin C",
        "type" => "Vitamin",
        "ingredient" => "Ascorbic Acid",
        "dosage" => "1000 MG / VIÊN",
        "image" => "https://images.unsplash.com/photo-1587854692152-cbe660dbde88?auto=format&fit=crop&w=300&q=80"
    ],
    [
        "name" => "Omeprazole 20mg",
        "type" => "Tiêu hóa",
        "ingredient" => "Omeprazole",
        "dosage" => "20 MG / VIÊN",
        "image" => "https://images.unsplash.com/photo-1471864190281-a93a3070b6de?auto=format&fit=crop&w=300&q=80"
    ],
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý loại thuốc và thuốc</title>
    <link rel="stylesheet" href="{{ asset('css/medications.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">✚</div>
            <div>
                <h1>Clinical Sanctuary</h1>
                <p>Pharmacy Management</p>
            </div>
        </div>

        <nav class="menu">
            <a href="#">Dashboard</a>
            <a href="#" class="active">Loại thuốc</a>
            <a href="#">Thuốc</a>
            <a href="#">Đơn thuốc</a>
            <a href="#">Báo cáo</a>
            <a href="#">Cài đặt</a>
        </nav>
    </aside>

    <header class="topbar">
        <div class="search-box">
            <input type="text" id="globalSearch" placeholder="Tìm kiếm thuốc...">
        </div>

        <div class="topbar-right">
            <button class="icon-btn">🔔</button>
            <button class="icon-btn">❔</button>
            <div class="user-box">
                <div>
                    <strong>Dr. Alexander</strong>
                    <span>Pharmacy Admin</span>
                </div>
                <div class="avatar">DA</div>
            </div>
        </div>
    </header>

    <main class="main">
        <section class="page-header">
            <div>
                <h2>Hệ thống Quản trị Dược phẩm</h2>
                <p>Theo dõi và quản lý danh mục thuốc trong cơ sở y tế của bạn.</p>
            </div>
            <button class="primary-btn">+ Thêm thuốc mới</button>
        </section>

        <section class="stats">
            <div class="stat-card">
                <p>Tổng loại thuốc</p>
                <h3><?php echo count($medicineTypes); ?></h3>
            </div>
            <div class="stat-card">
                <p>Tổng thuốc</p>
                <h3><?php echo count($medicines); ?></h3>
            </div>
            <div class="stat-card warning">
                <p>Danh mục hoạt động</p>
                <h3>100%</h3>
            </div>
            <div class="stat-card soft-danger">
                <p>Nhóm phổ biến</p>
                <h3>Kháng sinh</h3>
            </div>
        </section>

        <section class="tabs">
            <button class="tab-btn active" data-tab="medicinesTab">Danh sách thuốc</button>
            <button class="tab-btn" data-tab="typesTab">Loại thuốc</button>
            <button class="tab-btn" data-tab="formTab">Thêm mới</button>
        </section>

        <section class="panel active" id="medicinesTab">
            <div class="panel-toolbar">
                <input type="text" id="medicineSearch" placeholder="Tìm theo tên thuốc...">
                <select id="typeFilter">
                    <option value="">Loại thuốc</option>
                    <?php foreach ($medicineTypes as $type): ?>
                        <option value="<?php echo htmlspecialchars($type['name']); ?>">
                            <?php echo htmlspecialchars($type['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tên thuốc</th>
                            <th>Loại</th>
                            <th>Hoạt chất / Hàm lượng</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="medicineTable">
                        <?php foreach ($medicines as $medicine): ?>
                            <tr data-type="<?php echo htmlspecialchars($medicine['type']); ?>">
                                <td>
                                    <div class="medicine-cell">
                                        <img src="<?php echo htmlspecialchars($medicine['image']); ?>" alt="<?php echo htmlspecialchars($medicine['name']); ?>">
                                        <span><?php echo htmlspecialchars($medicine['name']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="pill"><?php echo htmlspecialchars($medicine['type']); ?></span>
                                </td>
                                <td class="ingredient">
                                    <?php echo htmlspecialchars($medicine['ingredient']); ?><br>
                                    <small><?php echo htmlspecialchars($medicine['dosage']); ?></small>
                                </td>
                                <td class="text-right actions">
                                    <button class="action-btn">Sửa</button>
                                    <button class="action-btn danger">Xóa</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel" id="typesTab">
            <div class="types-header">
                <h3>Danh sách loại thuốc</h3>
                <button class="primary-btn small">+ Thêm loại thuốc</button>
            </div>

            <div class="type-grid">
                <?php foreach ($medicineTypes as $type): ?>
                    <div class="type-card">
                        <h4><?php echo htmlspecialchars($type['name']); ?></h4>
                        <p><?php echo htmlspecialchars($type['description']); ?></p>
                        <div class="type-card-actions">
                            <button class="action-btn">Sửa</button>
                            <button class="action-btn danger">Xóa</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="panel" id="formTab">
            <div class="form-grid">
                <div class="form-card">
                    <h3>Thêm loại thuốc</h3>
                    <form>
                        <div class="form-group">
                            <label>Tên loại thuốc</label>
                            <input type="text" placeholder="Nhập tên loại thuốc">
                        </div>

                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea rows="4" placeholder="Nhập mô tả"></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="secondary-btn">Hủy</button>
                            <button type="submit" class="primary-btn small">Lưu loại thuốc</button>
                        </div>
                    </form>
                </div>

                <div class="form-card">
                    <h3>Thêm thuốc</h3>
                    <form>
                        <div class="form-group">
                            <label>Tên thuốc</label>
                            <input type="text" placeholder="Nhập tên thuốc">
                        </div>

                        <div class="form-group">
                            <label>Loại thuốc</label>
                            <select>
                                <option>Chọn loại thuốc</option>
                                <?php foreach ($medicineTypes as $type): ?>
                                    <option><?php echo htmlspecialchars($type['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Hoạt chất</label>
                            <input type="text" placeholder="Nhập hoạt chất">
                        </div>

                        <div class="form-group">
                            <label>Hàm lượng</label>
                            <input type="text" placeholder="Ví dụ: 500 MG / VIÊN">
                        </div>

                        <div class="form-group">
                            <label>Ảnh thuốc</label>
                            <input type="text" placeholder="Dán link ảnh thuốc">
                        </div>

                        <div class="form-actions">
                            <button type="button" class="secondary-btn">Hủy</button>
                            <button type="submit" class="primary-btn small">Lưu thuốc</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="highlight-box">
            <div>
                <h4>Hiệu suất Quản lý Danh mục</h4>
                <p>Giao diện tối ưu cho quản lý loại thuốc và thuốc, giữ phong cách gần giống mẫu gốc nhưng đã lược bỏ các trường không cần thiết.</p>
            </div>
            <div class="highlight-stats">
                <div>
                    <strong>2.4k</strong>
                    <span>Lượt thao tác</span>
                </div>
                <div>
                    <strong>+12%</strong>
                    <span>Tăng hiệu quả</span>
                </div>
            </div>
        </section>
    </main>

    <button class="fab">+</button>

    <script src="{{ asset('js/medications.js') }}"></script>
</body>
</html>