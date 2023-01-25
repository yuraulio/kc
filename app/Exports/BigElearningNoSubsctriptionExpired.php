<?php

namespace App\Exports;

use App\Model\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BigElearningNoSubsctriptionExpired implements FromArray,WithHeadings, ShouldAutoSize
{
    public $users;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function  __construct()
    {
        $this->users = User::doesntHave('subscriptionEvents')->whereHas('transactions',function($transaction){

            $transaction->whereHas('event', function($event){
                $event->whereHas('category', function($category){
                    return $category->whereIn('categoryables.category_id',[46,277,183]);
                });
            });

        })
        ->with([
            'events_for_user_list' => function($event){
                return $event->wherePivot('paid',true)->whereHas('category', function($category){
                    return $category->whereIn('categoryables.category_id',[46,277,183]);
                });
            }
        ])
        ->get();

    }

    public function array(): array
    {
        $data = array();

        foreach($this->users as $user){

            $rowdata = array(
                $user['id'],
                $user['firstname'],
                $user['lastname'],
                $user['email'],
                $user['mobile']
            );

            array_push($data, $rowdata);

        }

        return $data;
    }

    public function headings(): array {
        return ["ID", "First Name", "Last Name", "email",'Mobile'];
    }
}
