<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\SubscriptionsRequest;
use App\Models\Subscriptions;
use Illuminate\Http\Response;

class SubscriptionsController extends BaseController
{
    public function subscribe(SubscriptionsRequest $request): JsonResponse
    {
        $sub = Subscriptions::where('subscription_id', $request->subscription_id)->where('user_id', $this->user->id);

        if ($sub->count() > 0)
            $sub->delete();
        else
            Subscriptions::create(['subscription_id' => $request->subscription_id, 'user_id' => $this->user->id]);

        return response()->json(['success' => true],Response::HTTP_CREATED );
    }

    /**
     * @return JsonResponse
     */
    public function getList(): JsonResponse
    {
        $items = [];
        $subscriptions = Subscriptions::where('user_id', $this->user->id)->get();

        if ($subscriptions)  $items = $subscriptions->pluck('subscription_id');

        return response()->json(['items' =>$items]);
    }

}
