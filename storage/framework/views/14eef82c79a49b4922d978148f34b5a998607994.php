

<?php $__env->startSection('metas'); ?>

    <title><?php echo e($event['title']); ?></title>

   <?php echo $event->metable->getMetas(); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo" <script>
   document.getElementById('header').classList.add('header-transparent');
   </script>"?>

<?php $estatus = $event->status ?>

<main id="main-area" class="with-hero" role="main">
   <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "Course",
        "name": "<?php echo $event->title; ?>",
        "description": "<?php echo e($event->subtitle); ?>",
        "award": "Awarded as the 'Best Digital & Social Media Education', by Social Media World in 2016, 2017 and 2018.",
        "inLanguage": "Greek/Ellinika",
        "educationalCredentialAwarded": "EQF 5+ level",
        "author": {
        	"@type": "Person",
        	"name": "Tolis Aivalis"
        },
        "provider": {
          "@type": "Organization",
          "name": "Know Crunch",
          "sameAs": "https://knowcrunch.com/"
        }
      }
   </script>

   <section class="section-hero section-after-tabs-mob" style="background-image: url('<?php echo e(cdn(get_image($event->mediable,'header-image'))); ?>');">
      <div class="overlay"></div>
      <div class="container">
         <div class="hero-message">
            <h1><?php echo e($event->title); ?></h1>
            <h2><?php echo e($event->subtitle); ?></h2>
         </div>
      </div>
      <!-- /.section-hero -->
   </section>

   <section class="section-course-tabs">
      <div class="content-wrapper">
         <div class="tabs-wrapper fixed-tab-controls">
            <div class="tab-controls">
               <div class="container">
                  <a href="#" class="mobile-tabs-menu">Menu</a>
                  <ul class="clearfix tab-controls-list">
                     <li><a href="#overview" class="active">Overview</a></li>
                     <?php if($estatus == 0 || $estatus == 2): ?>

                     <?php if(count($benefits) > 0): ?><li><a href="#benefits">Benefits</a></li><?php endif; ?>

                     <li><a href="#topics">Topics</a></li>
                     <li><a href="#instructors">Instructors</a></li>
                     <li><a href="#testimonials">Testimonials</a></li>
                     <?php if(count($venues) > 0): ?><li><a href="#location">Location</a></li><?php endif; ?>
                     <li><a href="#faq">FAQ</a></li>
                     <?php elseif($estatus == 3 || $estatus == 1 ): ?>
                     <li><a href="#topics">Topics</a></li>
                     <li><a href="#instructors">Instructors</a></li>
                     <?php endif; ?>
                  </ul>

                  <?php if($estatus == 0 && !$is_event_paid): ?>
                  <a href="#seats" class="btn btn--lg btn--primary go-to-href">ENROLL NOW</a>
                  <?php elseif($estatus != 3 && $estatus != 1 && !$is_event_paid): ?>
                  <a href="#seats" class="btn btn--lg btn--primary go-to-href go-to-href soldout">SOLD OUT</a>
                  <?php endif; ?>
                  <!-- /.container -->
               </div>
               <!-- /.tab-controls -->
            </div>
            <div class="tabs-content">
               <div id="overview" class="tab-content-wrapper active-tab">
                  <div class="container">
                     <?php if($estatus == 0 || $estatus == 2): ?>
                     <div class="social-share">
                        <ul class="clearfix">
                           <li class="fb-icon"><a target="_blank" title="Share on facebook" href="http://www.facebook.com/sharer.php?u=<?php echo e(Request::url()); ?>" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=300');return false;"><i class="icon-facebook"></i></a></li>
                           <li class="tw-icon "><a target="_blank" title="Share on Twitter" href="http://twitter.com/share?text=<?php echo e($event->title); ?>&amp;url=<?php echo e(Request::url()); ?>&amp;via=KnowCrunch" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="icon-twitter"></i></a></li>
                           <li class="in-icon"><a target="_blank" title="Share on LinkedIn" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo e(Request::url()); ?>&amp;title=<?php echo e($event->title); ?>

                              &amp;summary=<?php echo e($event->summary); ?>&amp;source=KnowCrunch" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="icon-linkedin"></i></a></li>
                        </ul>
                     </div>
                     <?php endif; ?>
                     <?php
                        $title = '' ;
                        $body = '' ;


                           if(isset($sections['overview'])){
                              $title = $sections['overview']->first()->title;
                              $body = $sections['overview']->first()->description;
                           }

                        ?>

                     <?php switch ($estatus) {
                        case 0:
                        case 2: ?>
                        <h2 class="tab-title"><?php echo e($title); ?></h2>
                     <h3 > <?php echo $body; ?> </h3>
                     <div class="course-overview clearfix">
                        <div class="course-tab-text" itemprop="abstract">
                           <?php echo $event->body; ?>

                           <div class="author-infos">
                              <div class="row">
                                 <?php if(count($syllabus) > 0): ?>
                                 <div class="col6 col-xs-12">
                                    <div id="syll" class="ibox">
                                       <div class="author-img">
                                          <?php
                                             $alt='';
                                             $img = get_image($syllabus[0]['mediable'],'instructors-small'); //$event->mediable->original_name;


                                             ?>
                                          <a id="syllabus-link" href=""><img src="<?php echo e(cdn($img)); ?>" alt="<?php echo e($alt); ?>"></a>
                                       </div>
                                       <div class="ibox-text">


                                          <p>Syllabus Manager<br></p>
                                          <p>
                                             <a href="<?php echo e($syllabus[0]['slugable']['slug']); ?>"><?php echo e($syllabus[0]['title']); ?> <?php echo $syllabus[0]['subtitle']; ?></a>
                                          </p>
                                       </div>
                                    </div>
                                 </div>
                                 <?php endif; ?>
                                 
                              </div>
                           </div>
                           <!-- /.course-overview-text -->
                        </div>
                        <div class="course-tab-sidebar">
                           <div class="course-details <?php if(!isset($section_fullvideo)): ?> non-video-height <?php endif; ?>">
                              <ul class="two-column-list">

                              <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <?php if($sum['title']): ?>
                                 <li>
                                 <?php if($sum['mediable']): ?><img class="info-icon" class="replace-with-svg" src="<?php echo e(cdn(get_image($sum['mediable']))); ?>" width="30" /><?php endif; ?>
                                    <div class="info-text">

                                       <p><?php echo e($sum['title']); ?></br>
                                          <?php echo $sum['description']; ?>

                                       </p>
                                    </div>
                                 </li>
                                 <?php endif; ?>

                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                              </ul>
                              <!-- /.course-details -->
                           </div>
                           <!-- /.course-overview-sidebar -->
                        </div>
                        <!-- /.course-overview.clearfix -->
                     </div>
                     <?php break;
                        case 1:
                        case 3: ?>

                     <div  class="course-overview clearfix padd-bottom">
                        <div class="course-tab-text" itemprop="abstract">

                           <span class="completed">The event is completed</span>
                           <p style="display:none">The best digital &amp; social media diploma with a long track record trusted by top executives, agencies, brands and corporations is completed.</p>
                           <p >Please check all our <?php if(isset($location->name)): ?> <a href="<?php echo e($location->slug); ?>">upcoming events in <?php echo e($location->name); ?></a> <?php else: ?> upcoming events in this city <?php endif; ?>.</p>
                           

                        </div>
                     </div>
                     <?php break;
                        } ?>
                     <?php if($estatus == 0 || $estatus == 2): ?>
                     <?php if(isset($section_fullvideo) && $section_fullvideo->body != ''): ?>
                     <div class="video-wrapper">
                        <div class="responsive-fb-video">
                           <?php echo $section_fullvideo->body; ?>

                        </div>
                     </div>
                     <?php endif; ?>
                     <?php endif; ?>
                     <!-- /.container -->
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>

               <div id="benefits" class="tab-content-wrapper">
                  <div class="course-benefits-text">
                     <div class="container">
                        <?php
                           $title = '' ;
                           $body = '' ;


                           if(isset($sections['benefits'])){
                              $title = $sections['benefits']->first()->title;
                              $body = $sections['benefits']->first()->description;
                           }

                           ?>
                        <h2 class="text-align-center text-xs-left tab-title"><?php echo $title; ?></h2>
                        <h3><?php echo $body; ?></h3>
                        <div class="benefits-list">
                           <div class="row-flex row-flex-17">

                              <?php $__currentLoopData = $benefits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $benefit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <?php if($benefit['name'] != ''): ?>
                                 <div class="col-3 col-sm-6 col-xs-12">
                                    <div class="benefit-box">
                                       <div class="box-icon">
                                          <img class="replace-with-svg" src="<?php echo e(cdn(get_image($benefit['medias']))); ?>" width="40" alt="">
                                       </div>
                                       <h3><?php echo e($benefit['name']); ?></h3>
                                       <?php echo $benefit['description']; ?>

                                       <!-- /.benefit-box -->
                                    </div>
                                 </div>
                                 <?php endif; ?>

                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>


               <div id="topics" class="tab-content-wrapper">
                  <div class="container">
                     <div class="course-full-text">
                        <?php
                                  $title = '';
                                  $body = '';
                           if(isset($sections['topics'])){
                              $title = $sections['topics']->first()->title;
                              $body = $sections['topics']->first()->description;
                           }

                           ?>
                        <h2 class="text-align-center text-xs-left tab-title"> <?php echo $title; ?></h2>
                        <div class="topic-text-area">
                           <?php echo $body; ?>

                        </div>
                        <?php if(isset($topics)): ?>
                        <div class="tab-faq-wrapper topic-content">
                           <div class="accordion-wrapper accordion-big">
                              <?php $__currentLoopData = $topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <div class="accordion-item">
                                 <h3 class="accordion-title title-blue-gradient scroll-to-top"><?php echo $key; ?></h3>
                                 <div class="accordion-content">

                                 <?php $__currentLoopData = $topic['lessons']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lkey => $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <div class="topic-wrapper-big">
                                       <div class="topic-title-meta">
                                          <h4><?php echo $lesson['title']; ?></h4>


                                          <div class="topic-meta">
                                             <?php if(isset($lesson['type'][0]['name'])): ?><div class="category"><?php echo e($lesson['type'][0]['name']); ?></div><?php endif; ?>
                                             <?php
                                                $date = $lesson['pivot']['time_starts'] ? date('l d F Y',strtotime($lesson['pivot']['time_starts'])) : null;
                                                $time =  $lesson['pivot']['time_starts'] ? date('H:i',strtotime($lesson['pivot']['time_starts'])) : null;

                                             ?>
                                             <?php if($date): ?><span class="meta-item duration"><img src="<?php echo e(cdn('/theme/assets/images/icons/icon-calendar.svg')); ?>" width="12" alt="" /><?php echo e($date); ?></span><?php endif; ?>
                                             <?php if($time): ?><span class="meta-item duration"><img src="<?php echo e(cdn('/theme/assets/images/icons/Times.svg')); ?>" width="12" alt="" /><?php echo e($time); ?> (<?php echo e($lesson['pivot']['duration']); ?>)</span><?php endif; ?>
                                             <span class="meta-item duration"><img src="<?php echo e(cdn('/theme/assets/images/icons/icon-marker.svg')); ?>" width="12" alt="" /><?php echo e($lesson['pivot']['room']); ?></span>

                                          </div>
                                          <!-- /.topic-title-meta -->
                                       </div>
                                       <div class="author-img">

                                          <?php

                                             $instructor = reset($instructors[$lesson['instructor_id']]);

                                          ?>
                                          <?php if($instructor['status']): ?>
                                             <a href="<?php echo e($instructor['slugable']['slug']); ?>">
                                             <span class="custom-tooltip"><?php echo e($instructor['title']); ?> <?php echo e($instructor['subtitle']); ?></span>
                                             <img alt="<?php echo e($instructor['title']); ?> <?php echo e($instructor['subtitle']); ?>" src="<?php echo e(cdn(get_image($instructor['mediable'],'instructors-small'))); ?>"/>
                                             </a>
                                          <?php else: ?>
                                             <a class="non-pointer" href="javascript:void(0)">
                                                <span class="custom-tooltip"><?php echo e($instructor['title']); ?> <?php echo e($instructor['subtitle']); ?></span>
                                                <img alt="<?php echo e($instructor['title']); ?> <?php echo e($instructor['subtitle']); ?>" src="<?php echo e(cdn('')); ?>"/>
                                             </a>
                                             <?php endif; ?>
                                       </div>
                                       <!-- /.topic-wrapper -->
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <!-- /.accordion-content -->
                                 </div>
                                 <!-- /.accordion-item -->
                              </div>
                              <!-- /.accordion-wrapper -->
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </div>
                           <!-- /.tab-faq-wrapper -->
                        </div>
                        <?php endif; ?>
                        <!-- /.course-full-text -->
                     </div>
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>

               <div id="instructors" class="tab-content-wrapper tab-blue-gradient">
                  <div class="container">
                     <div class="course-full-text">
                        <?php if(isset($instructors)): ?>
                        <?php
                                  $title = '';
                                  $body = '';
                           if(isset($sections['instructors'])){
                              $title = $sections['instructors']->first()->title;
                              $body = $sections['instructors']->first()->description;
                           }

                           ?>
                        <h2 class="text-align-center text-xs-left tab-title"><?php echo $title; ?></h2>
                        <h3><?php echo $body; ?></h3>
                        <div class="instructors-wrapper row row-flex row-flex-23">
                           <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <?php $__currentLoopData = $instructor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inst): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <?php

                              $socialMedia = json_decode($inst['social_media'],true);
                              $fb = isset($socialMedia['facebook']) ? $socialMedia['facebook'] : '';
                              $twitter = isset($socialMedia['twitter']) ? $socialMedia['twitter'] : '';
                              $instagram = isset($socialMedia['instagram']) ? $socialMedia['instagram'] : '';
                              $linkedIn = isset($socialMedia['linkedin']) ? $socialMedia['linkedin']: '';
                              $yt = isset($socialMedia['youtube']) ? $socialMedia['youtube'] : '';
                                                      
                              $field2 = '';
                              if(isset($inst['ext_url'])){
                              
                                 $field2 = $inst['ext_url'];
                                 $field2 = str_replace ( "https://www.", "", $field2 );
                                 $field2 = str_replace ( "https://.", "", $field2 );
                                 $field2 = str_replace ( "http://www.", "", $field2 );  
                                 $field2 = str_replace ( "https:", "", $field2 );
                                 $field2 = str_replace ( "http:", "", $field2 );
                                 $field2 = str_replace ( "/", "", $field2 );
                              
                              }
                              
                              ?>
                              <div class="col-3 col-md-4 col-sm-6 col-xs-12">
                                 <div class="instructor-box">
                                    <div class="instructor-inner">
                              
                                       <div class="profile-img">
                                         <?php if($inst['status']): ?>
                                             <a href="<?php echo e($inst['slugable']['slug']); ?>"><img src="<?php echo e(cdn(get_image($inst['mediable'],'instructors-testimonials'))); ?>"  title="<?php echo e($inst['title']); ?>" alt="<?php echo e($inst['title']); ?>"></a>
                                         <?php else: ?>
                                             <img src="<?php echo e(cdn(get_image($inst['mediable'],'instructors-testimonials'))); ?>"  title="<?php echo e($inst['title']); ?>" alt="<?php echo e($inst['title']); ?>">
                                         <?php endif; ?>
                                       </div>
                                       <?php if($inst['status']): ?>
                                          <h3><a href="<?php echo e($inst['slugable']['slug']); ?>"><?php echo e($inst['title']); ?> <?php echo e($inst['subtitle']); ?></a></h3>
                                       <?php else: ?>
                                          <h3><?php echo e($inst['title']); ?></h3>
                                       <?php endif; ?>
                                       <p><?php echo e($inst['header']); ?>, <?php if($inst['ext_url'] != ''): ?><a target="_blank" title="<?php echo e($inst['header']); ?>"  href="<?php echo e($inst['ext_url']); ?>"  > <?php echo e($field2); ?></a>.<?php endif; ?></p>
                                       <ul class="social-wrapper">
                                          <?php if($fb != ''): ?>
                                          <li><a target="_blank" href="<?php echo e($fb); ?>"><img class="replace-with-svg"  src="/theme/assets/images/icons/social/Facebook.svg" width="16" alt="Visit"></a></li>
                                          <?php endif; ?>
                                          <?php if($instagram !=''): ?>
                                          <li><a target="_blank" href="<?php echo e($instagram); ?>"><img class="replace-with-svg"  src="<?php echo e(cdn('/theme/assets/images/icons/social/Instagram.svg')); ?>" width="16" alt="Visit"></a></li>
                                          <?php endif; ?>
                                          <?php if($linkedIn !=''): ?>
                                          <li><a target="_blank" href="<?php echo e($linkedIn); ?>"><img class="replace-with-svg"  src="<?php echo e(cdn('/theme/assets/images/icons/social/Linkedin.svg')); ?>" width="16" alt="Visit"></a></li>
                                          <?php endif; ?>
                                          <?php if($twitter !=''): ?>
                                          <li><a target="_blank" href="<?php echo e($twitter); ?>"><img class="replace-with-svg"  src="<?php echo e(cdn('/theme/assets/images/icons/social/Twitter.svg')); ?>" width="16" alt="Visit"></a></li>
                                          <?php endif; ?>
                                          <?php if($yt !=''): ?>
                                          <li><a target="_blank" href="<?php echo e($yt); ?>"><img class="replace-with-svg"  src="<?php echo e(cdn('/theme/assets/images/icons/social/Youtube.svg')); ?>" width="16" alt="Visit"></a></li>
                                          <?php endif; ?>
                              
                                       </ul>
                                       <!-- /.instructor-inner -->
                                    </div>
                                    <!-- /.instructor-box -->
                                 </div>
                                 <!-- /.col-3.col-sm-12 -->
                              </div>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           <?php endif; ?>
                           <!-- /.row.row-flex -->
                        </div>
                        <!-- /.course-full-text -->
                     </div>
                     <!-- /.container -->
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>

               <?php if(count($testimonials) > 0): ?>
               <?php
                        $title = '';
                        $body = '';
                  if(isset($sections['testimonials'])){
                     $title = $sections['testimonials']->first()->title;
                     $body = $sections['testimonials']->first()->description;
                  }

                  ?>
               <div id="testimonials" class="tab-content-wrapper tab-no-pad">
                  <div class="container">
                     <div class="course-full-text full-w-pad">
                        <h2 class="text-align-center text-xs-left tab-title"><?php echo $title; ?></h2>
                        <h3 class="text-align-center text-xs-left tab-title"> <?php echo $body; ?> </h3>
                        <div class="testimonial-carousel-wrapper hidden-xs">

                           <div class="video-carousel-big owl-carousel">
                              <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <?php

                                 if(!$video['video_url']){
                                    continue;
                                 }
                                 $urlArr = explode("/",$video['video_url']);
                                 $urlArrNum = count($urlArr);

                                 // YouTube video ID
                                 $youtubeVideoId = $urlArr[$urlArrNum - 1];

                                 // Generate youtube thumbnail url
                                 $thumbURL = 'https://img.youtube.com/vi/'.$youtubeVideoId.'/mqdefault.jpg';
                                 ?>
                              <div class="slide">
                                 <a data-fancybox href="<?php echo e($video['video_url']); ?>"><img src="<?php echo e($thumbURL); ?>" alt=""/></a>
                              </div>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </div>
                           <!-- /.testimonial-carousel-wrapper -->
                        </div>
                        <!-- /.course-full-text -->
                     </div>
                  </div>
                  <div class="user-testimonial-wrapper">
                     <div class="container">
                        <div class="user-testimonial-big owl-carousel">

                           <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <?php if($row['video_url']): ?>
                                 <?php continue;?>
                           <?php endif; ?>
                           <div class="slide">
                              <div class="testimonial-box">
                                 <div class="author-infos">
                                    <div class="author-img">
                                       <img src="<?php echo e(cdn(get_image($row['mediable'],'users'))); ?>" alt="<?php echo $row['name']; ?>">
                                    </div>
                                    <span class="author-name">
                                    <?php echo $row['name']; ?> <?php echo $row['lastname']; ?></span>
                                    <span class="author-job"><?php echo $row['title']; ?></span>
                                 </div>
                                 <div class="testimonial-text">
                                    <?php
                                          $rev = $row['testimonial'];
                                          $rev = str_replace('"','',$rev);
                                    ?>
                                    <?php echo $row['testimonial']; ?>

                                 </div>
                              </div>
                              <script type="application/ld+json">
														{
														  "@context": "https://schema.org/",
														  "@type": "UserReview",
														  "itemReviewed": {
														    "@type": "Course",
                                                            "provider": "Know Crunch",
														    "image": "",
														    "name": "<?php echo $event->title; ?>",
                                                            "description": "<?php echo $event->subtitle; ?>"
														  },
														  "reviewRating": {
														    "@type": "Rating",
														    "ratingValue": "5"
														  },
														  "name": "<?php echo $event->title; ?>",
														  "author": {
														    "@type": "Person",
														    "name": "<?php echo $row['name']; ?> <?php echo $row['lastname']; ?>"
														  },
														  "reviewBody": "<?php echo $rev; ?>",
														  "publisher": {
														    "@type": "Organization",
														    "name": "KnowCrunch"
														  }
														}
													</script>

                              <!-- /.slide -->
                           </div>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                     </div>
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>
               <?php endif; ?>

               <?php if(count($venues)): ?>
               <div id="location" class="tab-content-wrapper ">
                  <div class="container">
                     <div class="course-full-text">

                        <?php
                                  $title = '';
                                  $body = '';
                           if(isset($sections['location'])){
                              $title = $sections['location']->first()->title;
                              $body = $sections['location']->first()->description;
                           }

                           ?>
                        <h2 class="text-align-center text-xs-left tab-title"><?php echo $title; ?></h2>
                        <h3><?php echo $body; ?></h3>
                        <?php $__currentLoopData = $venues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vkey => $venue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <div class="location-text">
                           <h3><?php echo $venue['name']; ?> </h3>
                           <p><?php echo $venue['address']; ?><br/><br/><br/><?php echo $venue['direction_description']; ?></p>
                        </div>
                        <div class="location-map-wrapper">
                           <iframe  src="https://maps.google.com/maps?q=<?php echo $venue['name']; ?>&z=17&output=embed" width="1144" height="556" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     </div>
                  </div>
               </div>
               <?php endif; ?>
               <div id="faq" class="tab-content-wrapper">

                  <?php
                  $title = '';
                  $body = '';
                     if(isset($sections['questions'])){
                        $title = $sections['questions']->first()->title;
                        $body = $sections['questions']->first()->description;
                     }

                     ?>
                  <div class="container">
                     <div class="course-full-text">
                        <h2 class="text-align-center text-xs-left tab-title"><?php echo $title; ?></h2>
                        <h3 class="text-align-center text-xs-left tab-title"><?php echo $body; ?></h3>

                        <?php if(count($faqs) > 0): ?>

                        <?php $f=[] ?>
                        <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <h3 class="tab-sub-title"><?php echo $key; ?></h3>
                        <div class="tab-faq-wrapper multiple-accordions">
                           <div class="accordion-wrapper">


                              <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qkey => $qna): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <?php $questions = [];?>

                              <div class="accordion-item">
                                 <h4 class="accordion-title title-blue-gradient scroll-to-top"><?php echo $qna['question']; ?></h4>
                                 <div class="accordion-content">
                                    <div class="shorten-content">
                                    <?php echo $qna['answer']; ?>

                                    </div>
                                 </div>
                                 <!-- /.accordion-item -->
                              </div>


                              <?php
                                 $qq = [];
                                 $title = $qna['question'];
                                 $quest = $qna['answer'];

                                 $questions['@type'] = "Question";
                                 $questions['name'] = $title;
                                 $qq["@type"] = "Answer";
                                 $qq["text"] = $quest;

                                 $questions["acceptedAnswer"] = $qq;//json_encode($qq);

                                 $f[]= $questions; //json_encode($questions);


                              ?>

                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>






                              <!-- /.accordion-wrapper -->
                           </div>
                           <!-- /.tab-faq-wrapper -->
                        </div>


                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php $f=json_encode($f);?>
                        <script type="application/ld+json">
                           {
                              "@context": "https://schema.org",
                              "@type": "FAQPage",
                              "mainEntity": <?php echo $f; ?>

                              }
									</script>
                        <?php endif; ?>
                        <!-- /.course-full-text -->
                     </div>

                  </div>
                  <!-- /.tab-content-wrapper -->

               </div>


               <!-- /.tabs-content -->
            </div>
            <!-- /.tabs-wrapper -->
         </div>
         <!-- /.content-wrapper -->
      </div>
      <!-- /.section-course-tabs -->
   </section>

   <?php if(($estatus == 0 || $estatus == 2) && !$is_event_paid): ?>
   <?php echo $__env->make('theme.event.partials.seats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <?php endif; ?>
</main>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('theme.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/event/event.blade.php ENDPATH**/ ?>