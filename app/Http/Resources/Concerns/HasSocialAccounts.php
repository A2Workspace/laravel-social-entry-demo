<?php

namespace App\Http\Resources\Concerns;

trait HasSocialAccounts
{
    /**
     * Map the social accounts.
     *
     * @param  string  $relationship
     * @return \Illuminate\Http\Resources\MergeValue|mixed
     */
    protected function mergeSocialAccounts($force = false, $relationship = 'socialIdentifiers')
    {
        if ($force) {
            $this->resource->load($relationship);
        }

        return $this->mergeWhen(
            $force || $this->resource->relationLoaded($relationship),
            function () use ($relationship) {
                $identifiers = $this->resource->getRelation($relationship);

                $accountMap = [];

                foreach ($identifiers as $identifier) {
                    $accountMap["{$identifier->type}_id"] = $identifier->identifier;
                }

                return $accountMap;
            }
        );
    }
}
