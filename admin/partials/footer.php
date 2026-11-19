        </main>

        <!-- Admin Footer -->
        <footer class="footer">
            <div>
                &copy; <?php echo date('Y'); ?> <strong>FIXNOW Lanka Technologies</strong>. All Rights Reserved. Engineered by <a href="../about.php">SLDS Software Company</a>.
            </div>
        </footer>
    </div>

    <!-- Sidebar Mobile Toggle Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('adminSidebarToggle');
        const sidebar = document.getElementById('adminSidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
        }
    });
    </script>
</body>
</html>
