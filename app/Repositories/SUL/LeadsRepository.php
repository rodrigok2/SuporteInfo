<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class LeadsRepository{

    function LeadsAgrupadosPorOrigem($data_inicial, $data_final, $sistema){
        try{
            $leads = DB::connection('mysql_sul')->table("leads")
                ->select("leads_origin.origin as origem", DB::raw('count(leads_origin.origin) as total'))
                ->join('leads_origin', 'leads.origin', '=', 'leads_origin.id')
                ->where("leads.date_finish", ">=", $data_inicial." 00:00:00")
                ->where("leads.date_finish", "<=", $data_final." 23:59:59");

            if($sistema != null){
                $leads->where("leads.solucao", "=", $sistema);
            }

            $leads->orderBy("total","desc");
            $leads->groupBy("origem");
            $leads = $leads->get();

            //Converte o resultado em array
            $leads = json_decode(json_encode($leads), true);

            return $leads;
        }catch(Exception $e){
            Log::critical('LeadsRepository->LeadsAgrupadosPorOrigem', [$e]);
            return false;
        }
    }

    function LeadsFinalizados($data_inicial, $data_final, $sistema){
        try{
            $leads = DB::connection('mysql_sul')->table("leads")
                ->select(
                    "leads.id as lead_id",
                    "leads.name as cliente",
                    "leads.cnpj as cnpj",
                    "leads.date_start as data_abertura",
                    "leads.date_finish as data_fechado",
                    "leads.status as status_id",
                    "leads_status.name as status_descricao",
                    "leads.motivo as motivo_id",
                    "leads_status_motivos.name as motivo_descricao",
                    "leads.solucao as sistema_id",
                    "sis_sistemas.nome as sistema",
                    "leads.user_id as user_id",
                    "users.username as username",
                    "leads.origin as origin_id",
                    "leads_origin.origin as origin_descricao",
                )
                ->join('leads_status', 'leads.status', '=', 'leads_status.id')
                ->join('leads_status_motivos', 'leads.motivo', '=', 'leads_status_motivos.id')
                ->join('leads_origin', 'leads.origin', '=', 'leads_origin.id')
                ->join('sis_sistemas', 'leads.solucao', '=', 'sis_sistemas.id')
                ->join('users', 'leads.user_id', '=', 'users.id')
                ->where("leads.date_finish", ">=", $data_inicial." 00:00:00")
                ->where("leads.date_finish", "<=", $data_final." 23:59:59");

            if($sistema != null){
                $leads->where("leads.solucao", "=", $sistema);
            }

            $leads->orderBy("data_fechado","asc");
            $leads = $leads->get();

            //Converte o resultado em array
            $leads = json_decode(json_encode($leads), true);

            return $leads;
        }catch(Exception $e){
            Log::critical('LeadsRepository->LeadsFinalizados', [$e]);
            return false;
        }
    }
}
