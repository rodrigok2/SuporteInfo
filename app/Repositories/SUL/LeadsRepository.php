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
}
