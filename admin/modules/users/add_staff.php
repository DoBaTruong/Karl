<?php 
    if(!defined('SECURITY')) {
        die("You don't have authorization to view this page !");
    }
    if (isset($_POST['staff_full']) && isset($_POST['staff_mail'])) {
        $name = $_POST['staff_full'];
        $mail = $_POST['staff_mail'];
        $check = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '$mail'"));
        if (isset($check)) {
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
            $date = date('Y-m-d');
            $status = mysqli_query($conn, "INSERT INTO staffs (user_full, user_image, user_mail, user_add, user_tel, user_gender, user_birdth, user_pass, user_original, user_level, user_locked, user_create) VALUES ('$name', '$user_ava', '$mail', '$add', '$phone', $gender, '$birdth', '$pass', '$original', $level, 0, '$date')");

            if (isset($status)) {
                header('location: index.php?page_layout=staff');
            }
        }
    }    
?>
<section class="breadcumb-area">
    <h3>Administration Staffs</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item"><a href="index.php?page_layout=staff">Staffs</a></li>
        <li class="breadcumb-item">Add Staff</li>
    </ul>
</section>
<section class="add_page">
    <?php 
        if (!empty($error)) {echo $error;} 
    ?> 
    <form method="post" id="add_staff" class="flex-between" enctype="multipart/form-data">
        <div class="form-item">
            <div class="form-group">
                <label for="staff_full">Full name:</label>
                <input type="text" name="staff_full" id="staff_full" />
                <div class="form-message"></div>
            </div>            
            <div class="form-group">
                <label for="staff_mail">Email:</label>
                <input type="text" name="staff_mail" id="staff_mail" />
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="user_gender">Full name:</label>
                <div id="user_gender" class="flex-start custom-radio">
                    <div class="items-center">
                        <input type="radio" name="gender" id="male" value="0" />
                        <label for="male"><span>Male</span></label>
                    </div>
                    <div class="items-center">
                        <input type="radio" name="gender" id="fe-male" value="1" />
                        <label for="fe-male"><span>Female</span></label>
                    </div>                    
                    <div class="items-center">
                        <input type="radio" name="gender" id="others" value="2" />
                        <label for="others"><span>Others</span></label>
                    </div>
                </div>
                <div class="form-message"></div>
            </div>     
            <div class="form-group">
                <label for="staff_tel">Mobile:</label>
                <input type="tel" name="staff_tel" id="staff_tel" />
                <div class="form-message"></div>
            </div>              
            <div class="form-group">
                <label for="staff_level">Permission:</label>
                <select name="staff_level" id="staff_level">
                    <option value="">----Defaulf Select----</option>
                    <option value="0">Admin</option>
                    <option value="1">Manager</option>
                    <option value="2">Member</option>
                </select>
                <div class="form-message"></div>
            </div>           
            <div class="form-group">
                <label for="staff_birdth">Birdth Day:</label>
                <input type="date" name="staff_birdth" id="staff_birdth" />
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="staff_add">Address:</label>
                <input type="text" name="staff_add" id="staff_add" />
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
                <div id="ava-preview" class="user_preview"></div>
            </div>           
            <div class="form-group">
                <label for="staff_pass">Password:</label>
                <div class="pass-eye">
                    <input type="password" name="staff_pass" id="staff_pass" />
                    <span class="eye-show"><i class="fa fa-eye-slash"></i></span>
                </div>
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="staff_re_pass">Re-Password:</label>
                <div class="pass-eye">
                    <input type="password" name="staff_re_pass" id="staff_re_pass" />
                    <span class="eye-show"><i class="fa fa-eye-slash"></i></span>
                </div>
                <div class="form-message"></div>
            </div>
            <div class="flex-start">
                <button class="btn green" name="sbm" type="submit">ADD</button>
                <button class="btn gray" type="reset">Reset</button>
            </div>
        </div>
    </form>
</section>
