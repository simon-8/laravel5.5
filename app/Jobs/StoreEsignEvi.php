<?php
/*
 * 保存数据存证
 * */
namespace App\Jobs;

use App\Models\Contract;
use App\Models\EsignEviLink;
use App\Services\EsignSceneEviService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoreEsignEvi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    //public $tries = 5;

    /**
     * 如果模型缺失即删除任务。
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    protected $contract = null;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    public function handle(EsignSceneEviService $service)
    {

        if ($this->contract->status != Contract::STATUS_SIGN) {
            info(__METHOD__, ['当前合同未完成签名']);
            return true;
        }
        /**
         * 1. 创建证据链
         * 2. 创建证据点 (原文基础版认证)
         * 3. 上传文档
         * 4. 关联证据点和证据链
         * 5. 关联证据链和用户
         */
        try {
            $eviLink = EsignEviLink::where('contract_id', $this->contract->id)->first();
            if (!$eviLink) {
                $eviLink = $service->createLink($this->contract);
            }

            if ($eviLink->status === EsignEviLink::STATUS_INIT) {
                $service->createPointBasic($this->contract, $eviLink);
            }

            // 上传 URL 有效期为 1 个小时, 超过之后重新创建证据点
            if (now()->diffInMinutes($eviLink->updated_at) > 60 && $eviLink->status === EsignEviLink::STATUS_POINT_CREATED) {
                $service->createPointBasic($this->contract, $eviLink);
            }

            if ($eviLink->status === EsignEviLink::STATUS_POINT_CREATED) {
                $filePath = Storage::disk('uploads')->path($this->contract->path_pdf);
                $service->uploadFile($eviLink, $filePath);
            }

            if ($eviLink->status === EsignEviLink::STATUS_FILE_UPLOAD) {
                $service->addPointToLink($eviLink);
            }

            if ($eviLink->status === EsignEviLink::STATUS_POINT_LINKED) {
                $service->addLinkToUser($this->contract, $eviLink);
            }

            info(__METHOD__, ['成功']);
        } catch (\Exception $e) {
            Log::error(__METHOD__, [$e->getMessage()]);
        }

    }
}
