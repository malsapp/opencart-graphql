<?php
namespace GQL;

trait CartTypeResolver {
    
    public function CartType_items ($root, $args, &$ctx) { return $root['items']; }

}
?>