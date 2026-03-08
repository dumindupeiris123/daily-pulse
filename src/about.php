<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = 'About Us — ' . SITE_NAME;
require __DIR__ . '/includes/header.php';
?>

<div class="container">

<div class="row mb-5">
    <div class="col-12">
        <div style="background:var(--ink);color:#fff;padding:4rem 2rem;text-align:center;margin-bottom:3rem;">
            <h1 style="font-family:var(--serif);font-weight:900;font-size:3rem;">About The Daily Pulse</h1>
            <p class="lead mt-2" style="color:rgba(255,255,255,.75);max-width:600px;margin:0 auto;">
                Independent journalism dedicated to truth, accountability, and depth.
            </p>
        </div>
    </div>
</div>

<div class="row g-5 mb-5">
    <div class="col-lg-8">
        <h2 style="font-family:var(--serif);" class="mb-4">Our Mission</h2>
        <p class="lead">The Daily Pulse was founded with a single purpose: to deliver accurate, impartial, and in-depth news to readers who demand more than headlines.</p>
        <p>In an era of information overload and sensationalism, we believe in the power of careful reporting, rigorous fact-checking, and nuanced storytelling. Every article we publish goes through multiple layers of editorial review before it reaches you.</p>
        <p>We cover politics, technology, sports, health, business, and entertainment — not just as separate silos, but as interconnected parts of the world we all share.</p>

        <h3 style="font-family:var(--serif);" class="mt-5 mb-3">Our Values</h3>
        <div class="row g-3">
            <?php
            $values = [
                ['bi-shield-check', 'Accuracy', 'Every fact is verified before publication. Corrections are issued promptly and transparently.'],
                ['bi-eye', 'Independence', 'We have no corporate overlords or political benefactors. Our only allegiance is to the truth.'],
                ['bi-people', 'Accountability', 'We hold power to account — in government, business, and institutions.'],
                ['bi-layers', 'Depth', 'We go beyond the surface, providing context and analysis that helps you understand the why.'],
            ];
            foreach ($values as $v): ?>
            <div class="col-md-6">
                <div class="p-3 h-100" style="border:1px solid var(--border);background:#fff;">
                    <i class="bi <?= $v[0] ?>" style="font-size:1.5rem;color:var(--accent);"></i>
                    <h6 style="font-family:var(--serif);" class="mt-2 mb-1"><?= $v[1] ?></h6>
                    <p class="mb-0 text-muted" style="font-size:.875rem;"><?= $v[2] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <h3 style="font-family:var(--serif);" class="mt-5 mb-3">Our Team</h3>
        <?php
        $team = [
            ['Alex Thompson', 'Editor-in-Chief', 'Former international correspondent with 15 years covering conflict zones and political transitions.', 1],
            ['Sarah Mitchell', 'Senior Editor', 'Specializes in technology policy and the intersection of Silicon Valley and democracy.', 2],
            ['James Okafor', 'Staff Writer', 'Covers health, science, and climate. Recipient of the 2023 Science Journalism Award.', 3],
        ];
        ?>
        <div class="row g-3">
            <?php foreach ($team as $member): ?>
            <div class="col-md-4">
                <div class="text-center p-3" style="background:#fff;border:1px solid var(--border);">
                    <img src="https://i.pravatar.cc/100?img=<?= $member[3] ?>" class="rounded-circle mb-2" width="80" height="80" alt="">
                    <h6 style="font-family:var(--serif);" class="mb-0"><?= $member[0] ?></h6>
                    <div style="font-size:.75rem;color:var(--accent);text-transform:uppercase;letter-spacing:.07em;font-weight:700;"><?= $member[1] ?></div>
                    <p class="text-muted mt-2 mb-0" style="font-size:.8rem;"><?= $member[2] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <div style="background:#fff;border:1px solid var(--border);padding:2rem;" class="mb-4">
            <h5 style="font-family:var(--serif);" class="mb-3">By the Numbers</h5>
            <?php
            $stats = get_dashboard_stats();
            $numbers = [
                [$stats['published'],        'Published Articles'],
                [$stats['total_categories'], 'Topic Categories'],
                [number_format($stats['total_views']), 'Total Article Views'],
                ['2020', 'Year Founded'],
            ];
            foreach ($numbers as $n): ?>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted"><?= $n[1] ?></span>
                <strong style="font-family:var(--serif);font-size:1.1rem;"><?= $n[0] ?></strong>
            </div>
            <?php endforeach; ?>
        </div>

        <div id="contact" style="background:var(--ink);color:#fff;padding:2rem;">
            <h5 style="font-family:var(--serif);" class="mb-3">Contact Us</h5>
            <ul class="list-unstyled" style="font-size:.875rem;">
                <li class="mb-2"><i class="bi bi-envelope me-2" style="color:var(--accent);"></i><?= ADMIN_EMAIL ?></li>
                <li class="mb-2"><i class="bi bi-telephone me-2" style="color:var(--accent);"></i>+1 (555) 0192</li>
                <li class="mb-2"><i class="bi bi-geo-alt me-2" style="color:var(--accent);"></i>123 Press Row, Media City, CA</li>
            </ul>
            <hr style="border-color:rgba(255,255,255,.2);">
            <p style="font-size:.8rem;color:rgba(255,255,255,.6);">For tips and press inquiries, please include "NEWS TIP" or "PRESS" in your subject line.</p>
        </div>
    </div>
</div>

</div><!-- /container -->

<?php require __DIR__ . '/includes/footer.php'; ?>
