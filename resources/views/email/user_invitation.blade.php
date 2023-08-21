@php
    $dir= asset(Storage::url('uploads/logo'));
@endphp
<html>
   <head></head>
   <body>
      <div style="background-color:#f8f8f8">
         <div style="background-color:#f8f8f8">
            <table align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px" width="600">
               <tbody>
                  <tr>
                     <td style="line-height:0px;font-size:0px">
                        <div style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px">
                           <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%">
                              <tbody>
                                 <tr>
                                    <td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:0px;padding-left:0px;padding-right:0px;padding-top:0px;text-align:center;vertical-align:top">
                                       <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                          <tbody>
                                             <tr>
                                                <td style="vertical-align:top;width:600px">
                                                   <div class="m_8255827885234734827mj-column-per-100 m_8255827885234734827outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%">
                                                      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top" width="100%">
                                                         <tbody>
                                                            <tr>
                                                               <td style="font-size:0px;padding:10px 25px;padding-top:0px;padding-right:0px;padding-bottom:40px;padding-left:0px;word-break:break-word">
                                                                  <p style="border-top:solid 7px #040D4B;font-size:1;margin:0px auto;width:100%">
                                                                  </p>
                                                                  <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 7px #040D4B;font-size:1;margin:0px auto;width:600px" role="presentation" width="600px">
                                                                     <tbody>
                                                                        <tr>
                                                                           <td style="height:0;line-height:0">
                                                                           </td>
                                                                        </tr>
                                                                     </tbody>
                                                                  </table>
                                                               </td>
                                                            </tr>
                                                            <tr>
                                                               <td align="center" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word">
                                                                  <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px">
                                                                     <tbody>
                                                                        <tr>
                                                                           <td style="width:110px">
                                                                              <img alt="" height="auto" src="{{ $dir.'/'. logo.png  }}" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%" title="" width="110" class="CToWUd">
                                                                           </td>
                                                                        </tr>
                                                                     </tbody>
                                                                  </table>
                                                               </td>
                                                            </tr>
                                                         </tbody>
                                                      </table>
                                                   </div>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </td>
                  </tr>
               </tbody>
            </table>
            <table align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px" width="600">
               <tbody>
                  <tr>
                     <td style="line-height:0px;font-size:0px">
                        <div style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px">
                           <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%">
                              <tbody>
                                 <tr>
                                    <td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;padding-bottom:70px;padding-top:30px;text-align:center;vertical-align:top">
                                       <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                          <tbody>
                                             <tr>
                                                <td style="vertical-align:top;width:600px">
                                                   <div class="m_8255827885234734827mj-column-per-100 m_8255827885234734827outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%">
                                                      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top" width="100%">
                                                         <tbody>
                                                            <tr>
                                                               <td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-right:50px;padding-bottom:0px;padding-left:50px;word-break:break-word">
                                                                  <div style="font-family:Open Sans,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:left;color:#797e82">
                                                                     <h2 style="text-align:center;color:#000000;line-height:32px">
                                                                        {{__('Welcome to '). (!empty($company->company_name) ? $company->company_name : $company->first_name)}}   
                                                                     </h2>
                                                                  </div>
                                                               </td>
                                                            </tr>
                                                            <tr>
                                                               <td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-right:50px;padding-bottom:0px;padding-left:50px;word-break:break-word">
                                                                  <div style="font-family:Open Sans,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:left;color:#797e82">
                                                                     <p style="margin:10px 0;text-align:center">{{__('Hi  '.$user->first_name.',  Thank you for joining to RotaGo as a User')}}</p>
                                                                     <p style="margin:10px 0;text-align:center">{{__('Your Account Details')}}:</p>
                                                                  </div>
                                                               </td>
                                                            </tr>
                                                            <tr>
                                                               <td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-right:50px;padding-bottom:0px;padding-left:50px;word-break:break-word">
                                                                  <div style="font-family:Open Sans,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:left;color:#797e82">
                                                                     <p style="margin:10px 0 0 0;text-align:center"><b>{{__('Username')}} : {{ $user->email }}</b></p>                                                                     
                                                                     <p style="margin:0px 0 0 0;text-align:center"><b>{{__('Password')}} : {{ $password }}</b></p>                                                                     
                                                                  </div>
                                                               </td>
                                                            </tr>                                                            
                                                         </tbody>
                                                      </table>
                                                   </div>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </td>
                  </tr>
               </tbody>
            </table>
            <div class="yj6qo"></div>
            <div class="adL">
            </div>
         </div>
         <div class="adL">
         </div>
      </div>
   </body>
</html>