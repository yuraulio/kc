<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Http\Controllers\NotificationController;
use Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use Hash;
use App\Model\Ticket;
use App\Model\Event;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Model\Activation;
use Illuminate\Support\Str;


class ChunkReadFilter implements IReadFilter
{
    private $startRow = 0;

    private $endRow = 0;

    /**
     * Set the list of rows that we want to read.
     *
     * @param mixed $startRow
     * @param mixed $chunkSize
     */
    public function setRows($startRow, $chunkSize): void
    {
        $this->startRow = $startRow;
        $this->endRow = $startRow + $chunkSize;
    }

    public function readCell($columnAddress, $row, $worksheetName = '')
    {
        //  Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow
        if (($row == 1) || ($row >= $this->startRow && $row < $this->endRow)) {
            return true;
        }

        return false;
    }
}


class StudentController extends Controller
{
    public function statusInform(Request $request)
    {
        $user = User::find($request->input("content_id"));

        $notification = new NotificationController;

        if ($notification->userStatusChange($user)) {
            return [
                'status' => 1,
                'message' => 'The email has been sent',
            ];
        } else {
            return [
                'status' => 1,
                'message' => 'The email has not been sent',
            ];
        }
    }

    public function passwordInform(Request $request)
    {
        $user_id = $request->input("content_id");
        $notification = new NotificationController;
        if ($notification->userChangePassword($user_id)) {
            return [
                'status' => 1,
                'message' => 'The email has been sent',
            ];
        } else {
            return [
                'status' => 1,
                'message' => 'The email has not been sent',
            ];
        }
    }

    public function activationInform(Request $request)
    {
        $user_id = $request->input("content_id");

        $notification = new NotificationController;
        //dd($notification->userActivationLink($user_id));

        if ($notification->userActivationLink($user_id)) {
            return [
                'status' => 1,
                'message' => 'The email has been sent',
            ];
        } else {
            return [
                'status' => 1,
                'message' => 'The email has not been sent',
            ];
        }
    }

}
