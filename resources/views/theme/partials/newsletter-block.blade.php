<!-- NEWSLETTER block 930x185-->
<div class="newsletter-section">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <img class="img-responsive center-block" alt="Post Title Here" src="theme/assets/img/newsletter_logo.png" />
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="newsletter-title">...για να είστε ενημερωμένοι</div>
            <div class="newsletterFormArea">
                <form action="" method="POST" class="newsletter_form">
                    <input type="email" name="email" class="textboxH" placeholder="συμπληρώστε το email σας" />
                    <input type="hidden" id="current_url_input" name="current_url" value="{{ Request::url() }}" />
                    <input type="hidden" name="lang" value="el" />
                    <button type="button" class="btn btn-newsletter" title="ΑΠΟΣΤΟΛΗ" id="applyToNewsletter">Αποστολή</button>
                </form>
                <div class="newsletterReponse"></div>
            </div>
        </div>
    </div>
</div>
