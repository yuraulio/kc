<?php

namespace Library;

use Config;
use Excel;
use Mail;
use Request;
use Validator;
use Flash;
use App;
use Lang;

use PostRider\Newsletter;

class NewsletterLib
{
    public function __construct()
    {
        // can also be set to []
        $this->bcc = [];
        /*[
            0 => ['email' => 'd.parpounas@darkpony.com', 'name' => 'Demetris Parpounas']
        ];*/
    }

    public function subscribe($data = array())
    {
        $thelang = $_ENV['LANG'];
        $defaults = ['lang' => $thelang, 'email' => '', 'current_url' => '', 'message' => '', 'opstatus' => 0, 'opmessage' => '', 'please_fill' => '', 'on_list' => array()];
        $data = array_merge($defaults, $data);
        $validator = Validator::make($data, ['email' => 'required|email', 'name' => 'required','surname' => 'required']);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();//->all();
        } elseif (strlen($data['please_fill'])) {
            // bot control, this should always be empty
            $data['opmessage'] = trans('segments.translation.newsletter_email_notvalid');
        } else {
            $subscriber = Newsletter::where('email', $data['email'])->first();

            if (is_null($subscriber)) {
                if (empty($data['on_list'])) {
                    $on_list = 0;
                } else {
                    $on_list = implode('', array_filter($data['on_list']));
                }

                $subscriber = Newsletter::create([
                    'email' => $data['email'],
                    'status' => 0,
                    'lang' => $data['lang'],
                    'on_list' => $on_list,
                    'extra' => "",
                    'first_name' => $data['name'],
                    'last_name' => $data['surname'],
                    'sex' => "",
                    'dob' => "",
                    'verification_code' => $this->generateCode(),
                    'optin_time' => date('Y-m-d H:i:s'),
                    'optin_ip' => Request::ip()
                ]);

                $edata['name'] = '';
                $edata['lang'] = $data['lang'];
                $edata['code'] = $this->getCode("verify", $subscriber->id);
                $edata['from'] = ['email' => env('MAIL_FROM', 'd.parpounas@darkpony.com'), 'name' => env('MAIL_FROM_NAME', 'Knowcrunch')];
                $edata['to'] = ['email' => $data['email'], 'name' => ''];
                $edata['bcc'] = $this->bcc;

                if ($this->send_email('new_subscription', $edata)) {
                    $data['opstatus'] = 1;
                    $data['opmessage'] = trans('segments.translation.newsletter_info_sent');
                } else {
                    $data['opmessage'] = trans('segments.translation.newsletter_failed_send');
                }
            } else {
                $data['opmessage'] = trans('segments.translation.newsletter_already');
            }
        }

        return $data;
    }

    public function verify_subscription($code = '')
    {
        $subscriber = Newsletter::where('verification_code', $code)->first();
        $data = ['current_url' => '', 'lang' => 'el', 'opstatus' => 0, 'opmessage' => ''];

        if (is_null($subscriber)) {
            //flash message invalid code / url
            $data['opmessage'] = trans('segments.translation.newsletter_code_error');
        } else {
            //flash message success
            $subscriber->update([
                'status' => 1,
                'verification_code' => '',
                'verified_time' => date('Y-m-d H:i:s'),
                'confirm_time' => date('Y-m-d H:i:s'),
                'confirm_ip' => Request::ip()
            ]);

            $edata['name'] = '';
            $edata['lang'] = $data['lang'];
            $edata['code'] = $this->getCode("verify", $subscriber->id);
            $edata['from'] = ['email' => env('MAIL_FROM', 'info@darkpony.com'), 'name' => env('MAIL_FROM_NAME', 'Knowcrunch')];
            $edata['to'] = ['email' => $subscriber->email, 'name' => ''];
            $edata['bcc'] = $this->bcc;

            if ($this->send_email('verify_subscription', $edata)) {
                $data['opstatus'] = 1;
                $data['opmessage'] = trans('segments.translation.newsletter_successfully');
            } else {
                $data['opmessage'] = trans('segments.translation.newsletter_failed_send');
            }
        }

        return $data;
    }

    public function unsubscribe($data = array())
    {
        $defaults = ['lang' => 'el', 'email' => '', 'current_url' => '', 'message' => '', 'opstatus' => 0, 'opmessage' => '', 'please_fill' => ''];
        $data = array_merge($defaults, $data);
        $validator = Validator::make($data, ['email' => 'required|email']);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
        } elseif (strlen($data['please_fill'])) {
            // bot control, this should always be empty
            $data['opmessage'] = trans('segments.translation.newsletter_email_notvalid');
        } else {
            // flash message
            $subscriber = Newsletter::where('email', $data['email'])->first();

            if (!is_null($subscriber)) {
                $edata['name'] = '';
                $edata['lang'] = $data['lang'];
                $edata['code'] = $this->generateRemoveCode($subscriber->id);
                $edata['from'] = ['email' => env('MAIL_FROM', 'info@darkpony.com'), 'name' => env('MAIL_FROM_NAME', 'Knowcrunch')];
                $edata['to'] = ['email' => $data['email'], 'name' => ''];
                $edata['bcc'] = $this->bcc;

                if ($this->send_email('unsubscribe', $edata)) {
                    $data['opstatus'] = 1;
                    $data['opmessage'] = trans('segments.translation.newsletter_instructions');
                } else {
                    $data['opmessage'] = trans('segments.translation.newsletter_failed_send');
                }
            } else {
                $data['opmessage'] = trans('segments.translation.newsletter_email_not_found');
            }
        }

        return $data;
    }

    public function remove_subscription($code = '')
    {
        $subscriber = Newsletter::where('unsubscribe_code', $code)->first();
        $data = ['current_url' => '', 'lang' => 'el', 'opstatus' => 0, 'opmessage' => ''];

        if (is_null($subscriber)) {
            //flash message invalid code / url
            $data['opmessage'] = trans('segments.translation.newsletter_code_error');
        } else {
            //flash message
            $subscriber->update([
                'status' => 0,
                'unsubscribe_code' => '',
                'unsubscribed_time' => date('Y-m-d H:i:s'),
            ]);

            $edata['name'] = '';
            $edata['lang'] = $data['lang'];
            $edata['from'] = ['email' => env('MAIL_FROM', 'info@darkpony.com'), 'name' => env('MAIL_FROM_NAME', 'Knowcrunch')];
            $edata['to'] = ['email' => $subscriber->email, 'name' => ''];
            $edata['bcc'] = $this->bcc;

            if ($this->send_email('remove_subscription', $edata)) {
                $data['opstatus'] = 1;
                $data['opmessage'] = trans('segments.translation.newsletter_removed_from_list');
            } else {
                $data['opmessage'] = trans('segments.translation.newsletter_failed_send');
            }
        }

        return $data;
    }

    public function send_email($op_type = '', $edata = array())
    {
        switch ($op_type)
        {
            case "new_subscription":
                $edata['subject'] = env('SITE_NAME', 'Knowcrunch') . ' - Newsletter Subscription';
                $edata['loadView'] = 'theme.newsletter.new_subscription';
                break;
            case "verify_subscription":
                $edata['subject'] = env('SITE_NAME', 'Knowcrunch') . ' - Verified Subscription';
                $edata['loadView'] = 'theme.newsletter.verify_subscription_success';
                break;
            case "unsubscribe":
                $edata['subject'] = env('SITE_NAME', 'Knowcrunch') . ' - Newsletter Unsubscribe';
                $edata['loadView'] = 'theme.newsletter.remove_subscription';
                break;
            case "remove_subscription":
                $edata['subject'] = env('SITE_NAME', 'Knowcrunch') . ' - Removed Subscription';
                $edata['loadView'] = 'theme.newsletter.remove_subscription_success';
                break;
        }

        if (isset($edata['subject'])) {
            $mail_data = $edata;
            Mail::send($edata['loadView'], ['mail_data' => $mail_data], function ($m) use ($mail_data) {
                $m->from($mail_data['from']['email'], $mail_data['from']['name']);
                $m->to($mail_data['to']['email'], '')->subject($mail_data['subject']);
            });
            return 1;
        } else {
            return 0;
        }
    }

    // Helper Methods

    public function generateCode()
    {
        return sha1(md5(mt_rand().microtime()));
    }

    public function generateRemoveCode($sub_id = 0)
    {
        $code = $this->generateCode();
        Newsletter::where('id', $sub_id)->update(['unsubscribe_code' => $code]);
        return $code;
    }

    public function getCode($op_type = '', $sub_id = 0)
    {
        $retcode = '';

        switch ($op_type)
        {
            case "verify":
                $subscriber = Newsletter::where('id', $sub_id)->first();
                if (!is_null($subscriber)) {
                    $retcode = $subscriber->verification_code;
                }
                break;
            case "unsubscribe":
                $subscriber = Newsletter::where('id', $sub_id)->first();
                if (!is_null($subscriber)) {
                    $retcode = $subscriber->unsubscribe_code;
                }
                break;
            default:
                # code...
                break;
        }

        return $retcode;
    }

    public function createExportFile($type = '', $on_list = "any")
    {
        $this->createDir(base_path('public/uploads/tmp/exports/'));
        $subs = [];
        $query = '';

        switch ($type) {
            case 'active':
                $query = Newsletter::where('status', 1)->select('email');
                break;
            case 'inactive':
                $query = Newsletter::where('status', 0)->select('email');
                break;
            case 'all':
                $query = Newsletter::select('email');
                break;
            default:
                # code...
                break;
        }

        if ($query) {
            $export_name = $type;
            if ($on_list == "any") {
                $subs = $query->get();
            } else {
                $subs = $query->where('on_list', 'like', "%".$on_list."%")->get();
                $list_types = Config::get('dpoptions.newsletter_set.settings.on_list');
                $export_name .= '_'.strtolower($list_types[$on_list]);
            }
        }

        if (!$subs->isEmpty()) {
            $subs = $subs->toArray();
            Excel::create($export_name.'_subscribers', function($excel) use ($subs) {
                $excel->setTitle('Subscriber List');
                $excel->setCreator('Darkpony')->setCompany('Darkpony');
                $excel->setDescription('A demonstration to change the file properties');
                $excel->sheet('Sheetname', function($sheet) use ($subs) {
                    $sheet->fromArray($subs, null, 'A1', false, false);
                });
            })->download('csv');
        } else {
            Flash::error('No subscribers found matching your criteria');
            return redirect()->route('list.Newsletter');
        }
    }

    public function createDir($dir, $permision = 0755, $recursive = true)
    {
        if (!is_dir($dir)) {
            return mkdir($dir, $permision, $recursive);
        } else {
            return true;
        }
    }
}
