<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
if (!empty($_POST['name-ques'])) {
    $name = $_POST['name-ques'];
    $mail = $_POST['mail-ques'];
    $type = $_POST['type-ques'];
    $details = $_POST['details-ques'];
    $date = date('Y-m-d H:i:s');
    $conn->query("INSERT INTO supports (supp_name, supp_mail, supp_type, supp_content, supp_date) VALUES ('$name', '$mail', '$type', '$details', '$date')");
    header('location: index.php');
}
?>

<section class="contact">
    <div class="contact-header mb-100 mt-100 items-center">
        <div class="row">
            <h2>Contact Us</h2>
            <p class="text-center">Karl Fashion - Where put your trust !</p>
        </div>
    </div>
    <div class="container mb-100">
        <div class="row">
            <div class="col-lg-7 col-md-12 col-sm-12 contact-group">
                <form id="contactQuest" method="post">
                    <div class="form-group">
                        <textarea class="ckeditor" name="details-ques" cols="30" rows="10" id="details-ques"></textarea>
                        <div class="form-message"></div>
                    </div>
                    <div class="flex-between">
                        <div class="form-group">
                            <input type="text" name="name-ques" id="name-ques" placeholder="Name*" />
                            <div class="form-message"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="mail-ques" id="mail-ques" placeholder="Mail*" />
                            <div class="form-message"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="type-ques" id="type-ques" placeholder="Type Inquiry*" />
                            <div class="form-message"></div>
                        </div>
                    </div>
                    <button class="btn" name="sbm" type="submit">Send Message</button>
                </form>
            </div>
            <div class="col-lg-5 col-md-12 col-sm-12 contact-group">
                <iframe class="w-100" src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d3725.1501422969095!2d105.85136861488266!3d20.986617786021224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x3135ac1521e6aaab%3A0x809e14beba0f439!2zVklOQVdJTkQsIDE2NCBOZ3V54buFbiDEkOG7qWMgQ-G6o25oLCBUxrDGoW5nIE1haSwgSG_DoG5nIE1haSwgSMOgIE7hu5lpLCBWaeG7h3QgTmFt!3m2!1d20.9865507!2d105.85396039999999!5e0!3m2!1svi!2s!4v1613376380130!5m2!1svi!2s" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0">
                </iframe>
                <div class="contactInfo">
                    <div class="flex-start">
                        <span><i class="fa fa-phone"></i></span> 0988 041 615
                    </div>
                    <div class="flex-start">
                        <span><i class="fa fa-envelope"></i></span> dobatruongbk48@gmail.com
                    </div>
                    <div class="flex-start">
                        <span><i class="fa fa-map"></i></span>
                        <address>Đan Tảo, Tân Minh, Sóc Sơn, Hà Nội</address>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>