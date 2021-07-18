<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FeTmUser;
use App\Models\FeTmUserAttachment;
use App\Models\SysTmActivityLog;

class UserRegisteredAPIController extends Controller
{
    public function get()
    {
        try {
            $currentYear = date('Y');
            $limit      = request('limit') ? request('limit') : 100;
            $search     = request('search') ? request('search') : '';
            $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            $endDate    = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

            $page       = request('page') ? request('page') : 1;
            $pagination = request('pagination') ? request('pagination') : 12;

            $orderBy    = request('order_by') ? request('order_by') : 'last_update';
            $sort       = request('sort') ? request('sort') : 'desc';

            $USER = FeTmUser::where(function($query) use ($search){
                                    $query->where('_fullname', 'like', '%'.$search.'%')
                                        ->orWhere('email', 'like', '%'.$search.'%');
                                })
                                ->whereBetween('last_update', [$startDate, $endDate])
                                ->select('user_id',
                                        'email',
                                        '_fullname',
                                        '_dob',
                                        'gender_id',
                                        '_home_phone',
                                        '_mobile_phone',
                                        '_address',
                                        '_subscribe',
                                        '_avatar_url',
                                        '_avatar_object_name',
                                        '_avatar_real_name',
                                        '_active',
                                        '_last_login_at',
                                        '_last_login_ip',
                                        'last_update_by_usercms')
                                ->orderBy(''.$orderBy, $sort)
                                ->paginate($pagination);
            $data = [
                'users' => $USER,
                's'     => $startDate,
                'e'     => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switch($userId, $status)
    {
        try {
            DB::beginTransaction();

            $USER = FeTmUser::find($userId);
            if ($status == false || $status == 'false') {
                $USER->_active = '0';
                $this->logbook($userId, 47, $USER->_fullname);
            } else {
                $USER->_active = '1';
                $this->logbook($userId, 46, $USER->_fullname);
            }
            $USER->save();
            DB::commit();

            $RETURNUSER = FeTmUser::where('user_id', $userId)
                                ->select('*')
                                ->first();
            return response()->json($RETURNUSER);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function detail($userId)
    {
        try {
            DB::beginTransaction();

            $USER = FeTmUser::select('user_id',
                                    'email',
                                    '_fullname',
                                    '_dob',
                                    'gender_id',
                                    '_home_phone',
                                    '_mobile_phone',
                                    '_address',
                                    '_subscribe',
                                    '_avatar_url',
                                    '_avatar_object_name',
                                    '_avatar_real_name',
                                    '_active',
                                    '_last_login_at',
                                    '_last_login_ip',
                                    'last_update_by_usercms')
                            ->where('user_id', $userId)
                            ->first();

                $ATTACHMENT = FeTmUserAttachment::where('user_id', $USER->user_id)
                                                ->select('*')
                                                ->orderBy('last_update', 'desc')
                                                ->first();

                if ($ATTACHMENT == null) {
                    $USER['ktp_id']     = null;
                    $USER['ktp_name']   = null;
                    $USER['ktp_img']    = null;
                } else {
                    $USER['ktp_id']     = $ATTACHMENT->user_attachment_id;
                    $USER['ktp_name']   = $ATTACHMENT->_name;
                    $USER['ktp_img']    = $ATTACHMENT->_url;
                }

            return response()->json($USER);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function rejectKTP($userId, $ktpId)
    {
        try {
            DB::beginTransaction();

            $ATTACHMENT = FeTmUserAttachment::where('user_id', $userId)
                        ->delete();

            $USER = FeTmUser::find($userId);
            $USER->_id_verified = '0';
            $USER->save();

            $this->logbook($USER->user_id, 60, $USER->email);

            DB::commit();
            
            return response()->json($ATTACHMENT);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    private function logbook($ref_id, $act_id, $ref_name)
    {
        try {
            DB::beginTransaction();

            $ip             = $_SERVER['REMOTE_ADDR'];
            $userCmsId      = request('user_cms_id');
            $permissionId   = 13;

            $LOG = new SysTmActivityLog();
            $LOG->_ip = $ip;
            $LOG->permission_id = $permissionId;
            $LOG->cms_user_id = $userCmsId;
            $LOG->activity_id = $act_id;
            $LOG->referred_id = $ref_id;
            $LOG->referred_name = $ref_name;
            $LOG->create_date = date('Y-m-d H:m:s');
            $LOG->save();

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
        }
    }
}
