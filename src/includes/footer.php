</div><!-- /.container (main content) -->

<footer class="mt-5 pt-5 pb-3">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4 mb-4">
                <div class="footer-brand mb-2">The Daily<span>Pulse</span></div>
                <p style="font-size:.875rem;">Independent journalism dedicated to truth, depth, and accountability. Serving readers since 2020.</p>
            </div>
            <div class="col-md-2 mb-4">
                <h6 class="text-white text-uppercase mb-3" style="font-size:.8rem;letter-spacing:.1em;">Sections</h6>
                <ul class="list-unstyled">
                    <?php foreach (get_all_categories() as $cat): ?>
                    <li class="mb-1"><a href="<?= SITE_URL ?>/category.php?slug=<?= h($cat['slug']) ?>"><?= h($cat['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h6 class="text-white text-uppercase mb-3" style="font-size:.8rem;letter-spacing:.1em;">Company</h6>
                <ul class="list-unstyled">
                    <li class="mb-1"><a href="<?= SITE_URL ?>/about.php">About Us</a></li>
                    <li class="mb-1"><a href="<?= SITE_URL ?>/about.php#contact">Contact</a></li>
                    <li class="mb-1"><a href="#">Privacy Policy</a></li>
                    <li class="mb-1"><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h6 class="text-white text-uppercase mb-3" style="font-size:.8rem;letter-spacing:.1em;">Follow Us</h6>
                <div class="d-flex gap-3" style="font-size:1.4rem;">
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="border-top border-secondary pt-3 d-flex flex-wrap justify-content-between">
            <span>&copy; <?= date('Y') ?> The Daily Pulse. All rights reserved.</span>
            <span>Built with PHP &amp; Bootstrap</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
