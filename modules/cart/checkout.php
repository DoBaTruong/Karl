<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$queCusTotal  = $conn->query("SELECT cus_mail, cus_pass FROM customers");
$cusArrTotal = [];
while ($custotal = mysqli_fetch_array($queCusTotal)) {
    $cusArrTotal[] = $custotal['cus_mail'] . '-' . $custotal['cus_pass'];
}
if ($cusArrTotal !== []) {
    $cusStrTotal = implode(',', $cusArrTotal);
} else {
    $cusStrTotal = "";
}
?>
<div class="checkout-area mb-100">
    <div class="container">
        <form id="checkoutInfor" method="post" class="row flex-between">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="checkout-details-area">
                    <div class="cart-page-heading">
                        <h5>Billing Address</h5>
                        <p>Enter your cupone code</p>
                    </div>
                    <div class="infor-checkout">
                        <div class="form-group">
                            <label for="full_name">Full Name <span>*</span></label>
                            <input type="text" class="form-control" name="full_name" id="full_name" value="<?php if (!empty($_SESSION['mail'])) {
                                                                                                                echo $ssUser['cus_name'];
                                                                                                            } ?>" />
                            <div class="form-message"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cus_address">Address <span>*</span></label>
                        <input name="cus_add" type="text" class="form-control" id="cus_address" value="<?php if (!empty($_SESSION['mail'])) {
                                                                                                            echo $ssUser['cus_add'];
                                                                                                        } ?>" />
                        <div class="form-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="cus_cupon">Cupone code <span>*</span></label>
                        <input name="cus_cupon" type="text" class="form-control" id="cus_cupon" value="<?php if (!empty($_SESSION['cupon'])) {
                                                                                                            $coupID = explode('-', $_SESSION['cupon'])[0];
                                                                                                            echo  mysqli_fetch_array($conn->query("SELECT * FROM promotions WHERE prm_id = $coupID"))['prm_code'];
                                                                                                        } ?>" />
                        <div class="form-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone No <span>*</span></label>
                        <input name="cus_phone" type="number" class="form-control" id="phone_number" min="0" value="<?php if (!empty($_SESSION['mail'])) {
                                                                                                                        echo $ssUser['cus_phone'];
                                                                                                                    } ?>" />
                        <div class="form-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="email_address">Email Address <span>*</span></label>
                        <input name="cus_mail" type="email" class="form-control" id="email_address" value="<?php if (!empty($_SESSION['mail'])) {
                                                                                                                echo $ssUser['cus_mail'];
                                                                                                            } ?>" />
                        <div class="form-message"></div>
                    </div>
                    <div id="creatElPass" data-customers="<?= $cusStrTotal ?>"></div>
                    <div class="custom-checkbox">
                        <div class="form-group">
                            <input hidden name="agreeTerm" type="checkbox" id="customCheck1" />
                            <label for="customCheck1"><span></span>Terms and conditions</label>
                        </div>
                        <div class="form-group">
                            <input hidden name="createAccount" type="checkbox" id="customCheck2">
                            <label for="customCheck2"><span></span>Create an accout</label>
                        </div>
                        <div class="form-group">
                            <input hidden name="subscribe" type="checkbox" id="customCheck3" />
                            <label for="customCheck3"><span></span>Subscribe to our newsletter</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12">
                <div class="order-details-confirmation">
                    <div class="cart-page-heading">
                        <h5>Your Order</h5>
                        <p>The Details</p>
                    </div>
                    <?php
                    if (!empty($_SESSION['cupon'])) {
                        $cupon = explode('-', $_SESSION['cupon'])[1];
                        $cuponID = explode('-', $_SESSION['cupon'])[0];
                    } else {
                        $cuponID = -1;
                        $cupon = 0;
                    }
                    ?>
                    <ul class="order-details-form">
                        <li><span>Product</span> <span>Total</span></li>
                        <?php
                        $totPr = 0;
                        if (!empty($_SESSION['cart'])) {
                            $count = 0;
                            $prdArrSave = [];
                            foreach ($_SESSION['cart'] as $prd_id => $qty) {
                                $count++;
                                $prodt = mysqli_fetch_array($conn->query("SELECT * FROM product WHERE prd_id = $prd_id"));
                                $price = $prodt['prd_price'] * $qty;
                                $totPr += $price;
                                if (!empty($_SESSION['cartInfor'])) {
                                    $savTmp = explode(';', $_SESSION['cartInfor'][$prd_id]);
                                    $savTmp1 = implode('%', explode(', ', $savTmp[0]));
                                    $savTmp2 = implode('%', explode(',', $savTmp[1]));
                                    $prdArrSave[] = $prd_id . '-' . $qty . '-' . implode('-', [$savTmp1, $savTmp2]);
                                } else {
                                    $prdArrSave[] = $prd_id . '-' . $qty . '-' . trim(explode(',', $prodt['prd_size'])[0]) . '-' . trim(explode(',', $prodt['prd_color'])[0]);
                                }
                        ?>
                                <li><span class="limit-content"><?= $count . '. ' . $prodt['prd_name'] ?></span> <span>$<?= number_format($price, 2, ',', '.') ?></span></li>
                        <?php }
                        } else {
                            echo '<li><span class="limit-content">No product on the cart !</span></li>';
                        } ?>
                        <input hidden type="text" name="ssCart" value="<?= implode(',', $prdArrSave) ?>" />
                        <li>
                            <div>Subtotal</div>
                            <div><span>$<?= number_format($totPr, 2, ',', '.') ?></span></div>
                        </li>
                        <li>
                            <div>Shipping</div>
                            <div id="ship-fee">
                                <input type="text" hidden name="billShipMethod" value="0" />
                                <span>Free</span>
                            </div>
                        </li>
                        <li>
                            <div>Total</div>
                            <div><span id="total_bill"></span><span class="flex-end">(- $<?= $cupon ?>)</span></div>
                        </li>
                        <script>
                            var feeship;
                            var elFeeShip = document.getElementById('ship-fee span');
                            if (sessionStorage['methodShip']) {
                                var tmpfeeship = sessionStorage['methodShip'].split('-')[1];
                                if (tmpfeeship.trim().toLowerCase() === 'free') {
                                    feeship = 0;
                                } else {
                                    feeship = tmpfeeship.split('$')[1];
                                }
                                elFeeShip.querySelector('span').innerText = tmpfeeship;
                                elFeeShip.querySelector('input').value = sessionStorage['methodShip'].split('-')[0].trim();
                            } else {
                                elFeeShip.querySelector('span').innerText = tmpfeeship;
                                elFeeShip.querySelector('input').value = 0;
                                feeship = 0;
                            }
                            if (feeship.toString().indexOf(",") >= 0) {
                                feeship = feeship.split(',').join('.');
                            }
                            var totalPr = ((<?= $totPr ?> * 100 + parseFloat(parseFloat(feeship) * 100)) / 100).toString().split('.').join(',');
                            document.getElementById('total_bill').innerText = '$' + format(totalPr);
                        </script>
                    </ul>

                    <div id="accordition" class="custom-radio">
                        <div class="card">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseOne">
                                <h6 class="flex-start form-group">
                                    <input id="method1" type="radio" name="payMethod" value="0" />
                                    <label for="method1"><span>Paypal</span></label>
                                </h6>
                                <div id="collapseOne" class="collapse">
                                    <div class="card-body">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin
                                            pharetra
                                            tempor so dales. Phasellus sagittis auctor gravida. Integ er
                                            bibendum
                                            sodales arcu id te mpus. Ut consectetur lacus.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseTwo">
                                <h6 class="flex-start form-group">
                                    <input checked id="method2" type="radio" name="payMethod" value="1" />
                                    <label for="method2"><span>cash on delievery</span></label>
                                </h6>
                                <div id="collapseTwo" class="collapse">
                                    <div class="card-body">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo
                                            quis
                                            in veritatis officia inventore, tempore provident dignissimos.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseThree">
                                <h6 class="flex-start form-group">
                                    <input id="method3" type="radio" name="payMethod" value="2" />
                                    <label for="method3"><span>credit card</span></label>
                                </h6>
                                <div id="collapseThree" class="collapse">
                                    <div class="card-body">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse quo
                                            sint
                                            repudiandae suscipit ab soluta delectus voluptate, vero vitae</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseFour">
                                <h6 class="flex-start form-group">
                                    <input id="method4" type="radio" name="payMethod" value="3" />
                                    <label for="method4"><span>direct bank transfer</span></label>
                                </h6>
                                <div id="collapseFour" class="collapse">
                                    <div class="card-body">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est cum
                                            autem
                                            eveniet saepe fugit, impedit magni.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="placeOrder" class="btn">Place Order</button>
                    <script>
                        function SubmitForm() {
                            var formEl = document.getElementById('checkoutInfor');

                            if (formEl) {
                                var createCusEl = formEl.querySelector('input[name=createAccount]');
                                createCusEl.onchange = function() {
                                    var PassPar = formEl.querySelector('#creatElPass');
                                    var chilGr = PassPar.children;
                                    if (createCusEl.checked === true) {
                                        if (chilGr) {
                                            Array.from(chilGr).forEach(function(c) {
                                                PassPar.removeChild(c);
                                            })
                                        }
                                        var mailEl = formEl.querySelector('#email_address');
                                        var dataCuss = PassPar.getAttribute('data-customers');
                                        var dataMail = mailEl.value;
                                        var passMail;
                                        if (dataCuss) {
                                            var dataARR = dataCuss.split(',');
                                            dataARR.forEach(function(data) {
                                                if (dataMail.toLowerCase() === data.split('-')[0].trim().toLowerCase()) {
                                                    passMail = data.split('-')[1].trim();
                                                }
                                            })
                                        }
                                        if (passMail) {
                                            var inCompareEl = CreateEl('input', 'form-control', '', 'pass-compare', 'pass-compare');
                                            inCompareEl.style.display = 'none';
                                            inCompareEl.value = passMail;
                                            PassPar.appendChild(inCompareEl);
                                            createGroEl('pass-oldest', 'pass-oldest', 'Password');
                                            PassPar.setAttribute('data-pass', passMail);
                                        } else {
                                            createGroEl('pass-order', 'Password', 'Password');
                                            createGroEl('rePass-order', 'Re-Password', 'Re-Password')
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
                                            PassPar.appendChild(grel);
                                        }
                                    } else {
                                        var chilPassPar = PassPar.children;
                                        if (chilPassPar) {
                                            Array.from(chilPassPar).forEach(function(child) {
                                                PassPar.removeChild(child)
                                            })
                                        }
                                    }
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
                        SubmitForm()
                    </script>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
if (isset($_POST['cus_mail'])) {
    $mailCus = $_POST['cus_mail'];
    $addr = $_POST['cus_add'];
    $cusname = $_POST['full_name'];
    $phone = $_POST['cus_phone'];
    if (isset($_POST['pass-order'])) {
        $origi = $_POST['pass-order'];
        $pass = md5($origi);
    }
    $orderDate = date('Y-m-d H:i:s');

    if (isset($_POST['ssCart'])) {
        $sscsTR = $_POST['ssCart'];
        $sessCartTmp = explode(',', $sscsTR);
        $payMethod = $_POST['payMethod'];
        $shipMethodBil = $_POST['billShipMethod'];
        $str_body = '
        <p>
            <b>Buyer:</b>' . $cusname . '<br>
            <b>Mobile:</b>' . $phone . '<br>
            <b>Address:</b> ' . $addr . '<br>
        </p>

        <table border="1" cellspacing="0" cellpadding="10" bordercolor="#305eb3" width="100%">
	        <tr bgcolor="rgba(0,0,0,0.1)">
    	        <td width="70%" style="padding: 20px 5px;"><b><font color="#FFFFFF">Product</font></b></td>
                <td width="10%"><center><b><font color="#FFFFFF">Quantity</font></b></center></td>
                <td width="20%"><center><b><font color="#FFFFFF">Amount</font></b></center></td>
            </tr>
        ';
        $total_price = 0;
        for ($i = 0; $i < count($sessCartTmp); $i++) {
            $IDprd = trim(explode('-', $sessCartTmp[$i])[0]);
            $confirmOrder = mysqli_fetch_array($conn->query("SELECT * FROM product WHERE prd_id = $IDprd"));
            $price = trim(explode('-', $sessCartTmp[$i])[1]) * $confirmOrder['prd_price'];
            $total_price += $price;
            $str_body .= '    
            <tr>
                <td width="70%" style="padding: 10px 5px;">
                ' . $confirmOrder['prd_name'] . '
                <div style="padding-top: 10px"><span style="margin-right: 10px">Color: </span>';
            $coloPrd = explode('-', $sessCartTmp[$i])[3];
            if (strpos($coloPrd, '%') >= 0) {
                $arrColor = explode('%', $coloPrd);
                for ($j = 0; $j < count($arrColor); $j++) {
                    $str_body .=
                        '<span style="display: inline-block; margin-right: 10px; height: 20px; width: 20px; border: 1px solid rgba(0,0,0,0.5); background-color:' . trim($arrColor[$j]) . '"></span>';
                }
            } else {
                $str_body .=
                    '<span style="display: inline-block; height: 20px; width: 20px; border: 1px solid rgba(0,0,0,0.5); background-color:' . trim($coloPrd) . '"></span>';
            }
            $str_body .=
                '</div><div style="padding-top: 10px"><span style="margin-right: 10px">Size: </span>';
            $sizPrd = explode('-', $sessCartTmp[$i])[2];
            if (strpos($sizPrd, '%') >= 0) {
                $arrSiz = explode('%', $sizPrd);
                $strSiz = implode(', ', $arrSiz);
                $str_body .=
                    '<span style="display: inline-block; margin-right: 10px;">' . trim($strSiz) . '</span>';
            } else {
                $str_body .=
                    '<span style="display: inline-block; height: 20px; width: 20px; border: 1px solid rgba(0,0,0,0.5); background-color:' . trim($sizPrd) . '"></span>';
            }
            $str_body .=
                '</div></td>
                <td width="10%"><center>' . trim(explode('-', $sessCartTmp[$i])[1]) . '</center></td>
                <td width="20%"><center>$' . number_format($price, 2, ',', '.') . '</center></td>
            </tr>';
        }
        $str_body .= '
            <tr>
                <td colspan="2" width="70%"></td>
                <td width="20%" style="padding: 15px 0;"><center><b><font color="#FF0000">$' . number_format($total_price, 2, ',', '.') . '</font></b></center></td>
            </tr>
        </table>
            <p style="padding-top: 15px">
                Thank you for your purchase at our shop, the delivery department will contact you to confirm after 5 minutes of successful ordering and deliver the goods to you within 24 hours at the latest.
            </p>
            ';
        $customer = $conn->query("SELECT * FROM customers WHERE cus_mail = '$mailCus'");
        if ($customer->num_rows == 0 && isset($_POST['createAccount'])) {
            mysqli_query($conn, "INSERT INTO customers (cus_name, cus_mail, cus_phone, cus_amount, cus_add, cus_pass, cus_original, cus_date) VALUES ('$cusname', '$mailCus', '$phone', $total_price, '$addr', '$pass', '$origi', '$orderDate')");
            $conn->query("INSERT INTO notifications (ntf_infor, ntf_type, ntf_date) VALUES ('?page_layout=customer', 'user', '$orderDate')");
        } elseif ($customer->num_rows > 0) {
            $amount = mysqli_fetch_array($customer)['cus_amount'] + $total_price;
            $conn->query("UPDATE customers SET cus_amount = $amount WHERE cus_mail = '$mailCus'");
        }
        $custoInfor = mysqli_fetch_array($conn->query("SELECT * FROM customers WHERE cus_mail = '$mailCus'"));
        $cusId = $custoInfor['cus_id'];
        $conn->query("INSERT INTO orders (cus_id, order_add, order_phone, order_pay, order_ship, order_date) VALUES ($cusId, '$addr', '$phone', $payMethod, $shipMethodBil, '$orderDate')");
        $ntfInfor = '?page_layout=orders';
        $conn->query("INSERT INTO notifications (ntf_infor, ntf_type, ntf_date) VALUES ('$ntfInfor', 'order', '$orderDate')");
        $orId = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM orders WHERE cus_id = $cusId AND order_date = '$orderDate'"))['order_id'];
        $billTotalprice = 0;
        for ($i = 0; $i < count($sessCartTmp); $i++) {
            $IDprd = trim(explode('-', $sessCartTmp[$i])[0]);
            $bill_qty = trim(explode('-', $sessCartTmp[$i])[1]);
            $bills = mysqli_fetch_array($conn->query("SELECT * FROM product WHERE prd_id = $IDprd"));
            $bill_price = trim(explode('-', $sessCartTmp[$i])[1]) * $confirmOrder['prd_price'];
            $billTotalprice += $bill_price;
            $sizPrd = explode('-', $sessCartTmp[$i])[2];
            if (strpos($sizPrd, '%') >= 0) {
                $arrSiz = explode('%', $sizPrd);
                $strSiz = implode(', ', $arrSiz);
            } else {
                $strSiz = $sizPrd;
            }
            $coloPrd = explode('-', $sessCartTmp[$i])[3];
            if (strpos($coloPrd, '%') >= 0) {
                $arrColor = explode('%', $coloPrd);
                $strColor = implode(',', $arrColor);
            } else {
                $strColor = $coloPrd;
            }
            mysqli_query($conn, "INSERT INTO bills (order_id, prd_id, bill_size, bill_color, bill_qty, bill_price, bill_total) VALUES ($orId, $IDprd, '$strSiz', '$strColor', $bill_qty, $bill_price, $billTotalprice)");
        }
        $mail = new PHPMailer(true);                              // Passing 'true' enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'developer.karlfashion@gmail.com';                 // SMTP username
            $mail->Password = 'karlfashionTt11041998';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, 'ssl' also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('developer.karlfashion@gmail.com', 'Karl Fashion Shop');                // Gửi mail tới Mail Server
            $mail->addAddress($mailCus);               // Gửi mail tới mail người nhận
            $mail->addCC('developer.karlfashion@gmail.com');

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Request a password reset ';
            $mail->Body    = $str_body;
            $mail->AltBody = 'Request a password reset !';

            $mail->send();
            unset($_SESSION['cart']);
            unset($_SESSION['cartInfor']);
            unset($_SESSION['cupon']);
            header('location: index.php?page_layout=myaccount');
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}
?>