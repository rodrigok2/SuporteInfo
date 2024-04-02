<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTime;
use App\Repositories\SUL\UsersRepository;
use App\Services\MMRService;

class ProdutividadeController extends Controller
{
    function index(Request $request){
        $user = Auth::user();

        if($request->filtro_ativo == 1){
            $data_inicial = new DateTime($request->data_inicial);
            $data_final = new DateTime($request->data_final);

            $dateInterval = $data_inicial->diff($data_final);
            if($dateInterval->days > 31 || ($data_inicial > $data_final)){
                return redirect('admin/produtividade/index')
                    ->with("tecnicos", null)
                    ->with("erro","As datas selecionadas são inválidas!");
            }
            else{
                $usersRepository = new UsersRepository();
                $tecnicos = $usersRepository->UsuariosProfileSuporte();
                if(!$tecnicos){
                    return redirect('admin/produtividade/index')
                        ->with("tecnicos", null)
                        ->with("erro","Falha ao carregar lista de usuários do setor suporte no SUL!");
                }
                else{
                    $mrservice = new MMRService();
                    $tecnicos = $mrservice->calcularMmr($tecnicos, $request->data_inicial, $request->data_final);

                    //ordenar por MMR
                    array_multisort(array_column($tecnicos, 'mmr'), SORT_DESC, $tecnicos);

                    return view ('admin.produtividade.index', compact('tecnicos'));
                }
            }
        }
        return view ('admin.produtividade.index')->with('tecnicos', null);
    }

    function teste(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $username = $user->username;

        if($request->filtro_ativo == 1){
            $data_inicial = new DateTime($request->data_inicial);
            $data_final = new DateTime($request->data_final);

            $dateInterval = $data_inicial->diff($data_final);
            if($dateInterval->days > 31 || ($data_inicial > $data_final)){
                return redirect('admin/produtividade/teste')
                    ->with("tecnicos", null)
                    ->with('user_id', null)
                    ->with('username', null)
                    ->with("erro","As datas selecionadas são inválidas!");
            }
            else{
                $usersRepository = new UsersRepository();
                $tecnicos = $usersRepository->UsuariosProfileSuporte();
                if(!$tecnicos){
                    return redirect('admin/produtividade/teste')
                        ->with("tecnicos", null)
                        ->with('user_id', null)
                        ->with('username', null)
                        ->with("erro","Falha ao carregar lista de usuários do setor suporte no SUL!");
                }
                else{
                    $mrservice = new MMRService();
                    $tecnicos = $mrservice->calcularMmr($tecnicos, $request->data_inicial, $request->data_final);

                    //ordenar por MMR
                    array_multisort(array_column($tecnicos, 'mmr'), SORT_DESC, $tecnicos);
                    //dd($tecnicos);
                    return view ('admin.produtividade.teste', compact('tecnicos', 'user_id', 'username'));
                }
            }
        }
        return view ('admin.produtividade.teste')
            ->with('tecnicos', null)
            ->with('username', null)
            ->with('user_id', null);
    }
}
