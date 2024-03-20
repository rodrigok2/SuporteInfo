<?php

namespace App\Repositories\SUL;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SisLogLsoftBackupRepository{

    function PesquisarLogs(){
        try{
            //Busca a tabela de prioridades no SUL
            $logs = DB::connection('mysql_sul')->table("sis_log_lsoft_backup")
            ->select(
                "sis_log_lsoft_backup.id as log_id",
                "sis_log_lsoft_backup.documento as cnpj",
                "sis_log_lsoft_backup.data as data",
                "sis_log_lsoft_backup.info as descricao",
                "sis_log_lsoft_backup.computador as computador",
                "sis_log_lsoft_backup.pasta as pasta",
            )
            ->where("sis_log_lsoft_backup.info", "like", '%BACKUP NÃO GRAVADO%')
            ->orderBy("data", "desc")
            ->paginate(50);

            //retornar o array de prioridades
            return $logs;
        }catch(Exception $e){
            Log::critical('SisLogLsoftBackupRepository->PesquisarLogs', [$e]);
            return false;
        }
    }

    function PesquisarLogsPorCnpj($cnpj){
        try{
            //Busca a tabela de prioridades no SUL
            $logs = DB::connection('mysql_sul')->table("sis_log_lsoft_backup")
            ->select(
                "sis_log_lsoft_backup.id as log_id",
                "sis_log_lsoft_backup.documento as cnpj",
                "sis_log_lsoft_backup.data as data",
                "sis_log_lsoft_backup.info as descricao",
                "sis_log_lsoft_backup.computador as computador",
                "sis_log_lsoft_backup.pasta as pasta",
            )
            ->where("sis_log_lsoft_backup.info", "like", '%BACKUP NÃO GRAVADO%')
            ->where("sis_log_lsoft_backup.documento", "=", $cnpj)
            ->orderBy("data", "desc")
            ->paginate(30);

            //retornar o array de prioridades
            return $logs;
        }catch(Exception $e){
            Log::critical('SisLogLsoftBackupRepository->PesquisarLogsPorCnpj', [$e]);
            return false;
        }
    }
}
