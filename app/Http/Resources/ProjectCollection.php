<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    /**
    * Transform the resource collection into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($item, $index){

                $item['directories'] = $item->directories;
                $item['databases'] = $item->databases;
                $item['backups'] = $item->backups;

                return $item;
            }),
        ];

        return parent::toArray($request);
    }
}
