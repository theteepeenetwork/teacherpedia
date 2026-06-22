<div class="main__cards">
    <div class="card">
        <div class="card__header">
            <div class="card__header-title text-light">Add <strong>Resource</strong>
                <!--<a href="#" class="card__header-link text-bold">View All</a>-->
            </div>
        </div>
        <div class="card__main">
            <!-- Insert content here -->


            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Confirm Deletion of <?php echo $row->resource_name ?></h3>
                        <p class="text-muted m-b-30 font-13">This will remove the resource from the website.</p>
                        <table class="table">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">First</th>
                            </tr>
                            <td>
                                Author:
                            </td>
                            <td>
                                <?php echo $row->resource_author; ?>
                            </td>
                            </tr>
                            <row>
                                <td>
                                    Resource Name:
                                </td>
                                <td>
                                    <?php echo $row->resource_name; ?>
                                </td>
                                </tr>
                                <row>
                                    <td>
                                        Slug:
                                    </td>
                                    <td>
                                        <?php echo $row->slug; ?>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Excerpt:
                                        </td>
                                        <td>
                                            <?php echo $row->resource_excerpt; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Resource Description:
                                        </td>
                                        <td>
                                            <?php echo $row->resource_description; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Resource Link:
                                        </td>
                                        <td>
                                            <?php echo $row->link; ?>
                                        </td>
                                    </tr>
                        </table>

                        <a href="<?php echo base_url() . 'admin/resources/delete_test_resource/' . $row->id ?>"><button class="btn btn-danger">Confirm</button></a>

                    </div>
                </div>
            </div>

            <!--end card content -->
        </div>
    </div>
</div>