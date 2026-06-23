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

<?php include 'breadcrumbs.php'; ?>


<div class="list">
  <?php
  if (is_array($table)) {
    foreach ($table as $row) {
      echo $row;
    }
  } else {
    echo $table;
  }
  //echo '<div id="pagination">' . $links . '</div>';
  ?>
</div>