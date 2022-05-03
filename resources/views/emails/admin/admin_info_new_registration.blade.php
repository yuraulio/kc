<?php


$tickettype = $extrainfo[1];


if(isset($helperdetails[$user['email']])) {

    $did = $helperdetails[$user['email']]['deid'];
    $mob = $helperdetails[$user['email']]['mobile'] ? $helperdetails[$user['email']]['mobile'] : '';
    $com = $helperdetails[$user['email']]['company'] ? $helperdetails[$user['email']]['company'] : '';
    $job = $helperdetails[$user['email']]['jobtitle'] ? $helperdetails[$user['email']]['jobtitle'] : '';
    //$stid = $helperdetails[$user['email']]['stid']; (user given ticket type id)
    $dereeid = $did; //substr($did, 0, -2);
}
else {

    $did = '-';
    //$stid = '-';
    $dereeid = '-';
    $mob = isset($user['mobile']) ? $user['mobile'] : '-';
    $com = isset($user['company']) ? $user['company'] : '-';
    $job = isset($user['jobTitle']) ? $user['jobTitle'] : '-';
}



?>

<?php if(isset($trans->status_history[0]['pay_seats_data']['student_type_id'])){

	$stId = $trans->status_history[0]['pay_seats_data']['student_type_id'][0];
	
}else{
	
	$stId = null;
}
?>



@extends('emails.user.layouts.master')
@section('email_body')

<tr>
   <td align="center" class="r0-c">
      <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="600" class="r1-o" style="table-layout: fixed; width: 600px;">
         <tr>
                     <td valign="top" class="">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                           <tr>
                              <td class="r2-c" align="center">
                                 <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r3-o" style="table-layout: fixed; width: 100%;">
                                    <!-- -->
                                    <tr class="nl2go-responsive-hide">
                                       <td height="20" style="font-size: 20px; line-height: 20px; background-color: #ffffff;">­</td>
                                    </tr>
                                    <tr>
                                       <td class="r4-i" style="background-color: #ffffff;">
                                          <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                             <tr>
                                                <th width="100%" valign="top" class="r5-c">
                                                   <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; width: 100%;">
                                                      <!-- -->
                                                      <tr>
                                                         <td class="nl2go-responsive-hide" width="15" style="font-size: 0px; line-height: 1px;">­ </td>
                                                         <td valign="top" class="r7-i">
                                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                               <tr>
                                                                  <td class="r8-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="200" class="r9-o" style="table-layout: fixed; width: 200px;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        <tr>
                                                                           <td class="r10-i"> <a href="https://knowcrunch.com/?utm_source=Knowcrunch.com&utm_medium=Failed_Payment_Email" target="_blank" style="color: #0092ff; text-decoration: underline;"> <img src="https://img.mailinblue.com/4113051/images/rnb/original/61a340aa5576f4034d329fac.png" width="200" alt="Knowcrunch logo" border="0" class="" style="display: block; width: 100%;"></a> </td>
                                                                        </tr>
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>
                                                            </table>
                                                         </td>
                                                         <td class="nl2go-responsive-hide" width="15" style="font-size: 0px; line-height: 1px;">­ </td>
                                                      </tr>
                                                   </table>
                                                </th>
                                             </tr>
                                          </table>
                                       </td>
                                    </tr>
                                    <tr class="nl2go-responsive-hide">
                                       <td height="20" style="font-size: 20px; line-height: 20px; background-color: #ffffff;">­</td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                           <tr>
                              <td class="r2-c" align="center">
                                 <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r3-o" style="table-layout: fixed; width: 100%;">
                                    <!-- -->
                                    <tr class="nl2go-responsive-hide">
                                       <td height="20" style="font-size: 20px; line-height: 20px; background-color: #ffffff;">­</td>
                                    </tr>
                                    <tr>
                                       <td class="r11-i" style="background-color: #ffffff;">
                                          <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                             <tr>
                                                <th width="100%" valign="top" class="r5-c">
                                                   <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; width: 100%;">
                                                      <!-- -->
                                                      <tr>
                                                         <td class="nl2go-responsive-hide" width="10" style="font-size: 0px; line-height: 1px;">­ </td>
                                                         <td valign="top" class="r7-i">
                                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                               <tr>
                                                                  <td class="r12-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        <tr>
                                                                           <td align="left" valign="top" class="r14-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left;">
                                                                              <div>
                                                                                 <h2 class="default-heading2" style="color: #1F2D3D; font-family: arial,helvetica,sans-serif; font-size: 32px; margin: 0px;">New registration</h2>
                                                                              </div>
                                                                           </td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>
                                                               <tr>
                                                                  <td class="r12-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        <tr>
                                                                           <td align="left" valign="top" class="r15-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left;">
                                                                              <div>
                                                                                 <p style="margin: 0px;"><strong>COURSE</strong></p>
                                                                              </div>
                                                                           </td>
                                                                        </tr>
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>
                                                               <tr>
                                                                  <td class="r12-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        <tr>
                                                                           <td align="left" valign="top" class="r15-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left;">
                                                                              <div>
                                                                                 <p style="margin: 0px;"><span style="font-family: Tahoma, geneva, sans-serif;">{{ $extrainfo[2] }}</span></p>
                                                                              </div>
                                                                           </td>
                                                                        </tr>
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>
                                                               <tr>
                                                                  <td class="r12-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        <tr>
                                                                           <td align="left" valign="top" class="r15-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left;">
                                                                              <div>
                                                                                 <p style="margin: 0px;"><strong>CUSTOMER DATA</strong></p>
                                                                              </div>
                                                                           </td>
                                                                        </tr>
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>
                                                               <tr>
                                                                  <td class="r12-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        <tr>
                                                                           <td align="left" valign="top" class="r15-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left;">
                                                                              <div>
                                                                                 <p style="margin: 0px;">{{ $user['name'] }}</p>
                                                                              </div>
                                                                           </td>
                                                                        </tr>
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>
                                                               <tr>
                                                                  <td class="r12-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        <tr>
                                                                           <td align="left" valign="top" class="r15-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left;">
                                                                              <div>
                                                                                 <p style="margin: 0px;">Email: {{ $user['email'] }}, Phone: {{$mob}}</p>
                                                                              </div>
                                                                           </td>
                                                                        </tr>
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>

                                                               <tr>
                                                                  <td class="r12-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        <tr>
                                                                           <td align="left" valign="top" class="r15-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left;">
                                                                              <div>
                                                                                 <p style="margin: 0px;">Position: {{$job}}@if(isset($comp)), Company: {{$com}}@endif</p>
                                                                              </div>
                                                                           </td>

                                                                        </tr>
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>
                                                               <tr>
                                                                  <td class="r12-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        <tr>
                                                                           <td align="left" valign="top" class="r15-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left;">
                                                                              <div>
                                                                                 <p style="margin: 0px;"><strong>TRANSACTION DATA</strong></p>
                                                                              </div>
                                                                           </td>
                                                                        </tr>
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>
                                                               <tr>
                                                                  <td class="r12-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        <tr>
                                                                           <td align="left" valign="top" class="r15-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left;">
                                                                              <div><strong>TICKET TYPE:</strong>  <br /><br />
                                                                                 <?php 
                                                                                    $ticketType  = $tickettype;
                                                                                    if($trans->total_amount == 0){
                                                                                       $tickettype .=  ', Free'; 
                                                                                    }else { 
                                                                                       $tickettype .= ', ' . round($trans->total_amount,2);
                                                                                       
                                                                                    }

                                                                                    if($stId){ 
                                                                                       $tickettype .= ', ' . $stId ;
                                                                                    }
                                                                                 ?>
                                                                                 <p style="margin: 0px;">Ticket: {{ $tickettype }} </p>
                                                                              </div>
                                                                           </td>
                                                                        </tr>
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>
                                                               <tr>
                                                                  <td class="r12-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        <tr>
                                                                           <td align="left" valign="top" class="r15-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left;">
                                                                              <div>
                                                                                 <p style="margin: 0px;">Amount paid: @if($trans->total_amount == 0) Free @else {{   round($trans->total_amount/$installments,2) }} @endif </p>
                                                                              </div>
                                                                           </td>
                                                                        </tr>
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>
                                                               <tr>
                                                                  <td class="r12-c" align="left">
                                                                     <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="290" class="r16-o" style="table-layout: fixed; width: 290px;">
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                        @if($user['id'])
                                                                        <tr>
                                                                           <td height="18" align="center" valign="top" class="r17-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5;">
                                                                     
                                                                              <a href="http://www.knowcrunch.com/admin/user/{{$user['id']}}/edit" class="r18-r default-button" target="_blank" data-btn="1" style="line-height: 1.15; text-decoration: none; border-style: solid; display: inline-block; -webkit-text-size-adjust: none; mso-hide: all; background-color: #c8d151; border-color: #c8d151; border-radius: 4px; border-width: 0px; color: #ffffff; font-family: arial,helvetica,sans-serif; font-size: 16px; height: 18px; padding-bottom: 12px; padding-left: 5px; padding-right: 5px; padding-top: 12px; width: 280px;">
                                                                                 <p style="margin: 0px;"><span style="color: #3b3f44;"><strong>Check this customer</strong></span></p>
                                                                              </a>
                                                                          
                                                                           </td>
                                                                        </tr>
                                                                        @endif
                                                                        <tr class="nl2go-responsive-hide">
                                                                           <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                                        </tr>
                                                                     </table>
                                                                  </td>
                                                               </tr>
                                                            </table>
                                                         </td>
                                                         <td class="nl2go-responsive-hide" width="10" style="font-size: 0px; line-height: 1px;">­ </td>
                                                      </tr>
                                                   </table>
                                                </th>
                                             </tr>
                                          </table>
                                       </td>
                                    </tr>
                                    <tr class="nl2go-responsive-hide">
                                       <td height="20" style="font-size: 20px; line-height: 20px; background-color: #ffffff;">­</td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                        </table>
                     </td>
         </tr>
      </table>
   </td>
</tr>

@stop
        