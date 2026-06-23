<div class="breadcrumb">


    <?php if (current_url() !== base_url() && current_url() !== base_url() . "home") { ?>
        <div class="breadcrumbs">
            <?php
            $segments = $breadcrumbs;
            $last_segment = '/'; ?>
            <?php
            foreach ($segments as $segment) {
                $last_segment .= '/' . $segment;
            ?>
                <?php
                echo ' > ' . '<a href="' . substr($last_segment, 1) . '">' .    ucfirst(str_replace('-', '-', str_replace('_', ' ', $segment))) . '</a>';
                ?>
            <?php
            }
            ?>
        </div>
    <?php } ?>
</div>