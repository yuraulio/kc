@if(isset($folders) && count($folders) > 0)
<?php $bonusFiles = ['_Bonus', 'Bonus', 'Bonus Files', 'Βonus', '_Βonus', 'Βonus', 'Βonus Files'] ?>

                              <div class="acc-topic-accordion">
                                 <div class="accordion-wrapper accordion-big">
                                    @foreach($folders as $catid => $dbfolder)
                        
                                    
                                    @if (isset($dbfolder[0]) && !empty($dbfolder[0]))
                                    @foreach($dbfolder[0] as $key => $folder)
                                    <?php
                                       $rf = strtolower($folder['dirname']);
                                       $rf1 = $folder['dirname']; //newdropbox
                                       ?>
                                    <?php  
                                       $topic=1; 
                                       if($instructor_topics){ 
                                          $topic=0;
                                          
                                          if((trim($folder['foldername']) === '1 - Prelearning - Digital & Social Media Fundamentals')
                                                   && in_array(trim('Pre-learning: Digital & Social Media Fundamentals'), $instructor_topics)){
                                             
                                             $topic = 1;
                                          }else{
                                             $topic_name = explode( '-', $folder['foldername'] );  
                                             $topic=in_array(trim($topic_name[1]), $instructor_topics); 
                                       }   }
                                       ?>
                                    @if($topic)
                                    <div class="accordion-item">
                                       <h3 class="accordion-title title-blue-gradient scroll-to-top"> {{ $folder['foldername'] }}</h3>
                                       <!-- Feedback 01-12 changed -->
                                       <div class="accordion-content no-padding">
                                       <?php    
                                          $checkedF = []; 
                                          $fs = [];
                                          $fk = 1;
                                          $bonus = [];
                                          $subfolder = false;
                                       ?>
                                          
                                          @if (isset($files[$catid][1]) && !empty($files[$catid][1]))
                                    
                                          @foreach($files[$catid][1] as $fkey => $frow2)

                                          @if($frow2['fid'] == $folder['id'])
                                  

                                          <?php 
                                            
                                             $fn = $folder['foldername'];
                                             
                                             if(isset($dbfolder[1]) && !empty($dbfolder[1])){
                                                foreach($dbfolder[1] as $nkey => $nfolder){
                                                   $dirname = explode('/',$nfolder['dirname']);
                                                   if($nfolder['parent'] == $folder['id'] && in_array($fn,$dirname) && !$subfolder  && !in_array($nfolder['foldername'],$bonusFiles)/*$nfolder['foldername'] != '_Bonus' && $nfolder['foldername'] != 'Bonus'*/){
                                                      
                                                      $checkedF[] = $nfolder['id'] + 1 ;
                                                      $fs[$nfolder['id']+1]=[];
                                                      $fs[$nfolder['id']+1][] = $nfolder;
                                                      
                                                   }
                                                }
                                             }
                                             
                                             if(count($fs) > 0 ){
                                                $subfolder = true;
                                             }

                                          ?>
                                         
                                             @if($subfolder && in_array($fk,$checkedF))

                                                @while(in_array($fk,$checkedF))
                                                      <?php 

                                                         $sfv = reset($checkedF);
                                                         $sfk = array_search($sfv, $checkedF);
                                                         unset($checkedF[$sfk]);
                                                      ?>


                                                      @if (isset($dbfolder[1]) && !empty($dbfolder[1]))
                                                         @foreach($dbfolder[1] as $nkey => $nfolder)
                                                             @if($nfolder['id'] == $fs[$sfv][0]['id'] && !in_array($nfolder['foldername'],$bonusFiles)/*$nfolder['foldername'] != '_Bonus' && $nfolder['foldername'] != 'Bonus'*/) <!--//lioncode-->

                                                               <div class="files-wrapper bonus-files">
                                                                  <h4 class="bonus-title">{{ $nfolder['foldername'] }}</h4>
                                                                  <span><i class="icon-folder-open"></i>   </span>
                                                                  @if (isset($files[$catid][2]) && !empty($files[$catid][2]))
                                                                     @foreach($files[$catid][2] as $fkey => $frow)
                                                                        @if (strpos($frow['dirname'], $rf) !== false || strpos($frow['dirname'], $rf1) !== false && ( $frow['fid'] == ($sfv-1)  ))
                                                                        <?php $bonus[]= $frow['filename'] ?>    
                                                                           <div class="file-wrapper">
                                                                              <h4 class="file-title">{{ $frow['filename'] }}</h4>
                                                                              <span class="last-modified">Last modified:  {{$frow['last_mod']}}</span>
                                                                              <a  class="download-file getdropboxlink"  data-dirname="{{ $frow['dirname'] }}" data-filename="{{ $frow['filename'] }}" href="javascript:void(0)" >
                                                                              <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                                                           </div>
                                                                           @endif
                                                                     @endforeach

                                                                  @endif
                                                               </div>
                                                            @endif   
                                                         @endforeach
                                                      @endif
                                                      <!-- bonus of each lesson -->
                                      
                                                   <?php $fk += 1;?>

                                                @endwhile
                                                <div class="files-wrapper">
                                                   <div class="file-wrapper">
                                                      <h4 class="file-title">{{ $frow2['filename'] }}</h4>
                                                      <span class="last-modified">Last modified:  {{$frow2['last_mod']}}</span>
                                                      <a  class="download-file getdropboxlink"  data-dirname="{{ $frow2['dirname'] }}" data-filename="{{ $frow2['filename'] }}" href="javascript:void(0)" >
                                                      <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                                   </div>
                                                </div>
                                             @else
                                                <div class="files-wrapper">
                                                   <div class="file-wrapper">
                                                      <h4 class="file-title">{{ $frow2['filename'] }}</h4>
                                                      <span class="last-modified">Last modified:  {{$frow2['last_mod']}}</span>
                                                      <a  class="download-file getdropboxlink"  data-dirname="{{ $frow2['dirname'] }}" data-filename="{{ $frow2['filename'] }}" href="javascript:void(0)" >
                                                      <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                                   </div>
                                                </div>
                                             @endif
                                             
                                             
                                             <?php
                                        
                                             $fk += 1;

                                          ?>
                                          @endif
                                        
                                          @endforeach
                                          
                                          <!-- bonus of each lesson -->
                                          @if (isset($dbfolder[1]) && !empty($dbfolder[1]))
                                          @foreach($dbfolder[1] as $nkey => $nfolder)
                                          @if($nfolder['parent'] == $folder['id'] && in_array($nfolder['foldername'],$bonusFiles) /*($nfolder['foldername'] == '_Bonus' || $nfolder['foldername'] == 'Bonus')*/) <!--//lioncode-->
                                         
                                          <div class="files-wrapper bonus-files">
                                             <h4 class="bonus-title">{{ $nfolder['foldername'] }}</h4>
                                             <span><i class="icon-folder-open"></i>   </span>
                                             @if (isset($files[$catid][2]) && !empty($files[$catid][2]))
                                             @foreach($files[$catid][2] as $fkey => $frow)
                                             @if (strpos($frow['dirname'], $rf) !== false || strpos($frow['dirname'], $rf1) !== false && !in_array($frow['filename'],$bonus))
                                             <div class="file-wrapper">
                                                <h4 class="file-title">{{ $frow['filename'] }}</h4>
                                                <span class="last-modified">Last modified:  {{$frow['last_mod']}}</span>
                                                <a  class="download-file getdropboxlink"  data-dirname="{{ $frow['dirname'] }}" data-filename="{{ $frow['filename'] }}" href="javascript:void(0)" >
                                                <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                             </div>
                                             @endif
                                             @endforeach
                                             </ul>
                                             @endif
                                          </div>
                                          @endif
                                          @endforeach
                                          @endif
                                          <!-- bonus of each lesson -->
                                          @endif
                                          <!-- /.accordion-content -->
                                       </div>
                                       <!-- /.accordion-item -->
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif<!-- edw -->
                                    <!-- last files-->
                                    @if(!$instructor_topics)
                                    @if (isset($files[$catid][0]) && !empty($files[$catid][0]))
                                    @foreach($files[$catid][0] as $key => $row)
                                    <div class="files-wrapper bonus-files">
                                       <div class="file-wrapper">
                                          <h4 class="file-title">{{ $row['filename'] }}</h4>
                                          <span class="last-modified">Last modified:  {{$row['last_mod']}}</span>
                                          <a  class="download-file getdropboxlink"  data-dirname="{{ $row['dirname'] }}" data-filename="{{ $row['filename'] }}" href="javascript:void(0)" >
                                          <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                       </div>
                                    </div>
                                    @endforeach
                                    @endif
                                    @endif
                                    <!--last files -->
                                    
                                    @endforeach
                                    <!-- /.accordion-wrapper -->
                                 </div>
                                 <!-- /.acc-topic-accordion -->
                              </div>
                              @endif