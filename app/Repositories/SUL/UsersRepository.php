<?php

namespace App\Repositories\SUL;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \App\Repositories\SUL\UsersGrupsRepository;

class UsersRepository{

    /*
    *Consulta todos os usuarios do SUL de acordo com o status passado no paramentro
    */
    function PesquisarUsuarios($status_users){
        try{
            //Prepara a consulta dos usuarios
            $users = DB::connection('mysql_sul')->table("users")
            ->select(
                "users.id as user_id",
                "users.name as nome",
                "users.username as username",
                "users.email as email",
                "users.grupo as grupo_id"
            );

            //Inclui a clausula where com 'ativos' ou 'inativos' conforme paramentro recebido
            if($status_users == 'ativos'){
                $users->where("users.active", "=", 1);
            }
            elseif($status_users == 'inativos'){
                $users->where("users.active", "=", 0);
            }

            //Inclui ordenação por Id
            $users->orderBy("user_id", "asc");

            //Consulta os usuarios
            $users = $users->get();

            //Converte para array
            $users = json_decode(json_encode($users), true);

            //retorna o array de usuarios
            return $users;
        }catch(Exception $e){
            Log::critical('UsersRepository->PesquisarUsuarios', [$e]);
            return false;
        }
    }

    function UsuarioPorId($user_Id){
        try{
            //Pesquisa os dados do técnico
            $tecnico = DB::connection('mysql_sul')->table("users")
                ->select(
                    "users.id as user_id",
                    "users.name as nome",
                    "users.username as username",
                    "users.email as email",
                    "users.grupo as grupo_id",
                    )
                ->where("users.id", "=", $user_Id)
                ->get();

            $tecnico = json_decode(json_encode($tecnico), true);

            $usersGrupsRepository = new UsersGrupsRepository();
            $user_departamento = $usersGrupsRepository->DepartamentoPorId($tecnico[0]['grupo_id']);
            if(!$user_departamento){
                $tecnico[0]['grupo_descricao'] = "";
            }
            else{
                $tecnico[0]['grupo_descricao'] = $user_departamento;
            }

            return $tecnico;
        }catch(Exception $e){
            Log::critical('UsersRepository->UsuarioPorId', [$e]);
            return false;
        }
    }

    function UsernamePorId($user_Id){
        try{
            //Pesquisa os dados do técnico
            $tecnico = DB::connection('mysql_sul')->table("users")
            ->select(
                "users.username as username"
                )
            ->where("users.id", "=", $user_Id)
            ->get();
            return $tecnico;
        }catch(Exception $e){
            Log::critical('UsersRepository->UsernamePorId', [$e]);
            return false;
        }
    }

    function UsuariosProfileSuporte(){
        try{
            //Pesquisa os dados do técnico
            $tecnicos = DB::connection('mysql_sul')->table("users")
            ->select(
                "users.id as tecnico_id",
                "users.username as username"
                )
            ->join('users_profiles', 'users.id', '=', 'users_profiles.user')
            ->where("users.active", "=", 1)
            ->where("users_profiles.profile", "=", 3)
            ->where("users.id", "<>", 2)
            ->where("users.id", "<>", 79)
            ->where("users.id", "<>", 102)
            ->where("users.id", "<>", 14)
            ->orderBy("tecnico_id", "asc")
            ->get();

            $tecnicos = json_decode(json_encode($tecnicos), true);

            return $tecnicos;
        }catch(Exception $e){
            Log::critical('UsersRepository->UsuariosProfileSuporte', [$e]);
            return false;
        }

    }
}
