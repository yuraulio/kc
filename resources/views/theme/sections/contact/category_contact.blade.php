@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')

<div id="page-banner">
    <?php  if (isset($categoryBanner) && count($categoryBanner) > 0) : ?>

    <?php foreach ($categoryBanner as $key => $value) : ?>
        <img alt="About" src="/uploads/categories/<?php echo $value['catid'] . '/' . $value['truefilename']; ?>" class="img-responsive center-block" />
    <?php endforeach; ?>

    <?php else : ?>

        <img alt="" src="assets/img/banners/CONTACT.jpg" class="img-responsive center-block" />

    <?php endif; ?>

    <!-- <div class="container" id="banner-caption">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="introtextpages-center animatable fadeInUp">Get in touch with us</h3>
            </div>
        </div>
    </div> -->
</div>

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-5 col-sm-6">
                <div class="tabs-sidebar-menu">
                    <h1 class="contact-side-head">CYPRUS</h1>
                    <strong>European Head Office</strong>
                    <p>
                       80 Arch. Makarios III Avenue, 5th Floor, Suite 500,
                        1077 Nicosia - CYPRUS<br />
                        Tel: +357 7000 2555<br />
                        Fax: +357 7000 2333<br />
                    </p>
                    <p style="text-align:center; margin: 45px 0 0;">
                    <a class="btn btn-default-or" href="#" data-lat="35.1626611,33.3635446" data-toggle="modal" data-target="#myMapModal">
                        VIEW ON MAP
                    </a>
                    </p>

                </div>
                <div class="tabs-sidebar-menu">
                    <h1 class="contact-side-head">UNITED KINGDOM</h1>
                    <strong>London Office</strong>
                    <p>
                        New Bond House, 124 New Bond Street, London,
                        W1S 1DX
                    </p>
                    <p style="text-align:center; margin: 45px 0 0;">
                    <a class="btn btn-default-or" href="#" data-lat="51.5125633,-0.1471403" data-toggle="modal" data-target="#myMapModal">
                        VIEW ON MAP
                    </a>
                    </p>

                </div>

                <div class="tabs-sidebar-menu">
                    <h1 class="contact-side-head">CHINA</h1>
                    <strong>Asia Office</strong>
                    <p>
                        15/F, Effectual Building, 16, Hennessy Road, Wanchai,
                        Hong Kong
                    </p>
                    <p style="text-align:center; margin: 45px 0 0;">
                    <a class="btn btn-default-or" href="#" data-lat="22.2775544,114.1664688" data-toggle="modal" data-target="#myMapModal">
                        VIEW ON MAP
                    </a>
                    </p>

                </div>
            </div>
            <div class="col-lg-8 col-md-7 col-sm-6">

                <h2>Contact us</h2>

                <form id="doall" class="contactUsForm" method="POST" action="">

                    <div class="row form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <input class="form-control textboxF" id="name" name="name" placeholder="NAME" required="required" type="text" value="<?php if (isset($form['name'])) : echo $form['name']; endif; ?>">
                            <input class="form-control textboxF" id="company" name="company" placeholder="COMPANY" required="required" type="email" value="<?php if (isset($form['email'])) { echo $form['email']; } ?>">

                            <input class="form-control textboxF" id="email" name="email" placeholder="EMAIL" required="required" type="email" value="<?php if (isset($form['email'])) { echo $form['email']; } ?>">
                            <input class="form-control textboxF" id="phone" name="phone" placeholder="PHONE" required="required" type="phone" value="<?php if (isset($form['phone'])) { echo $form['phone']; } ?>">

                            <textarea class="form-control textboxF" placeholder="MESSAGE" name="message" rows="10" id="comment"><?php if (isset($form['message'])) { echo $form['message']; } ?></textarea>
                        </div>
                    </div>
                    <input class="fill_me_up" name="fill_me_up" placeholder="" type="text" value="" tabindex="-1" />
                      <p class="mobileR">
                      <a id="sendme" class="btn btn-default-or contactUsSubmit">SUBMIT</a>
                      </p>
                    <div id="errors"></div>
                    <div id="result">
                        <div id="value" class="contactUsReponse"></div>
                    </div>
                    <div class="boxLoading"></div>

                </form>

                  <span id="loadingDiv"><img class="img-responsive center-block" src="assets/img/ajax-loader.gif" /></span>
                  <div id="success"></div>


            </div>
        </div>
    </div>
</div>

@include('theme.home.partials.newsletter')
@endsection
