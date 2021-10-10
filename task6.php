<?php

require './helpers/validate.php';
require './dbcon.php';
error_reporting(0);
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean($_POST['title']);
    $content = clean($_POST['content']);
    $img_file = $_FILES['image']['name'];


    $error_msg = [];

#title validation
    if (!validate($title, 1)) {
        $error_msg['title'] = 'This Field is required';
    }

#content validation
    if (!validate($content, 1)) {
        $error_msg['content'] = 'This Field is required';
    } elseif (!validate($content, 3, 50)) {
        $error_msg['content'] = 'Article content must be more than 50 chars';
    }
    if (!empty($_FILES['image']['name'])) {
        $imgTmp = $_FILES['image']['tmp_name'];
        $imgName = $_FILES['image']['name'];
        $imgType = $_FILES['image']['type'];
        $imgSize = $_FILES['image']['size'];
        $imgError = $_FILES['image']['error'];
        $allowdEx = ['jpg', 'png', 'jpeg'];
        $ExeArray = explode('.', $imgName);
        if (in_array($ExeArray[1], $allowdEx)) {
            $newName = uniqid('img_file', False) . '.' . $ExeArray[1];
            $dirPath = './uploads/' . $newName;

            if (move_uploaded_file($imgTmp, $dirPath)) {
                echo '<div class="alert alert-success" role="alert" style="text-align: center;">
                Image Uploaded successfully . <br>
        </div>';
            } else {
                echo '<div class="alert alert-danger" role="alert" style="text-align: center;">
                Error while uploading file try again. "<br>"</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert" style="text-align: center;">
            Not Allowed Extension . "<br>"</div>';
        }

    } else {
        echo '<div class="alert alert-danger" role="alert" style="text-align: center;">
        * => Image : Please upload an image <br>
        </div>';
    }
    if (count($error_msg) > 0) {
        foreach ($error_msg as $key => $val) {
            echo "<div class='alert alert-danger w-75 p-3' role='alert' style='text-align: center;'>
        *  => $key   :    $val <br>
    </div>";
        }
    }
    else {
        $query = "INSERT INTO `articles` (`title`,`content`,`image`) VALUES ('$title','$content','$imgName')";
        $sql_exe = mysqli_query($conn,$query);
        if($sql_exe){
            echo '<div class="alert alert-success" role="alert" style="text-align: center;">
            Data inserted into DB successfully. <br></div>';
        }else {
            echo '<div class="alert alert-danger" role="alert" style="text-align: center;">
            Error unsaved Data . "<br>"</div>';
        }
        mysqli_close($conn);
    }
}

?>

<?php include './layout/header.php'?>
<body>
<div class="container">
    <h1>Article Form</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-25">
                <label for="name"><b>Title : </b></label>
            </div>
            <div class="col-75">
                <input type="text" id="title" name="title" placeholder="Your Article Title..">
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label for="email"><b>Content : </b></label>
            </div>
            <div class="col-75">
                <textarea rows="7" cols="75" id="content" name="content" class="content" placeholder="Your Content Here.."></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label for="country"><b>Image : </b></label>
            </div>
            <div class="col-75">
                <input type="file" id="image" name="image" class="image" placeholder="Your Password..">
            </div>
        </div>
        <div class="submit">
            <input type="submit" value="Submit">
        </div>
    </form>
</div>

</body>
</html>

