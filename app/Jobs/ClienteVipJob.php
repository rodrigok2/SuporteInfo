<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\ClienteVipService;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClienteVipMail;
use Illuminate\Support\Facades\Log;
use App\Repositories\Local\VipRepository;

class ClienteVipJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $clienteVipService = new ClienteVipService();
        $dadosVips = $clienteVipService->VerificarNovasOs();

        if($dadosVips == null){
            Log::critical('Nao foi possivel executar a JOB ClienteVipJob.php', ['dadosVips' => $dadosVips]);
        }
        else{
            $vipRepository = new VipRepository();

            for($i = 0; $i < count($dadosVips); $i++){
                if($dadosVips[$i]['novas_os'] != null){
                    $email = new ClienteVipMail(
                        $dadosVips[$i]['razao_social'],
                        $dadosVips[$i]['cnpj'],
                        $dadosVips[$i]['contrato_id'],
                        $dadosVips[$i]['novas_os'],
                    );

                    if($dadosVips[$i]['novas_os'] != null){
                        $array_os = end($dadosVips[$i]['novas_os']);
                        $os_id = $array_os['os_id'];
                        $vip_id = $dadosVips[$i]['vip_id'];
                        $vipUpdate = $vipRepository->AtualizarUltimaOs($os_id, $vip_id);

                        if(!$vipUpdate){
                            Log::critical('Falha ao atualizar a ultima O.S. do cliente Vip', ['vipUpdate' => $vipUpdate]);
                        }
                    }

                    Mail::to('helpdesk@lsoft.com.br')->send($email);
                }
            }
        }
    }
}
