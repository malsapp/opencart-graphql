<?php
namespace GQL\Resolvers;

trait CartTypeResolver {
    
    public function CartType_items ($root, $args, &$ctx) { return $root['items']; }

}
?>