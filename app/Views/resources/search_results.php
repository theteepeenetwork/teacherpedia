<style>
    .thumbnail {
        max-width: 100%;
        margin: auto;
    }

    .list a {
        text-decoration: underline;
    }

    .middle {
        margin: auto;
    }
</style>

<body>
    <div class="container mt-5">
        <div class="mt-3">
            <table class="table table-bordered" id="users-list">
                <thead>
                    <tr>
                        <th>Resource Thumb</th>
                        <th>Resource Name</th>
                        <th>Description</th>
                        <th>Keystage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($table) : ?>
                        <?php foreach ($table as $user) : ?>
                            <tr>
                                <td>
                                    <a href="<?php echo '/resource/' . $user['slug']; ?>"><img class="thumbnail" src=" <?php echo $user['resource_thumb']; ?>"></img>
                                </td>
                                <td><a href="<?php echo '/resource/' . $user['slug']; ?>"><?php echo $user['resource_name']; ?></a></td>
                                <td><?php echo $user['resource_description']; ?></td>
                                <td><?php echo $user['year']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-end">
                <?php if ($pager) : ?>
                    <?php $pagi_path = 'resources/search/search_results'; ?>
                    <?php $pager->setPath($pagi_path); ?>
                    <?= $pager->links() ?>
                <?php endif ?>
            </div>

        </div>
    </div>
</body>

</html>