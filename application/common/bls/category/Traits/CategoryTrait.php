<?php

namespace app\common\bls\category\traits;

use think\Collection;

trait CategoryTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatCategory(Collection $items)
    {
        return $items->each(function ($item) {

            $item->titleName = $item->title;
            if(empty($item->titleName)  && ($page = $item->page)) {
                $item->titleName = $page->title;
            }
        });
    }
}
