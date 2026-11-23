<?php 
include_once('../config/constant.php');
$current_page = 'services.php';
include './partials/header.php'; 

// Fetch categories and services
$categories = db_fetch_all($conn, "SELECT * FROM tbl_category ORDER BY category_name ASC");
$services = db_fetch_all($conn, "SELECT s.*, c.category_name FROM tbl_service s LEFT JOIN tbl_category c ON s.category_id = c.id ORDER BY s.service_type ASC");
?>

<style>
.fn-page-header {
    background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%);
    padding: 50px 20px 30px 20px;
    text-align: center;
    border-bottom: 1px solid #e2e8f0;
}

.fn-filter-tabs {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    margin: 30px 0;
}

.fn-filter-pill {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    padding: 8px 18px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s ease;
}

.fn-filter-pill:hover,
.fn-filter-pill.active {
    background: #0284c7;
    color: #ffffff;
    border-color: #0284c7;
}

.fn-services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}

.fn-service-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 14px rgba(0,0,0,0.02);
    transition: transform 0.2s, box-shadow 0.2s;
}

.fn-service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(2, 132, 199, 0.1);
}

.fn-service-img-wrapper {
    height: 180px;
    width: 100%;
    overflow: hidden;
    background: #0f172a;
}

.fn-service-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.fn-service-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.fn-category-badge {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: #0284c7;
    background: #e0f2fe;
    padding: 3px 8px;
    border-radius: 4px;
    align-self: flex-start;
    margin-bottom: 8px;
}

.fn-service-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 12px;
}

.fn-service-footer {
    margin-top: auto;
    padding-top: 14px;
    border-top: 1px dashed #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.fn-service-price {
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
}
</style>

<div class="fn-page-header">
    <div style="max-width: 800px; margin: 0 auto;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px; background: #e0f2fe; color: #0284c7; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-screwdriver-wrench"></i> Trade Categories &amp; Services
        </span>
        <h1 style="font-size: 34px; font-weight: 900; color: #0f172a; margin: 0 0 10px 0;">Trade Services Catalog</h1>
        <p style="color: #64748b; font-size: 16px; margin: 0 auto; max-width: 600px; line-height: 1.6;">
            Explore the full spectrum of certified residential, domestic, and commercial maintenance services offered on FixNow.
        </p>

        <!-- Search Bar -->
        <div style="max-width: 500px; margin: 24px auto 0 auto; position: relative;">
            <input type="text" id="service-search-input" placeholder="Search services or skills..." 
                   onkeyup="filterServices();"
                   style="width: 100%; box-sizing: border-box; padding: 13px 20px 13px 44px; border-radius: 50px; border: 1.5px solid #cbd5e1; font-size: 14.5px; outline: none; font-family: inherit; box-shadow: 0 4px 14px rgba(0,0,0,0.04);" />
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
        </div>
    </div>
</div>

<section style="padding: 30px 20px 80px 20px; max-width: 1200px; margin: 0 auto;">
    
    <!-- Filter Pills -->
    <div class="fn-filter-tabs">
        <button class="fn-filter-pill active" onclick="filterCategory('all', this)">All Categories</button>
        <?php foreach ($categories as $cat): ?>
            <button class="fn-filter-pill" onclick="filterCategory('<?php echo $cat['id']; ?>', this)">
                <?php echo htmlspecialchars($cat['category_name']); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Services Grid -->
    <div class="fn-services-grid" id="services-container">
        <?php foreach ($services as $srv): 
            $cat_id = $srv['category_id'];
            $cat_name = htmlspecialchars($srv['category_name'] ?? 'General');
            $srv_name = htmlspecialchars($srv['service_type']);
            $price = floatval($srv['booking_price']);
            $srv_img_name = basename($srv['service_image'] ?? '');
            $img = (!empty($srv['service_image']) && file_exists('../images/category_images/' . $srv_img_name)) 
                ? '../images/category_images/' . htmlspecialchars($srv_img_name) 
                : '../assets/images/service-1.jpg';
        ?>
            <div class="fn-service-card" data-category="<?php echo $cat_id; ?>" data-title="<?php echo strtolower($srv_name . ' ' . $cat_name); ?>">
                <div class="fn-service-img-wrapper">
                    <img src="<?php echo $img; ?>" alt="<?php echo $srv_name; ?>" />
                </div>
                <div class="fn-service-body">
                    <span class="fn-category-badge"><?php echo $cat_name; ?></span>
                    <h3 class="fn-service-title"><?php echo $srv_name; ?></h3>
                    
                    <div class="fn-service-footer">
                        <span class="fn-service-price">
                            From LKR <?php echo number_format($price, 2); ?>
                        </span>
                        <a href="vendors.php?service_id=<?php echo $srv['id']; ?>" style="color: #0284c7; font-weight: 700; font-size: 13.5px; text-decoration: none;">
                            Find Pros <i class="fa-solid fa-arrow-right fa-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</section>

<script>
let currentCategory = 'all';

function filterCategory(catId, btn) {
    currentCategory = catId;
    document.querySelectorAll('.fn-filter-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filterServices();
}

function filterServices() {
    const query = document.getElementById('service-search-input').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.fn-service-card');
    
    cards.forEach(card => {
        const matchesCategory = (currentCategory === 'all' || card.getAttribute('data-category') === currentCategory);
        const matchesQuery = card.getAttribute('data-title').includes(query);
        
        if (matchesCategory && matchesQuery) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

<?php 
include './partials/footer.php'; 
?>
