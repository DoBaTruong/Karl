<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

if (isset($_POST['subChangeInforCus'])) {
    $mailold = $_SESSION['mail'];
    $newmail = $_POST['pf_mail'];
    $newname = $_POST['pf_name'];
    $newtel = $_POST['pf_tel'];
    $newadd = $_POST['pf_add'];
    $newbirdth = $_POST['pf_birdth'];
    $newsex = $_POST['pf_sex'];
    if ($_FILES['pf_avata']['name'] === "") {
        $newava = $ssUser['cus_image'];
    } else {
        if ($ssUser['cus_image']) {
            $unlinkURL = 'admin/images/avata/' . $ssUser['cus_image'];
            if (file_exists($unlinkURL)) {
                unlink($unlinkURL);
            }
        }
        $avaextension = trim(explode('/', $_FILES['pf_avata']['type'])[1]);
        $newavaTMP = 'cus_' . convert_name(join('_', explode(' ', $newname)));
        $newava = mb_strtolower($newavaTMP . '.' . $avaextension);
        $url = 'admin/images/avata/' . $newava;
        if (file_exists($url)) {
            $cre = 0;
            while (file_exists($url)) {
                $newava = mb_strtolower($newavaTMP . '-' . $cre . '.' . $avaextension);
                $url = 'admin/images/avata/' . $newava;
            }
        }
        move_uploaded_file($_FILES['pf_avata']['tmp_name'], $url);
    }
    $status = $conn->query("UPDATE customers SET cus_name = '$newname', cus_gender = $newsex, cus_image='$newava', cus_mail = '$newmail', cus_add = '$newadd', cus_phone = '$newtel', cus_birdth = '$newbirdth' WHERE cus_mail = '$mailold'");
    if ($status) {
        $_SESSION['mail'] = $newmail;
    }
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$row_per_page = 2;
$per_row = $page * $row_per_page - $row_per_page;
$toltal_row = mysqli_num_rows($conn->query("SELECT * FROM orders WHERE cus_id = $cusId"));
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-item"><a class="page-link" href="index.php?page_layout=myaccount&page=' . $prev_page . '#comm-details">&laquo;</a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-item ' . $active . '"><a class="page-link" href="index.php?page_layout=myaccount&page=' . $i . '#comm-details">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-item"><a class="page-link" href="index.php?page_layout=myaccount&page=' . $next_page . '#comm-details">&raquo;</a></li>';
}
?>
<section class="new-arrivals-area myaccount mtb-100">
    <div class="container">
        <div id="profileofcus">
            <div class="acc-head">
                <h3>My Account <?php if (isset($_SESSION['mail'])) { ?><span class="edit-badge"><i class="fa fa-edit"></i></span><?php } ?></h3>
                <p>Manage profile information for account security.</p>
                <button type="button" id="buttonResetPasCus" class="reset-pascus">Re-Password</button>
            </div>
            <?php
            if (isset($_SESSION['mail'])) {
                $ssUserNew = mysqli_fetch_array($conn->query("SELECT * FROM customers WHERE cus_mail = '" . $_SESSION['mail'] . "'"));
                $cusID = $ssUserNew['cus_id'];
                $cusname = $ssUserNew['cus_name'];
                $cusmail = $ssUserNew['cus_mail'];
                $custel = $ssUserNew['cus_phone'];
                $cussex = $ssUserNew['cus_gender'];
                $cusbirdth = $ssUserNew['cus_birdth'];
                $pass = $ssUserNew['cus_original'];
                $cusadd = $ssUserNew['cus_add'];
                if (trim($ssUserNew['cus_image']) !== "") {
                    $cusava = $ssUserNew['cus_image'];
                } else {
                    $cusava = 'avatar-default.png';
                }
            } else {
                $cusname = $cusmail = $custel = $cussex = $cusbirdth = $cusadd = 'No information !';
                $cusava = 'avatar-default.png';
            }
            ?>
            <form method="post" class="w-100" id="profileofform" enctype="multipart/form-data">
                <table class="table acc-table">
                    <tbody>
                        <tr>
                            <td>Full Name</td>
                            <td>:</td>
                            <td><input disabled type="text" name="pf_name" value="<?= $cusname ?>" /></td>
                            <td rowspan="4" class="pro-ava">
                                <div id="preview-ava-profile">
                                    <img src="admin/images/avata/<?= $cusava ?>" alt="" />
                                </div>
                                <input hidden type="file" name="pf_avata" id="pf_avatar" />
                                <input class="btn" onclick="this.parentElement.querySelector('#pf_avatar').click()" type="button" value="Avatar" />
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td><input disabled type="email" name="pf_mail" value="<?= $cusmail ?>" /></td>
                        </tr>
                        <tr>
                            <td>Mobile</td>
                            <td>:</td>
                            <td><input disabled type="text" name="pf_tel" value="<?= $custel ?>" /></td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td>:</td>
                            <td class="flex-start">
                                <div class="custom-radio">
                                    <input <?php if ($cussex == 0) {
                                                echo 'checked';
                                            } ?> hidden id="male" type="radio" name="pf_sex" value="0" />
                                    <label for="male"><span>Male</span></label>
                                </div>
                                <div class="custom-radio">
                                    <input <?php if ($cussex == 1) {
                                                echo 'checked';
                                            } ?> hidden id="fmale" type="radio" name="pf_sex" value="1" />
                                    <label for="fmale"><span>Female</span></label>
                                </div>
                                <div class="custom-radio">
                                    <input <?php if ($cussex == 2) {
                                                echo 'checked';
                                            } ?> hidden id="omale" type="radio" name="pf_sex" value="2" />
                                    <label for="omale"><span>Others</span></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Birdth Day</td>
                            <td>:</td>
                            <td><input disabled type="date" name="pf_birdth" value="<?= $cusbirdth ?>" /></td>
                            <td rowspan="2"></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>:</td>
                            <td><input disabled type="text" name="pf_add" value="<?= $cusadd ?>" /></td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex-end">
                    <button type="submit" name="subChangeInforCus" class="btn">SAVE</button>
                </div>
            </form>
            <script>
                var formEl = document.getElementById('profileofcus');
                if (formEl) {
                    var editBut = formEl.querySelector('.edit-badge');
                    if (editBut) {
                        editBut.onclick = function() {
                            var inpEls = formEl.querySelectorAll('input[name]');
                            if (inpEls) {
                                Array.from(inpEls).forEach(function(inp) {
                                    inp.disabled = false;
                                })
                                var inpuFile = formEl.querySelector('input[type=file]');
                                if (inpuFile) {
                                    inpuFile.onchange = function() {
                                        var previewImage = formEl.querySelector('#preview-ava-profile img');
                                        if (previewImage) {
                                            var reader = new FileReader();
                                            reader.onload = function(e) {
                                                previewImage.src = e.target.result;
                                            }
                                            reader.readAsDataURL(inpuFile.files[0])
                                        }
                                    }
                                }
                                formEl.querySelector('input[type=button].btn').style.opacity = '1';
                                var subBut = formEl.querySelector('button[type=submit].btn');
                                var trForms = formEl.querySelectorAll('tr');
                                if (trForms) {
                                    Array.from(trForms).forEach(function(tr) {
                                        if (tr.querySelector('input[name=edit-pass-confirm]')) {
                                            formEl.querySelector('table tbody').removeChild(tr);
                                        }
                                    })
                                }
                                var passTrConfirm = document.createElement('tr');
                                var tdLabelPass = document.createElement('td');
                                tdLabelPass.innerText = 'Enter Your Password';
                                passTrConfirm.appendChild(tdLabelPass);
                                var tdTwoPass = document.createElement('td');
                                tdTwoPass.innerText = ':';
                                passTrConfirm.appendChild(tdTwoPass);
                                var passTdConfirm = document.createElement('td');
                                var inpPassEl = document.createElement('input');
                                inpPassEl.type = 'password';
                                inpPassEl.name = 'edit-pass-confirm';
                                passTdConfirm.appendChild(inpPassEl);
                                passTrConfirm.appendChild(passTdConfirm);
                                formEl.querySelector('table tbody').appendChild(passTrConfirm);
                                inpPassEl.onblur = function() {
                                    if (inpPassEl.value == <?= $pass ?>) {
                                        passTrConfirm.style.color = '#3a3a3a';
                                        inpPassEl.style.backgroundColor = '#f4f2f8'
                                        subBut.style.display = 'block';
                                    } else {
                                        passTrConfirm.style.color = 'red';
                                        inpPassEl.style.backgroundColor = 'red'
                                    }
                                }

                                inpPassEl.oninput = function() {
                                    passTrConfirm.style.color = '#3a3a3a';
                                    inpPassEl.style.backgroundColor = '#f4f2f8'
                                }
                            }

                        }
                    }
                    var resetPasBut = document.getElementById('buttonResetPasCus');
                    if (resetPasBut) {
                        resetPasBut.onclick = function() {
                            var formSubEl = formEl.querySelector('#profileofform');
                            if (formSubEl) {
                                Array.from(formSubEl.children).forEach(function(chil) {
                                    formSubEl.removeChild(chil);
                                })
                                createGroEl('pass-oldest', 'pass-oldest', 'Password Oldest');
                                createGroEl('pass-new', 'pass-new', 'Password New');
                                createGroEl('re-pass-new', 're-pass-new', 'Re-Password');
                                var contanBut = document.createElement('div');
                                contanBut.classList.add('flex-end');
                                var buttEl = document.createElement('button');
                                buttEl.type = 'submit';
                                buttEl.innerText = 'CONFIRM';
                                buttEl.classList.add('btn', 'resetpasBut');
                                contanBut.appendChild(buttEl);
                                formSubEl.appendChild(contanBut);
                                var inputPasss = formSubEl.querySelectorAll('input[type=password]');
                                if (inputPasss) {
                                    Array.from(inputPasss).forEach(function(pasEl) {
                                        pasEl.onblur = function() {
                                            if (pasEl.id === 'pass-new') {
                                                if (pasEl.value.length < 6) {
                                                    getParent(pasEl, '.form-group').classList.add('invalid');
                                                    getParent(pasEl, '.form-group').querySelector('.form-message').innerHTML = 'Please enter at least 6 characters !';
                                                }
                                            }
                                            if (pasEl.id === 're-pass-new') {
                                                if (pasEl.value !== formSubEl.querySelector('#pass-new').value) {
                                                    getParent(pasEl, '.form-group').classList.add('invalid');
                                                    getParent(pasEl, '.form-group').querySelector('.form-message').innerHTML = 'Entered password is incorrect !';
                                                }
                                            }
                                            if (pasEl.id === 'pass-oldest') {
                                                if (MD5(pasEl.value) !== '<?= $ssUserNew['cus_pass'] ?>') {
                                                    getParent(pasEl, '.form-group').classList.add('invalid');
                                                    getParent(pasEl, '.form-group').querySelector('.form-message').innerHTML = 'Entered password is incorrect !';
                                                }
                                            }
                                        }
                                        pasEl.oninput = function() {
                                            getParent(pasEl, '.form-group').classList.remove('invalid');
                                            getParent(pasEl, '.form-group').querySelector('.form-message').innerText = '';
                                        }
                                        pasEl.onfocus = function() {
                                            getParent(pasEl, '.form-group').classList.remove('invalid');
                                            getParent(pasEl, '.form-group').querySelector('.form-message').innerHTML = ''
                                        }
                                    })
                                }
                            }

                            function createGroEl(idEl, nameInEl, texEl) {
                                var grel = CreateEl('div', 'form-group', '', '', '');
                                var label = CreateEl('label', '', texEl, '', '');
                                grel.appendChild(label);
                                var eyeGrEl = CreateEl('div', 'pass-eye', '', '', '');
                                var inpEl = CreateEl('input', 'form-control', '', idEl, nameInEl);
                                inpEl.type = 'password';
                                eyeGrEl.appendChild(inpEl);
                                var eyeEl = CreateEl('span', 'eye-show', '', '', '');
                                var iconEl = CreateEl('i', 'fa', '', '', '');
                                iconEl.classList.add('fa-eye-slash');
                                eyeEl.appendChild(iconEl);
                                eyeGrEl.appendChild(eyeEl);
                                grel.appendChild(eyeGrEl);
                                var messEl = CreateEl('div', 'form-message', '', '', '');
                                grel.appendChild(messEl);
                                formSubEl.appendChild(grel);
                            }

                            function CreateEl(tagElName, classElName, textEl, idEl, nameEl) {
                                var elCr = document.createElement(tagElName);
                                if (classElName.trim() !== '') {
                                    elCr.classList.add(classElName);
                                }
                                if (textEl.trim() !== '') {
                                    const teEl = document.createTextNode(textEl);
                                    elCr.appendChild(teEl);
                                }
                                if (idEl.trim() !== '') {
                                    elCr.id = idEl;
                                }
                                if (nameEl.trim() !== '') {
                                    elCr.name = idEl;
                                }
                                return elCr;
                            }
                        }
                    }
                }
            </script>
            <?php
            if (isset($_POST['pass-new'])) {
                $mailPass = $_SESSION['mail'];
                $orgiNew = $_POST['pass-new'];
                $passNew = md5($orgiNew);
                $conn->query("UPDATE customers SET cus_pass = '$passNew', cus_original = '$orgiNew' WHERE cus_mail = '$mailPass'");
            }
            ?>
        </div>
        <div id="orderInforCus">
            <div class="acc-head">
                <h3>MY ORDERS</h3>
                <p>Follow information orders.</p>
            </div>
            <?php
            $orderCusQue = $conn->query("SELECT * FROM orders WHERE cus_id = $cusId ORDER BY order_status ASC LIMIT ".$per_row.','.$row_per_page);
            while ($orderCus = mysqli_fetch_array($orderCusQue)) {
                if ($orderCus['prm_id'] > 0) {
                    $prmDiscount = mysqli_fetch_array($conn->query("SELECT * FROM promotions WHERE prm_id = '" . $orderCus['prm_id'] . "'"));
                    $prdDiscount = explode(',', $prmDiscount['prm_apply']);
                    $billDiscount = $prmDiscount['prm_percent'];
                } else {
                    $prdDiscount = '';
                    $billDiscount = 0;
                }
                $totalPriceOrder = 0;
            ?>
                <div class="order-group">
                    <div class="order-item">
                        <div class="order-head flex-between">
                            <div>Code Order: <?= mb_strtoupper(substr(md5($orderCus['order_id']), 0, 14)) ?></div>
                            <div class="infor-delivery">
                                <span><i class="fa 
                            <?php
                            switch ($orderCus['order_status']) {
                                case '0':
                                    echo 'fa-archive';
                                    break;
                                case '1':
                                    echo 'fa-shipping-fast';
                                    break;
                                case '2':
                                    echo 'fa-truck-loading';
                            }
                            ?>
                            "></i></span>
                                <span>
                                    <?php
                                    switch ($orderCus['order_status']) {
                                        case '0':
                                            echo 'processing';
                                            break;
                                        case '1':
                                            echo 'Shipping';
                                            break;
                                        case '2':
                                            echo 'Deliveried';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                        <?php
                        $billOrderCusQue = $conn->query("SELECT * FROM bills WHERE order_id = '" . $orderCus['order_id'] . "'");
                        while ($billOrderCus = mysqli_fetch_array($billOrderCusQue)) {
                            $totalPriceOrder += $billOrderCus['bill_total'];
                            $prdBill = mysqli_fetch_array($conn->query("SELECT * FROM product WHERE prd_id = '" . $billOrderCus['prd_id'] . "'"));
                        ?>
                            <div class="order-details flex-between">
                                <div class="order-image">
                                    <img src="admin/images/product/<?= $prdBill['prd_image'] ?>" alt="" />
                                </div>
                                <div class="order-infor flex-between">
                                    <div class="infor-basic">
                                        <h4><?= $prdBill['prd_name'] ?></h4>
                                        <p class="order-color">Color:
                                            <?php
                                            if (strpos($billOrderCus['bill_color'], ',') >= 0) {
                                                $arrColorBill = explode(', ', $billOrderCus['bill_color']);
                                            } else {
                                                $arrColorBill = [$billOrderCus['bill_color']];
                                            }
                                            for ($i = 0; $i < count($arrColorBill); $i++) {
                                            ?>
                                                <span style="background-color: <?= $arrColorBill[$i] ?>;"></span>
                                            <?php } ?>
                                        </p>
                                        <p>size: <span><?= $billOrderCus['bill_size'] ?></span></p>
                                        <p>x<?= $billOrderCus['bill_qty'] ?></p>
                                    </div>
                                    <div class="items-center">
                                        <?php
                                        if ($prdDiscount !== '') {
                                            if (in_array($billOrderCus['prd_id'], $prdDiscount)) {
                                        ?>
                                                <span class="price-oldest">$<?= number_format($prdBill['prd_price'], 2, ',', '.'); ?></span><span class="price-new ml-3"><?= number_format(($prdBill['prd_price'] * (100 - $billDiscount) / 100), 2, ',', '.'); ?></span>
                                            <?php } else { ?>
                                                <span class="price-new">$<?= number_format($prdBill['prd_price'], 2, ',', '.'); ?></span>
                                            <?php }
                                        } else { ?>
                                            <span class="price-new">$<?= number_format($prdBill['prd_price'], 2, ',', '.'); ?></span>
                                        <?php }  ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="order-price-total flex-end">
                        <p>Total Amount: <span>$<?= number_format($totalPriceOrder, 2, ',', '.') ?></span></p>
                    </div>
                </div>
            <?php } ?>

            <?php
            if ($toltal_row > $row_per_page) {
            ?>
                <ul class="shop-pagination flex-end">
                    <?php echo $list_page; ?>
                </ul>
            <?php }
            ?>
        </div>
</section>