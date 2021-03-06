<?php
include('boilerplate.php');

$per_page = 12;

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start_from = ($page - 1) * $per_page;

?>
<style>
    #price_range {
        height: 6px;
    }

    .ui-slider-handle {
        height: 13px !important;
        width: 13px !important;
        background: #66FCF1 !important;
        border-radius: 25px;
    }

    .ui-slider-range.ui-corner-all.ui-widget-header {
        background: #333;
    }

    .sublist {
        color: black;
        font-size: 12px;
        font-weight: bolder;
        text-transform: uppercase;
    }

    .pricelist {
        color: black;
        font-size: 16px;
        font-weight: bolder;
        text-transform: uppercase;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-8 produts-sidebar-filter">

            <div class="filter-widget">

                <h3 class="fw-title"><span class="badge badge-dark">PRICE</span></h3>
                <input type="hidden" id="min_price_hide" value="1" />
                <input type="hidden" id="max_price_hide" value="5000" />
                <p id="price_show" class="pricelist">&#x20B9; 1 - &#x20B9; 5000</p>
                <div id="price_range"></div>
            </div>

            <div class="filter-widget">
                <h3 class="fw-title"><span class="badge badge-dark">SECTIONS</span></h3>
                <div class="fw-brand-check">
                    <?php
                    $query = "
            SELECT DISTINCT(section) FROM product ORDER BY section ASC
            ";
                    $statement = $con->prepare($query);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    foreach ($result as $row) {
                        $sql = "
            SELECT section_name FROM section WHERE section_id = '$row[0]'
            ";
                        $exe = $connect->query($sql);
                        $name = $exe->fetch_assoc();

                    ?>
                        <div class="bc-item">
                            <label for="<?php echo $name['section_name']; ?>">
                                <input type="checkbox" id="<?php echo $name['section_name']; ?>" class="filter_all section" value="<?php echo $row['section']; ?>">
                                <span class="checkmark"></span>
                                <p class="sublist"><?php echo $name['section_name']; ?></p>
                            </label>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>


            <div class="filter-widget">
                <h3 class="fw-title"><span class="badge badge-dark">CATEGORY</span></h3>
                <div class="fw-brand-check">
                    <?php

                    $query = "
            SELECT DISTINCT(category) FROM product ORDER BY category ASC
            ";
                    $statement = $con->prepare($query);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    foreach ($result as $row) {
                        $sql = "
            SELECT category_name FROM category WHERE category_id = '$row[0]'
            ";
                        $exe = $connect->query($sql);
                        $name = $exe->fetch_assoc();
                    ?>
                        <div class="bc-item">
                            <label for="<?php echo $name['category_name']; ?>">
                                <input type="checkbox" id="<?php echo $name['category_name']; ?>" class="filter_all category" value="<?php echo $row['category']; ?>">
                                <span class="checkmark"></span>
                                <p class="sublist"><?php echo $name['category_name']; ?></p>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="filter-widget">
                <h3 class="fw-title"><span class="badge badge-dark">BRANDS</span></h3>
                <div class="fw-brand-check">
                    <?php

                    $query = "
            SELECT DISTINCT(brand) FROM product ORDER BY brand ASC
            ";
                    $statement = $con->prepare($query);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    foreach ($result as $row) {
                        $sql = "
            SELECT brand_name FROM brand WHERE brand_id = '$row[0]'
            ";
                        $exe = $connect->query($sql);
                        $name = $exe->fetch_assoc();
                    ?>
                        <div class="bc-item">
                            <label for="<?php echo $name['brand_name']; ?>">
                                <input type="checkbox" id="<?php echo $name['brand_name']; ?>" class="filter_all brand" value="<?php echo $row['brand']; ?>">
                                <span class="checkmark"></span>
                                <p class="sublist"><?php echo $name['brand_name']; ?></p>
                            </label>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="row filter_data"></div>
            <?php include('pagination.php'); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        filter_data();

        function filter_data() {
            $('.filter_data');
            var action = 'fetch_data';
            var start_from = <?php echo $start_from; ?>;
            var per_page = <?php echo $per_page; ?>;
            var minimum_price = $('#min_price_hide').val();
            var maximum_price = $('#max_price_hide').val();
            var brand = get_filter('brand');
            var category = get_filter('category');
            var section = get_filter('section');
            $.ajax({
                url: "fetch.php",
                method: "POST",
                data: {
                    action: action,
                    start_from: start_from,
                    per_page: per_page,
                    minimum_price: minimum_price,
                    maximum_price: maximum_price,
                    brand: brand,
                    category: category,
                    section: section
                },
                success: function(data) {
                    $('.filter_data').html(data);
                }
            });
        }

        function get_filter(class_name) {
            var filter = [];
            $('.' + class_name + ':checked').each(function() {
                filter.push($(this).val());
            });
            return filter;
        }

        $('.filter_all').click(function() {
            filter_data();
        });

        $('#price_range').slider({
            range: true,
            min: 1,
            max: 5000,
            values: [1, 5000],
            step: 100,
            stop: function(event, ui) {
                $('#price_show').html(' &#x20B9;  ' + ui.values[0] + ' -  &#x20B9; ' + ui.values[1]);
                $('#min_price_hide').val(ui.values[0]);
                $('#max_price_hide').val(ui.values[1]);
                filter_data();
            }
        });
    });
</script>
<?php include('footer.php'); ?>