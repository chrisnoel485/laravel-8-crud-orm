<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function index()
    {
        //fungsi eloquent menampilkan data menggunakan pagination
        $users = User::latest()->paginate(5);
        return view('users.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    //public function search(Request $request)
    //{
    //    $keyword = $request->search;        
    //    $users = User::where('name', 'like', "%" . $keyword . "%")->paginate(5);
    //    return view('users.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * 5);
    //}

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        //melakukan validasi data
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        //fungsi eloquent untuk menambah data
        User::create($request->all());

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('users.index')
            ->with('success', 'User Berhasil Ditambahkan');
    }

    public function show($id)
    {
        //menampilkan detail data dengan menemukan/berdasarkan id user
        $user = User::find($id);
        return view('users.detail', compact('user'));
    }

    public function edit($id)
    {
        //menampilkan detail data dengan menemukan/berdasarkan id user untuk diedit
        $user = User::find($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        //melakukan validasi data
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        //fungsi eloquent untuk mengupdate data inputan kita
        User::find($id)->update($request->all());

        //jika data berhasil diupdate, akan kembali ke halaman utama
        return redirect()->route('users.index')
            ->with('success', 'User Berhasil Diupdate');
    }

    public function destroy($id)
    {
        //fungsi eloquent untuk menghapus data
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User Berhasil Dihapus');
    }
    //public function search(Request $request)
   // {
    //    $keyword = $request->search;        
    //    $users = User::where('name', 'like', "%" . $keyword . "%")->paginate(5);
    //    return view('users.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * 5);
   // }
    public function search(Request $request){
        $keyword = $request->search;
        $title = 'Search Name';
        
        $User = new User();
        
        $data = User::all();//select* from user
        
        // searched value
        $ketemu = false;// awalnya kosong belum ketemu
        foreach ($data as $keyword) {//apkah data ini sama dengan data yang di cari
            if ($keyword->name == $keyword->input('name')) {
                    $User = $keyword;
                    $ketemu = true;
            break;
            }
        }
       
        if ($ketemu == false) {
            Session::flash('success','Nomor Surat '.$keyword->input('name').' tidak ditemukan');
            return view('users.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        else{
            return view('users.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * 5);
        }
    }    
}