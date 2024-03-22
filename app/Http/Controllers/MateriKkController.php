<?php

namespace App\Http\Controllers;

use App\Models\materi_kk;
use Illuminate\Http\Request;

class MateriKkController extends Controller
{
    public function addMateri(Request $req){
        try{
            
            $judul = materi_kk::where('judul', $req->input('judul'))
                                ->where('course_id', $req->input('course_id'))
                                ->get();
            $count = $judul->count();
            if($count > 0){
                return response()->json(['message'=>'Judul yang sama sudah ada']);
            }else{
                $input = $req->all();
                if($req->input('foto') != 'no'){
                    $foto = $req->file('foto');
                $nama_foto = time().'.'.$foto->getClientOriginalExtension();
                $foto->move('images/', $nama_foto);
                $input['foto'] = $nama_foto;
                }
                $data = materi_kk::create($input);
                return response()->json(['message'=>'Berhasil menambahkan materi', 'data'=>$data]);
            }
        }catch(\Throwable $e) {
            return response()->json(['message' =>$e->getMessage()]);
        }
    }

    public function getMateri($course_id){
        try{
            $data = materi_kk::where('course_id', $course_id)
            ->orderBy('created_at', 'asc')
                            ->get();
            $datacount = $data->count();
            if($datacount > 0){
                return response()->json(['message'=>'Berhasil mendapatkan data', 'data'=>$data]);
            }else{
                return response()->json(['message'=>'Belum ada materi di course ini']);
            }
        }catch(\Throwable $e) {
            return response()->json(['message' =>$e->getMessage()]);
        }
    }
}
