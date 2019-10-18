<?php

namespace App\GraphQL\Mutations;

use App\Feature;
use App\Product;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateProduct
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
        $feature_names = $args['product_feature_names'];
        $feature_descriptions = $args['product_feature_descriptions'];
        if (sizeof($feature_names) != sizeof($feature_descriptions))
        {
            return ;
        }
        $product = Product::create([
            'product_name' => $args['product_name'],
            'product_price' => $args['product_price'],
            'product_description' => $args['product_description']
        ]);
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
        return $product;
    }
}
