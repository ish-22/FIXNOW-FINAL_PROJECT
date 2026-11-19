<?php 
include './partials/header.php'; 

// Fetch categories and services
$categories = db_fetch_all($conn, "SELECT * FROM tbl_category ORDER BY category_name ASC");
$services = db_fetch_all($conn, "SELECT s.*, c.category_name FROM tbl_service s LEFT JOIN tbl_category c ON s.category_id = c.id ORDER BY s.service_type ASC");
?>

<style>
.fn-page-header {
    background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%);
    padding: 50px 0 30px 0;
    text-align: center;
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
    font-size: 14.5px;
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
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
}

.fn-search-wrapper {
    max-width: 480px;
    margin: 0 auto 30px auto;
    position: relative;
}

.fn-search-input {
    width: 100%;
    padding: 12px 18px 12px 45px;
    border-radius: 50px;
    border: 1.5px solid #cbd5e1;
    font-size: 15px;
    font-family: inherit;
    outline: none;
    box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    transition: all 0.2s;
}

.fn-search-input:focus {
    border-color: #0284c7;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
}

.fn-search-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 16px;
}

.fn-category-tag {
    display: inline-block;
    background: #f1f5f9;
    color: #0284c7;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 4px;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>

<div class="fn-page-header">
    <div class="fn-container">
        <h1 class="fn-section-title">Explore Professional Services</h1>
        <p class="fn-section-subtitle">
            Select a category or search for specialized services to connect with verified providers near you.
        </p>

        <!-- Live Search Input -->
        <div class="fn-search-wrapper">
            <i class="fa-solid fa-magnifying-glass fn-search-icon"></i>
            <input type="text" id="fnServiceSearch" class="fn-search-input" placeholder="Search service e.g. Electrician, Plumbing, AC..." />
        </div>

        <!-- Category Pills -->
        <div class="fn-filter-tabs">
            <button type="button" class="fn-filter-pill active" data-category="all">All Services</button>
            <?php foreach ($categories as $cat): ?>
                <button type="button" class="fn-filter-pill" data-category="<?php echo $cat['id']; ?>">
                    <?php echo htmlspecialchars($cat['category_name']); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<section style="padding: 20px 0 80px 0;">
    <div class="fn-container">
        <!-- Services Grid -->
        <div class="fn-grid-3" id="fnServicesGrid">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $srv): 
                    $img = !empty($srv['service_image']) && file_exists('./images/service_images/' . $srv['service_image']) 
                        ? './images/service_images/' . htmlspecialchars($srv['service_image']) 
                        : './assets/images/service.jpeg';
                ?>
                <div class="fn-card fn-service-card-item" data-category="<?php echo $srv['category_id']; ?>" data-name="<?php echo strtolower(htmlspecialchars($srv['service_type'])); ?>">
                    <div style="overflow: hidden;">
                        <img src="<?php echo $img; ?>" class="fn-service-card-img" alt="<?php echo htmlspecialchars($srv['service_type']); ?>" />
                    </div>
                    <div class="fn-service-card-body">
                        <div>
                            <span class="fn-category-tag"><?php echo htmlspecialchars($srv['category_name'] ?? 'General Service'); ?></span>
                            <h3 class="fn-service-card-title"><?php echo htmlspecialchars($srv['service_type']); ?></h3>
                            <p class="fn-service-card-desc"><?php echo htmlspecialchars($srv['about_service']); ?></p>
                        </div>
                        <div class="fn-service-card-footer">
                            <span style="font-size: 13px; color: #10b981; font-weight: 700;">
                                <i class="fa-solid fa-circle-check"></i> Verified Specialists Available
                            </span>
                            <a href="vendor-search.php?service_type=<?php echo $srv['id']; ?>" class="fn-btn fn-btn-primary" style="padding: 7px 16px; font-size: 13.5px; border-radius: 50px;">
                                Find Specialists <i class="fa-solid fa-arrow-right fa-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #64748b; grid-column: 1 / -1;">No services currently listed.</p>
            <?php endif; ?>
        </div>

        <div id="fnNoResults" style="display: none; text-align: center; padding: 60px 0;">
            <i class="fa-regular fa-folder-open fa-3x" style="color: #94a3b8; margin-bottom: 16px;"></i>
            <h3 style="color: #334155; font-size: 1.4rem;">No matching services found</h3>
            <p style="color: #64748b;">Try searching for another keyword or selecting "All Services".</p>
        </div>
    </div>
</section>

<script>
(function() {
    const searchInput = document.getElementById('fnServiceSearch');
    const pills = document.querySelectorAll('.fn-filter-pill');
    const cards = document.querySelectorAll('.fn-service-card-item');
    const noResults = document.getElementById('fnNoResults');
    let activeCategory = 'all';

    function filterCards() {
        const query = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            const cardName = card.getAttribute('data-name');

            const matchesCategory = (activeCategory === 'all' || cardCat === activeCategory);
            const matchesQuery = (!query || cardName.includes(query));

            if (matchesCategory && matchesQuery) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        noResults.style.display = (visibleCount === 0) ? 'block' : 'none';
    }

    pills.forEach(pill => {
        pill.addEventListener('click', function() {
            pills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            activeCategory = this.getAttribute('data-category');
            filterCards();
        });
    });

    searchInput.addEventListener('input', filterCards);
})();
</script>

<?php include './partials/footer.php'; ?>
