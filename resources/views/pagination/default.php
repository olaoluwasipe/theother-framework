<?php if ($paginator->hasPages()): ?>
    <ul class="pagination">
        <!-- Previous Page Link -->
        <?php if ($paginator->onFirstPage()): ?>
            <li class="disabled"><span>«</span></li>
        <?php else: ?>
            <li><a href="<?= $paginator->previousPageUrl() ?>" rel="prev">«</a></li>
        <?php endif; ?>

        <?php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $range = 2; // Number of pages to show around the current page
        $pagination = [];

        // Always show the first page
        if ($currentPage > 1 + $range) {
            $pagination[] = 1;
            if ($currentPage > 2 + $range) {
                $pagination[] = '...';
            }
        }

        // Show pages around current page
        for ($i = max(1, $currentPage - $range); $i <= min($lastPage, $currentPage + $range); $i++) {
            $pagination[] = $i;
        }

        // Always show last page
        if ($currentPage < $lastPage - $range) {
            if ($currentPage < $lastPage - ($range + 1)) {
                $pagination[] = '...';
            }
            $pagination[] = $lastPage;
        }
        ?>

        <!-- Page Links -->
        <?php foreach ($pagination as $page): ?>
            <?php if ($page === '...'): ?>
                <li class="disabled"><span>...</span></li>
            <?php elseif ($page == $currentPage): ?>
                <li class="active"><span><?= $page ?></span></li>
            <?php else: ?>
                <li><a href="<?= $paginator->url($page) ?>"><?= $page ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Next Page Link -->
        <?php if ($paginator->hasMorePages()): ?>
            <li><a href="<?= $paginator->nextPageUrl() ?>" rel="next">»</a></li>
        <?php else: ?>
            <li class="disabled"><span>»</span></li>
        <?php endif; ?>
    </ul>
<?php endif; ?>
