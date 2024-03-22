<?php

namespace App\Http\Controllers;

use App\Models\absen_kk;
use App\Models\course_kk;
use App\Models\jadwal_absen_kk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsenKkController extends Controller
{
    public function absenToday(Request $req){
        try{
            $absen = absen_kk::where('course_id', $req->input('course_id'))
                            ->where('user_id', $req->input('user_id'))
                            ->where('tanggal', $req->input('tanggal'))
                            ->where('bulan', $req->input('bulan'))
                            ->where('tahun', $req->input('tahun'))
                            ->count();
            if($absen > 0){
                return response()->json(['message'=>'Kamu sudah melakukan absen']);
            }else{
                $input = $req->all();
                $data = absen_kk::create($input);
                return response()->json(['message'=>'Absensi berhasil', 'data'=>$data]);
        }
        }catch(\Throwable $e) {
            return response()->json(['message' =>$e->getMessage()]);
        }
    }

    public function getRekapAbsenStudent($bulan, $id){
        try{
            $data = DB::table('absen_kks')
                            ->join('course_kks', 'course_kks.id', 'absen_kks.course_id')
                            ->join('course_join_kks', 'course_join_kks.course_id', 'course_kks.id')
                            ->select('course_kks.nama', 'absen_kks.keterangan', 'absen_kks.hari', 'absen_kks.tanggal', 'absen_kks.bulan', 'absen_kks.waktu','absen_kks.tahun')
                            ->where('absen_kks.bulan', $bulan)
                            ->where('course_join_kks.user_id', $id)
                            ->get();
            $count = $data->count();
            if($count > 0){
                return response()->json(['message' =>'Data berhasil didapatkan', 'data'=>$data]);
            }else{
                return response()->json(['message' =>'Kamu belum melakukan absen bulan ini']);
            }
        }catch(\Throwable $e) {
            return response()->json(['message' =>$e->getMessage()]);
        }
    }

    public function getAbsenPengajar($user_id){
        try{
            $data = DB::table('course_kks')->join('jadwal_absen_kks', 'course_kks.id', 'jadwal_absen_kks.course_id')
                                    ->where('course_kks.instructor_id', $user_id)
                                    ->select('jadwal_absen_kks.hari', 'jadwal_absen_kks.mulai', 'jadwal_absen_kks.selesai', 'course_kks.nama', 'course_kks.id')
                                    ->get();
            $count = $data->count();
            if($count > 0){
                return response()->json(['message'=>'Data berhasil didapatkan', 'data'=>$data]);
            }else{
                return response()->json(['message'=>'Absensi course tidak ditemukan']);
            }
        }catch(\Throwable $e) {
            return response()->json(['message' =>$e->getMessage()]);
        }
    }

    public function getRekapabsenPengajar($course_id){
        try{
            $data = DB::table('absen_kks')
                            ->join('user_kks', 'absen_kks.user_id', 'user_kks.id')
                            ->where('absen_kks.course_id', $course_id)
                            ->select('user_kks.nama', 'absen_kks.keterangan', 'absen_kks.hari', 'absen_kks.tanggal', 'absen_kks.bulan', 'absen_kks.waktu','absen_kks.tahun')
                            ->get();
            $count = $data->count();
            if($count > 0){
                return response()->json(['message'=>'Data berhasil didapatkan', 'data'=>$data]);
            }else{
                return response()->json(['message'=>'Belum ada siswa yang absen']);
            }
        }catch(\Throwable $e) {
            return response()->json(['message' =>$e->getMessage()]);
        }
    }
}
