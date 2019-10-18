<?php

namespace App\GraphQL\Mutations;

use App\Feature;
use App\Product;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateProduct
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $product = Product::find($args['product_id']);
        $product_name = $args['product_name'];
        if ($product_name != null)
        {
            $product->product_name = $product_name;
        }
        $product_description = $args['product_description'];
        if ($product_description != null)
        {
            $product->product_descriotion = $product_description;
        }
        $product_price = $args['product_price'];
        if ($product_price != null)
        {
            $product->product_price = $product_price;
        }
        $product_features = $args['product_features'];
        if ($product_features != null)
        {
            $feature_names = $args['product_feature_names'];
            $feature_descriptions = $args['product_feature_descriptions'];
            if (sizeof($feature_names) != sizeof($feature_descriptions))
            {
                return ;
            } else {
                $product->features()->detach();
                foreach (array_combine($feature_names, $feature_descriptions) as $feature_name => $feature_description)
                {
                    $extracted_feature = Feature::where('feature_name', $feature_name)->first();
                    if($extracted_feature == null)
                    {
                        $feature = Feature::create([
                            'feature_name' => $feature_name,
                            'feature_description' => $feature_description
                        ]);
                        $product->features()->attach($feature);
                    } else {
                        $product->features()->attach($extracted_feature);
                    }
                }
            }
        }
        $product->save();
        return $product;
    }
}
