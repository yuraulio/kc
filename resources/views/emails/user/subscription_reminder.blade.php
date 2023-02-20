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
                        <tr>
                          <td class="r4-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                              <tr>
                                <th width="100%" valign="top" class="r5-c" style="font-weight: normal;">
                                  <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; width: 100%;">
                                    <!-- -->
                                    <tr>
                                      <td valign="top" class="r7-i" style="padding-left: 15px; padding-right: 15px;">
                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                          <tr>
                                            <td class="r8-c" align="left">
                                              <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="200" class="r9-o" style="table-layout: fixed; width: 200px;">
                                                <tr>
                                                  <td class="r10-i" style="padding-bottom: 15px; padding-top: 15px;">
                                                    <a href="https://knowcrunch.com/?utm_source=Knowcrunch.com&utm_medium=Expiration_Email" target="_blank" style="color: #0092ff; text-decoration: underline;">
                                                      <img src="https://img.mailinblue.com/4113051/images/rnb/original/61a340aa5576f4034d329fac.png" width="200" alt="Knowcrunch logo" border="0" class="" style="display: block; width: 100%;">
                                                    </a>
                                                  </td>
                                                </tr>
                                              </table>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </th>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td class="r2-c" align="center">
                      <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r3-o" style="table-layout: fixed; width: 100%;">
                        <!-- -->
                        <tr>
                          <td class="r11-i" style="background-color: #ffffff; padding-bottom: 20px; padding-top: 20px;">
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                              <tr>
                                <th width="100%" valign="top" class="r5-c" style="font-weight: normal;">
                                  <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r6-o" style="table-layout: fixed; width: 100%;">
                                    <!-- -->
                                    <tr>
                                      <td valign="top" class="r7-i" style="padding-left: 10px; padding-right: 10px;">
                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                          <tr>
                                            <td class="r12-c" align="left">
                                              <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                <tr>
                                                  <td align="left" valign="top" class="r14-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; padding-top: 15px; text-align: left;">
                                                    <div>
                                                      <h2 class="default-heading2" style="margin: 0; color: #1f2d3d; font-family: arial,helvetica,sans-serif; font-size: 32px;">
                                                        <span style="font-family: Tahoma, geneva, sans-serif;">Dear {{$firstName}}</span>
                                                      </h2>
                                                    </div>
                                                  </td>
                                                </tr>
                                              </table>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td class="r12-c" align="left">
                                              <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r13-o" style="table-layout: fixed; width: 100%;">
                                                <tr>
                                                  <td align="left" valign="top" class="r15-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; padding-bottom: 15px; padding-top: 15px; text-align: left;">
                                                    <div>
                                                      <p style="margin: 0;">
                                                        <span style="font-family: Tahoma, geneva, sans-serif;">Thanks for spending the year with us on our subscription. We remind you that your subscription plan to the {{$eventTitle}} is set to auto-renew on <strong>{{$expirationDate}}</strong>.  </span>
                                                      </p>
                                                      <br>
                                                      <p style="margin: 0;"></p>
                                                      <p style="margin: 0;">
                                                        <span style="font-family: Tahoma, geneva, sans-serif;">­We encourage you to keep having full access to the course's updated videos, files and your personal notes for one more year. In case you want to cancel your subscription just visit your account and turn off the auto renewal.</span>
                                                      </p>
                                                    </div>
                                                  </td>
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
                                                <tr>
                                                  <td height="18" align="center" valign="top" class="r17-i nl2go-default-textstyle" style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5;">
                                                    <!--[if mso]>
																															<v:roundrect
																																xmlns:v="urn:schemas-microsoft-com:vml"
																																xmlns:w="urn:schemas-microsoft-com:office:word" href="https://knowcrunch.com/?utm_source=Knowcrunch.com&utm_medium=Expiration_Email" style="v-text-anchor:middle; height: 41px; width: 289px;" arcsize="10%" fillcolor="#c8d151" strokecolor="#c8d151" strokeweight="1px" data-btn="1">
																																<w:anchorlock/>
																																<v:textbox inset="0,0,0,0">
																																	<div style="display:none;">
																																		<center class="default-button" style="background-color:#c8d151">
																																			<p>
																																				<span style="color:#000000;">
																																					<strong>Manage my subscription</strong>
																																				</span>
																																			</p>
																																		</center>
																																	</div>
																																</v:textbox>
																															</v:roundrect>
																															<![endif]-->
                                                    <!--[if !mso]>
																															<!-- -->
                                                    <a href="https://knowcrunch.com/?utm_source=Knowcrunch.com&utm_medium=Expiration_Email" class="r18-r default-button" target="_blank" data-btn="1" style="font-style: normal; font-weight: normal; line-height: 1.15; text-decoration: none; border-style: solid; word-wrap: break-word; display: inline-block; -webkit-text-size-adjust: none; mso-hide: all; background-color: #c8d151; border-color: #c8d151; border-radius: 4px; border-width: 0px; color: #ffffff; font-family: arial,helvetica,sans-serif; font-size: 16px; height: 18px; padding-bottom: 12px; padding-left: 5px; padding-right: 5px; padding-top: 12px; width: 280px;">
                                                      <p style="margin: 0;">
                                                        <span style="color: #000000;">
                                                          <strong>Manage my subscription</strong>
                                                        </span>
                                                      </p>
                                                    </a>
                                                    <!--
																															<![endif]-->
                                                  </td>
                                                </tr>
                                                <tr class="nl2go-responsive-hide">
                                                  <td height="15" style="font-size: 15px; line-height: 15px;">­</td>
                                                </tr>
                                              </table>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </th>
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
          </table>
        </td>
</tr>
@stop
