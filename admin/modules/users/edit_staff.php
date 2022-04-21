<?php 
    if(!defined('SECURITY')) {
        die("You don't have authorization to view this page !");
    }

    $edit = $_GET['edit_id'];
    $staff = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM staffs WHERE user_id = $edit"));

    if (isset($_POST['staff_full']) && isset($_POST['staff_mail'])) {
        $name = $_POST['staff_full'];
        $mail = $_POST['staff_mail'];
        $check = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '$mail'"));
        if (isset($check) && $check['user_mail'] !== $staff['user_mail']) {
            $error = '<div class="alert-danger">Account already exists ! </div>';
        } else {
            $gender = $_POST['gender'];
            $birdth = $_POST['staff_birdth'];
            $phone = $_POST['staff_tel'];
            $add = $_POST['staff_add'];
            $level = $_POST['staff_level'];
            $arrName = explode(' ', $name);
            $arrEx = explode('/', $_FILES['staff_avata']['type']);
            $user_ava = mb_strtolower(convert_name(implode('_', $arrName)).'.'.end($arrEx));
            $des = 'images/avata/'.$user_ava;
            if (file_exists($des)) {
                $increment = 0;
                $info = pathinfo($des);
                while (file_exists($des)) {
                    $increment++;
                    $user_ava = $info['filename'].'-'.$increment.'.'.$info['extension'];
                    $des = "images/avata/".$user_ava;
                }
            } 
            move_uploaded_file($_FILES['staff_avata']['tmp_name'], $des);
            $original = $_POST['staff_pass'];
            $pass = md5($original);
            $status = mysqli_query($conn, "UPDATE staffs SET user_full = '$name', user_image = '$user_ava', user_mail = '$mail', user_add = '$add', user_tel = '$phone', user_gender = $gender, user_birdth = '$birdth', user_level = $level WHERE user_id = $edit");

            if (isset($status)) {
                header('location: index.php?page_layout=user_staff');
            }
        }
    }    
?>
<section class="breadcumb-area">
    <h3>Administration Staffs</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item"><a href="index.php?page_layout=user_staff">Staffs</a></li>
        <li class="breadcumb-item"><?php if (!empty($staff['user_full'])) {echo $staff['user_full'];} else {$arrTmp = explode('@', $staff['user_mail']); echo ucfirst(reset($arrTmp));} ?></li>
    </ul>
</section>
<section class="add_page">
    <?php 
        if (!empty($error)) {echo $error;} 
    ?> 
    <form method="post" id="edit_staff" class="flex-between" enctype="multipart/form-data">
        <div class="form-item">
            <div class="form-group">
                <label for="staff_full">Full name:</label>
                <input type="text" name="staff_full" id="staff_full" value="<?= $staff['user_full']; ?>" />
                <div class="form-message"></div>
            </div>            
            <div class="form-group">
                <label for="staff_mail">Email:</label>
                <input type="text" name="staff_mail" id="staff_mail" value="<?= $staff['user_mail']; ?>" />
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="user_gender">Full name:</label>
                <div id="user_gender" class="flex-start custom-radio">
                    <div class="items-center">
                        <input <?php if($staff['user_gender'] == 0) {echo 'checked';} ?> type="radio" name="gender" id="male" value="0" />
                        <label for="male"><span>Male</span></label>
                    </div>
                    <div class="items-center">
                        <input <?php if($staff['user_gender'] == 1) {echo 'checked';} ?> type="radio" name="gender" id="fe-male" value="1" />
                        <label for="fe-male"><span>Female</span></label>
                    </div>                    
                    <div class="items-center">
                        <input <?php if($staff['user_gender'] == 2) {echo 'checked';} ?> type="radio" name="gender" id="others" value="2" />
                        <label for="others"><span>Others</span></label>
                    </div>
                </div>
                <div class="form-message"></div>
            </div>     
            <div class="form-group">
                <label for="staff_tel">Mobile:</label>
                <input type="tel" name="staff_tel" id="staff_tel" value="<?= $staff['user_tel']; ?>" />
                <div class="form-message"></div>
            </div>           
            <div class="form-group">
                <label for="staff_birdth">Birdth Day:</label>
                <input type="date" name="staff_birdth" id="staff_birdth" value="<?= $staff['user_birdth']; ?>" />
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="staff_add">Address:</label>
                <input type="text" name="staff_add" id="staff_add" value="<?= $staff['user_add']; ?>" />
                <div class="form-message"></div>
            </div>
        </div>
        <div class="form-item">
            <div class="form-group">
                <label for="staff_avata">Avatar:</label>
                <div class="items-center">
                    <input hidden type="file" name="staff_avata" id="staff_avata" />
                    <input class="green" onclick="document.getElementById('staff_avata').click()" type="button"
                        value="Chose avata" />
                    <div class="form-message"></div>
                </div>
                <div id="ava-preview" class="user_preview">
                    <?php if (!empty($staff['user_image'])) {echo '<img src="images/avata/'.$staff['user_image'].'" alt="" />'; } ?>
                </div>
            </div>                   
            <div class="form-group">
                <label for="staff_level">Permission:</label>
                <select name="staff_level" id="staff_level">
                    <option <?php if($staff['user_level'] == 0) {echo 'selected';} ?> value="0">Admin</option>
                    <option <?php if($staff['user_level'] == 1) {echo 'selected';} ?> value="1">Manager</option>
                    <option <?php if($staff['user_level'] == 2) {echo 'selected';} ?> value="2">Member</option>
                </select>
                <div class="form-message"></div>
            </div>
            <div class="flex-start">
                <button class="btn green" name="sbm" type="submit">UPDATE</button>
                <button class="btn gray" type="reset">Reset</button>
            </div>
        </div>
    </form>
</section>
