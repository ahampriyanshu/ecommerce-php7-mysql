<?php
require('header.php');
?>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 login-section-wrapper pl-5 ">
                    <div class="login-wrapper">
                    <h1 class="login-title mb-4">Edit Categories</h1>
                        <form class="form-horizontal" method="post" action="updateCategory.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo $row['section_id'] ?>">
                                <label for="email">Category Title</label>
                                <input type="text" name="name" class="form-control" id="email" value="<?php echo $row['section_name'] ?>" required />
                            </div>
                            <input type="submit" name="submit" id="submit login" class="btn btn-block login-btn" value="Update">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>