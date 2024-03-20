<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SUL\SisLogLsoftBackupRepository;
use Illuminate\Support\Facades\Log;

class LogBackupController extends Controller
{
    function index(Request $request){
        $sisLogLsoftBackupRepository = new SisLogLsoftBackupRepository();

        if($request->cnpj){
            $logs = $sisLogLsoftBackupRepository->PesquisarLogsPorCnpj($request->cnpj);

            if(!$logs){
                Log::warning('Falha ao carregar Logs por CNPJ de backup do SUL!');
                return redirect('admin/logs/index')
                ->with('logs', null)
                ->with('erro', 'Falha ao carregar Logs por CNPJ de backup do SUL!');
            }

            return view("admin.logs.index", compact('logs'));
        }
        else{
            $logs = $sisLogLsoftBackupRepository->PesquisarLogs();

            if(!$logs){
                Log::warning('Falha ao carregar Logs de backup do SUL!');
                return redirect('admin/logs/index')
                ->with('logs', null)
                ->with('erro', 'Falha ao carregar Logs de backup do SUL!');
            }

            return view("admin.logs.index", compact('logs'));
        }
    }
}
