<?php
namespace GQL;
require_once __dir__ . '/Resolvers/ProductType.php';
require_once __dir__ . '/Resolvers/ProductAttributeGroupType.php';
require_once __dir__ . '/Resolvers/ProductOptionType.php';
require_once __dir__ . '/Resolvers/ProductDiscountIdType.php';
require_once __dir__ . '/Resolvers/ProductImageType.php';
require_once __dir__ . '/Resolvers/CategoryType.php';
require_once __dir__ . '/Resolvers/CategoryFilterGroupType.php';
require_once __dir__ . '/Resolvers/ManufacturerType.php';
require_once __dir__ . '/Resolvers/InformationType.php';
require_once __dir__ . '/Resolvers/AddressType.php';
require_once __dir__ . '/Resolvers/CustomerGroupType.php';
require_once __dir__ . '/Resolvers/ZoneType.php';
require_once __dir__ . '/Resolvers/RewardType.php';
require_once __dir__ . '/Resolvers/TransactionType.php';
require_once __dir__ . '/Resolvers/OrderType.php';
require_once __dir__ . '/Resolvers/CartItemType.php';
require_once __dir__ . '/Resolvers/ReturnType.php';
require_once __dir__ . '/Resolvers/CustomerType.php';
require_once __dir__ . '/Resolvers/CartType.php';
require_once __dir__ . '/Resolvers/CouponType.php';
require_once __dir__ . '/Resolvers/RootQueryType.php';
require_once __dir__ . '/Resolvers/FaqCategoryType.php';
require_once __dir__ . '/Resolvers/FaqType.php';
require_once __dir__ . '/Resolvers/PhotoType.php';
require_once __dir__ . '/Resolvers/MutationType.php';

class Resolvers {
    use ProductTypeResolver;
    use ProductAttributeGroupTypeResolver;
    use ProductOptionTypeResolver;
    use ProductDiscountIdTypeResolver;
    use ProductImageTypeResolver;
    use CategoryTypeResolver;
    use CategoryFilterGroupTypeResolver;
    use ManufacturerTypeResolver;
    use InformationTypeResolver;
    use AddressTypeResolver;
    use CustomerGroupTypeResolver;
    use ZoneTypeResolver;
    use RewardTypeResolver;
    use TransactionTypeResolver;
    use OrderTypeResolver;
    use CartItemTypeResolver;
    use ReturnTypeResolver;
    use CustomerTypeResolver;
    use CartTypeResolver;
    use CouponTypeResolver;
    use FaqCategoryTypeResolver;
    use FaqTypeResolver;
    use PhotoTypeResolver;
    use RootQueryTypeResolver;
    use MutationTypeResolver;
}
?>