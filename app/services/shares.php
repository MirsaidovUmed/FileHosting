<?php

namespace App\Services;

use App\Models\Shares;
use App\Repositories\SharesRepository;

class ShareService
{
    protected $sharesRepository;

    public function __construct(SharesRepository $sharesRepository)
    {
        $this->sharesRepository = $sharesRepository;
    }

    public function getShares(int $shareId): ?Shares
    {
        return $this->sharesRepository->findById($shareId);
    }

    public function createShare(int $userId, int $fileId): bool
    {
        $share = new Shares(null, $userId, $fileId, null);
        return $this->sharesRepository->save($share);
    }

    public function deleteShare(Shares $share): bool
    {
        return $this->sharesRepository->delete($share);
    }
}