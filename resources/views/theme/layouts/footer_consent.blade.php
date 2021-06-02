<!-- <a href="#0" class="cd-top"><i class="fa fa-chevron-up"></i></a> -->
<!-- FOOTER -->
<footer id="footer" class="wrap fixed-bottom" style="position: fixed; bottom: 0; left: 0; right: 0; padding-top: 10px; min-height: 143px;">

    <div id="footer-primary">
        <div class="container">
            <form id="acceptconsent" method="POST" action="" class="nbeForm">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group text-center">
                        <div id="botview">
                        <div class="form-group">
                <label for="accept"><input id="accept" type="checkbox">
                              I have read, agree &amp; accept the <a class="termlink" target="_blank" title="View the data privacy policy" href="/data-privacy-policy">data privacy policy</a> above.</label>
            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="text-center">
                            <button title="I DO NOT AGREE" class="btn btn--lg btn--secondary btn--completed conotSubmit" id="conotSubmit">I DO NOT AGREE</button>

                            <button title="I AGREE AND CONTINUE" class="btn btn--lg btn--secondary btn--completed coSubmit" id="coSubmit">I AGREE, AND CONTINUE</button>

                            <div class="errorReponse"></div>

                            <!-- <br /> -->
                            <div id="loadingDivN"><img class="img-responsive" src="theme/assets/img/ajax-loader-blue.gif" /></div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

</footer>


</html>
