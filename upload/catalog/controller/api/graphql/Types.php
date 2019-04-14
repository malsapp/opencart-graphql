<?php
namespace GQL;

// require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/Resolvers/Resolvers.php';

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\Type;
use GraphQL\Schema;
use \Exception;

class Types {
    private static $types;
    private static $resolvers;

    public static $ProductType;
    public static $ProductAttributeType;
    public static $ProductAttributeGroupType;
    public static $ProductOptionType;
    public static $productOptionValueType;
    public static $ProductDiscountIdType;
    public static $ProductImageType;
    public static $ReviewType;
    public static $ReviewInput;
    public static $CategoryType;
    public static $CategoryFilterGroupType;
    public static $CategoryFilterDataType;
    public static $ManufacturerType;
    public static $InformationType;
    public static $SessionType;
    public static $AddressInput;
    public static $AddressType;
    public static $MethodExtensionType;
    public static $MethodQuoteType;
    public static $CustomerGroupType;
    public static $DownloadType;
    public static $LanguageType;
    public static $CountryType;
    public static $CurrencyType;
    public static $ZoneType;
    public static $RewardType;
    public static $TransactionType;
    public static $BannerType;
    public static $AccountActivityInput;
    public static $AffiliateActivityInput;
    public static $OrderInput;
    public static $OrderProductType;
    public static $OrderProductInput;
    public static $OrderProductOptionInput;
    public static $OrderVoucherInput;
    public static $VoucherType;
    public static $TotalsType;
    public static $OrderTotalsInput;
    public static $OrderType;
    public static $NewCustomSearchInput;
    public static $CustomFieldType;
    public static $CartItemType;
    public static $ReturnInput;
    public static $ReturnType;
    public static $ReturnHistoryType;
    public static $CustomerType;
    public static $CustomerInput;
    public static $RecurringType;
    public static $CartItemInput;
    public static $CartItemOptionInput;
    public static $CartType;
    public static $StoreType;
    public static $CountType;
    public static $CustomerEdit;
    public static $IPType;
    public static $CustomerLoginType;
    public static $DiscountType;
    public static $ShippingQuoteType;
    public static $CouponType;
    public static $SearchFilterType;
    public static $FaqCategoryType;
    public static $FaqType;
    public static $PhotoType;
    public static $PriceType;
    public static $ProductVariationType;
    public static $PhotoDescriptionType;
    public static $NewsType;
    public static $SiteInfoType;
    public static $ProductInput;
    public static $ProductDescriptionInput;
    public static $ProductAttributeInput;
    public static $ProductAttributeDescriptionInput;
    public static $ProductOptionInput;
    public static $ProductOptionValueInput;
    public static $ProductDiscountInput;
    public static $ProductSpecialInput;
    public static $ProductImageInput;
    public static $ProductRewardInput;
    public static $ProductLayoutInput;
    public static $ProductRecurringInput;
    public static $OrderStatus;
    public static $RootQueryType;
    public static $PostType;
    public static $PostsCategory;
    public static $MutationType;
    public static $MenuItemType;
    public static $schema;

    private function __clone () {}
    private function __construct () {
        self::$resolvers = new Resolvers ();

        static::$ProductType = new ObjectType ([
            'name' => 'ProductType',
            'fields'  => function () { return [
                'product_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'description' => [
                    'type' => Type::string ()
                ],
                'meta_title' => [
                    'type' => Type::string ()
                ],
                'meta_description' => [
                    'type' => Type::string ()
                ],
                'meta_keyword' => [
                    'type' => Type::string ()
                ],
                'tag' => [
                    'type' => Type::string ()
                ],
                'model' => [
                    'type' => Type::string ()
                ],
                'sku' => [
                    'type' => Type::string ()
                ],
                'upc' => [
                    'type' => Type::string ()
                ],
                'ean' => [
                    'type' => Type::string ()
                ],
                'jan' => [
                    'type' => Type::string ()
                ],
                'isbn' => [
                    'type' => Type::string ()
                ],
                'mpn' => [
                    'type' => Type::string ()
                ],
                'location' => [
                    'type' => Type::string ()
                ],
                'quantity' => [
                    'type' => Type::string ()
                ],
                'stock_status' => [
                    'type' => Type::string ()
                ],
                'image' => [
                    'type' => Type::string ()
                ],
                'manufacturer' => [
                    'type' => self::$ManufacturerType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductType_manufacturer ($root, $args, $ctx);
                    }
                ],
                'in_stock' => [
                    'type' => Type::boolean (),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductType_in_stock ($root, $args, $ctx);
                    }
                ],
                'price' => [
                    'type' => Type::string ()
                ],
                'special' => [
                    'type' => Type::string ()
                ],
                'formatted_price' => [
                    'type' => Type::string (),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductType_formatted ($root["price"], $ctx);
                    }
                ],
                'formatted_special' => [
                    'type' => Type::string (),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductType_formatted ($root["special"], $ctx);
                    }
                ],
                'reward' => [
                    'type' => Type::string ()
                ],
                'points' => [
                    'type' => Type::string ()
                ],
                'tax_class_id' => [
                    'type' => Type::id ()
                ],
                'date_available' => [
                    'type' => Type::string ()
                ],
                'weight' => [
                    'type' => Type::string ()
                ],
                'weight_class_id' => [
                    'type' => Type::id ()
                ],
                'length' => [
                    'type' => Type::float ()
                ],
                'width' => [
                    'type' => Type::float ()
                ],
                'height' => [
                    'type' => Type::float ()
                ],
                'length_class_id' => [
                    'type' => Type::id ()
                ],
                'subtract' => [
                    'type' => Type::string ()
                ],
                'rating' => [
                    'type' => Type::float ()
                ],
                'review_count' => [
                    'type' => Type::int ()
                ],
                'minimum' => [
                    'type' => Type::string ()
                ],
                'sort_order' => [
                    'type' => Type::string ()
                ],
                'status' => [
                    'type' => Type::string ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ],
                'date_modified' => [
                    'type' => Type::string ()
                ],
                'viewed' => [
                    'type' => Type::string ()
                ],
                'wishlist' => [
                    'type' => Type::boolean (),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductType_wishlist ($root, $args, $ctx);
                    }
                ],
                'categories' => [
                    'type' => Type::listOf (self::$CategoryType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductType_categories ($root, $args, $ctx);
                    }
                ],
                'attributes' => [
                    'type' => Type::listOf (self::$ProductAttributeGroupType),
                    'args' => [
                        'language_id' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductType_attributes ($root, $args, $ctx);
                    }
                ],
                'options' => [
                    'type' => Type::listOf (self::$ProductOptionType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductType_options ($root, $args, $ctx);
                    }
                ],
                'discounts' => [
                    'type' => Type::listOf (self::$ProductDiscountIdType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductType_discounts ($root, $args, $ctx);
                    }
                ],
                'images' => [
                    'type' => Type::listOf (self::$ProductImageType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductType_images ($root, $args, $ctx);
                    }
                ],
                'layout_id' => [
                    'type' => Type::int ()
                ]
            ]; }
        ]);

        static::$ProductAttributeType = new ObjectType ([
            'name' => 'ProductAttributeType',
            'fields'  => function () { return [
                'attribute_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'text' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$ProductAttributeGroupType = new ObjectType ([
            'name' => 'ProductAttributeGroupType',
            'fields'  => function () { return [
                'attribute_group_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'attribute' => [
                    'type' => Type::listOf (self::$ProductAttributeType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductAttributeGroupType_attribute ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        static::$ProductOptionType = new ObjectType ([
            'name' => 'ProductOptionType',
            'fields'  => function () { return [
                'product_option_id' => [
                    'type' => Type::id ()
                ],
                'product_option_value' => [
                    'type' => Type::listOf (self::$productOptionValueType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductOptionType_product_option_value ($root, $args, $ctx);
                    }
                ],
                'option_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'type' => [
                    'type' => Type::string ()
                ],
                'value' => [
                    'type' => Type::string ()
                ],
                'required' => [
                    'type' => Type::int ()
                ]
            ]; }
        ]);

        static::$productOptionValueType = new ObjectType ([
            'name' => 'productOptionValueType',
            'fields'  => function () { return [
                'product_option_value_id' => [
                    'type' => Type::id ()
                ],
                'option_value_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'image' => [
                    'type' => Type::string ()
                ],
                'quantity' => [
                    'type' => Type::int ()
                ],
                'subtract' => [
                    'type' => Type::int ()
                ],
                'price' => [
                    'type' => Type::float ()
                ],
                'price_prefix' => [
                    'type' => Type::string ()
                ],
                'weight' => [
                    'type' => Type::float ()
                ],
                'weight_prefix' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$ProductDiscountIdType = new ObjectType ([
            'name' => 'ProductDiscountIdType',
            'fields'  => function () { return [
                'product_discount_id' => [
                    'type' => Type::id ()
                ],
                'Product' => [
                    'type' => self::$ProductType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductDiscountIdType_Product ($root, $args, $ctx);
                    }
                ],
                'CustomerGroup' => [
                    'type' => self::$CustomerGroupType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductDiscountIdType_CustomerGroup ($root, $args, $ctx);
                    }
                ],
                'quantity' => [
                    'type' => Type::int ()
                ],
                'priority' => [
                    'type' => Type::int ()
                ],
                'price' => [
                    'type' => Type::float ()
                ],
                'date_start' => [
                    'type' => Type::string ()
                ],
                'date_end' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$ProductImageType = new ObjectType ([
            'name' => 'ProductImageType',
            'fields'  => function () { return [
                'product_image_id' => [
                    'type' => Type::id ()
                ],
                'product' => [
                    'type' => self::$ProductType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ProductImageType_product ($root, $args, $ctx);
                    }
                ],
                'image' => [
                    'type' => Type::string ()
                ],
                'sort_order' => [
                    'type' => Type::int ()
                ]
            ]; }
        ]);

        static::$ReviewType = new ObjectType ([
            'name' => 'ReviewType',
            'fields'  => function () { return [
                'review_id' => [
                    'type' => Type::id ()
                ],
                'author' => [
                    'type' => Type::string ()
                ],
                'rating' => [
                    'type' => Type::string ()
                ],
                'text' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$PostType = new ObjectType ([
            'name' => 'PostType',
            'fields'  => function () { return [
                'id' => [
                    'type' => Type::id ()
                ],
                'title' => [
                    'type' => Type::string ()
                ],
                'content' => [
                    'type' => Type::string ()
                ],
                'excerpt' => [
                    'type' => Type::string ()
                ],
                'date' => [
                    'type' => Type::string ()
                ],
            ]; }
        ]);

        static::$PostsCategory = new ObjectType ([
            'name' => 'PostsCategory',
            'fields'  => function () { return [
                'id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'count' => [
                    'type' => Type::int ()
                ],
                'parent' => [
                    'type' => self::$PostsCategory,
                    'resolve' => function ($root, $args, $ctx) {
                        // return self::$resolvers->PostsCategoryType_parent ($root, $args, $ctx);
                        return null;
                    }
                ],
                'posts' => [
                    'type' => Type::listOf (self::$PostType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->PostsCategoryType_posts ($root, $args, $ctx);
                    }
                ],
            ]; }
        ]);

        static::$ReviewInput = new InputObjectType ([
            'name' => 'ReviewInput',
            'fields'  => function () { return [
                'name' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'rating' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'text' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$CategoryType = new ObjectType ([
            'name' => 'CategoryType',
            'fields'  => function () { return [
                'category_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'image' => [
                    'type' => Type::string ()
                ],
                'top' => [
                    'type' => Type::int ()
                ],
                'column' => [
                    'type' => Type::int ()
                ],
                'status' => [
                    'type' => Type::int ()
                ],
                'description' => [
                    'type' => Type::string ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ],
                'date_modified' => [
                    'type' => Type::string ()
                ],
                'language' => [
                    'type' => self::$LanguageType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CategoryType_language ($root, $args, $ctx);
                    }
                ],
                'meta_title' => [
                    'type' => Type::string ()
                ],
                'meta_description' => [
                    'type' => Type::string ()
                ],
                'meta_keyword' => [
                    'type' => Type::string ()
                ],
                'store' => [
                    'type' => self::$StoreType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CategoryType_store ($root, $args, $ctx);
                    }
                ],
                'parent' => [
                    'type' => self::$CategoryType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CategoryType_parent ($root, $args, $ctx);
                    }
                ],
                'layout_id' => [
                    'type' => Type::int ()
                ],
                'products_count' => [
                    'type' => Type::int ()
                ],
                'products' => [
                    'type' => Type::listOf (self::$ProductType),
                    'args' => [
                        'limit' => Type::int (),
                        'start' => Type::int (),
                        'sort' => Type::string (),
                        'order' => Type::string ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CategoryType_products ($root, $args, $ctx);
                    }
                ],
                'categories' => [
                    'type' => Type::listOf (self::$CategoryType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CategoryType_categories ($root, $args, $ctx);
                    }
                ],
                'filters' => [
                    'type' => Type::listOf (self::$CategoryFilterGroupType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CategoryType_filters ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        static::$CategoryFilterGroupType = new ObjectType ([
            'name' => 'CategoryFilterGroupType',
            'fields'  => function () { return [
                'filter_group_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'filter' => [
                    'type' => Type::listOf (self::$CategoryFilterDataType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CategoryFilterGroupType_filter ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        static::$CategoryFilterDataType = new ObjectType ([
            'name' => 'CategoryFilterDataType',
            'fields'  => function () { return [
                'filter_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$ManufacturerType = new ObjectType ([
            'name' => 'ManufacturerType',
            'fields'  => function () { return [
                'manufacturer_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'image' => [
                    'type' => Type::string ()
                ],
                'sort_order' => [
                    'type' => Type::int ()
                ],
                'products' => [
                    'type' => Type::listOf (self::$ProductType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ManufacturerType_products ($root, $args, $ctx);
                    }
                ],
                'store' => [
                    'type' => self::$StoreType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ManufacturerType_store ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        static::$InformationType = new ObjectType ([
            'name' => 'InformationType',
            'fields'  => function () { return [
                'information_id' => [
                    'type' => Type::id ()
                ],
                'bottom' => [
                    'type' => Type::int ()
                ],
                'sort_order' => [
                    'type' => Type::int ()
                ],
                'status' => [
                    'type' => Type::int ()
                ],
                'language' => [
                    'type' => self::$LanguageType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->InformationType_language ($root, $args, $ctx);
                    }
                ],
                'title' => [
                    'type' => Type::string ()
                ],
                'description' => [
                    'type' => Type::string ()
                ],
                'meta_title' => [
                    'type' => Type::string ()
                ],
                'meta_description' => [
                    'type' => Type::string ()
                ],
                'meta_keyword' => [
                    'type' => Type::string ()
                ],
                'store' => [
                    'type' => self::$StoreType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->InformationType_store ($root, $args, $ctx);
                    }
                ],
                'layout_id' => [
                    'type' => Type::int ()
                ]
            ]; }
        ]);

        static::$SessionType = new ObjectType ([
            'name' => 'SessionType',
            'fields'  => function () { return [
                'id' => [
                    'type' => Type::nonNull (Type::id ())
                ]
            ]; }
        ]);

        static::$AddressInput = new InputObjectType ([
            'name' => 'AddressInput',
            'fields'  => function () { return [
                'firstname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'lastname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'company' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'address_1' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'address_2' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'postcode' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'city' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'zone_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'country_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'custom_field' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'default' => [
                    'type' => Type::boolean ()
                ]
            ]; }
        ]);

        static::$AddressType = new ObjectType ([
            'name' => 'AddressType',
            'fields'  => function () { return [
                'address_id' => [
                    'type' => Type::id ()
                ],
                'firstname' => [
                    'type' => Type::string ()
                ],
                'lastname' => [
                    'type' => Type::string ()
                ],
                'company' => [
                    'type' => Type::string ()
                ],
                'address_1' => [
                    'type' => Type::string ()
                ],
                'address_2' => [
                    'type' => Type::string ()
                ],
                'postcode' => [
                    'type' => Type::string ()
                ],
                'city' => [
                    'type' => Type::string ()
                ],
                'zone' => [
                    'type' => self::$ZoneType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->AddressType_zone ($root, $args, $ctx);
                    }
                ],
                'country' => [
                    'type' => self::$CountryType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->AddressType_country ($root, $args, $ctx);
                    }
                ],
                'custom_field' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$MethodExtensionType = new ObjectType ([
            'name' => 'MethodExtensionType',
            'fields'  => function () { return [
                'title' => [
                    'type' => Type::string ()
                ],
                'quote' => [
                    'type' => self::$MethodQuoteType
                ],
                'sort_order' => [
                    'type' => Type::string ()
                ],
                'error' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$MethodQuoteType = new ObjectType ([
            'name' => 'MethodQuoteType',
            'fields'  => function () { return [
                'code' => [
                    'type' => Type::string ()
                ],
                'title' => [
                    'type' => Type::string ()
                ],
                'cost' => [
                    'type' => Type::float ()
                ],
                'text' => [
                    'type' => Type::string ()
                ],
                'details' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$CustomerGroupType = new ObjectType ([
            'name' => 'CustomerGroupType',
            'fields'  => function () { return [
                'customer_group_id' => [
                    'type' => Type::id ()
                ],
                'approval' => [
                    'type' => Type::int ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'description' => [
                    'type' => Type::string ()
                ],
                'sort_order' => [
                    'type' => Type::int ()
                ],
                'language' => [
                    'type' => self::$LanguageType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CustomerGroupType_language ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        static::$DownloadType = new ObjectType ([
            'name' => 'DownloadType',
            'fields'  => function () { return [
                'download_id' => [
                    'type' => Type::id ()
                ],
                'order_id' => [
                    'type' => Type::id ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'filename' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'mask' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$LanguageType = new ObjectType ([
            'name' => 'LanguageType',
            'fields'  => function () { return [
                'language_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'code' => [
                    'type' => Type::string ()
                ],
                'locale' => [
                    'type' => Type::string ()
                ],
                'image' => [
                    'type' => Type::string ()
                ],
                'directory' => [
                    'type' => Type::string ()
                ],
                'sort_order' => [
                    'type' => Type::int ()
                ],
                'status' => [
                    'type' => Type::int ()
                ]
            ]; }
        ]);

        static::$CountryType = new ObjectType ([
            'name' => 'CountryType',
            'fields'  => function () { return [
                'country_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'iso_code_2' => [
                    'type' => Type::string ()
                ],
                'iso_code_3' => [
                    'type' => Type::string ()
                ],
                'address_format' => [
                    'type' => Type::string ()
                ],
                'postcode_required' => [
                    'type' => Type::string ()
                ],
                'status' => [
                    'type' => Type::int ()
                ]
            ]; }
        ]);

        static::$CurrencyType = new ObjectType ([
            'name' => 'CurrencyType',
            'fields'  => function () { return [
                'currency_id' => [
                    'type' => Type::id ()
                ],
                'title' => [
                    'type' => Type::string ()
                ],
                'code' => [
                    'type' => Type::string ()
                ],
                'symbol_left' => [
                    'type' => Type::string ()
                ],
                'symbol_right' => [
                    'type' => Type::string ()
                ],
                'decimal_place' => [
                    'type' => Type::int ()
                ],
                'value' => [
                    'type' => Type::float ()
                ],
                'status' => [
                    'type' => Type::int ()
                ],
                'date_modified' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$ZoneType = new ObjectType ([
            'name' => 'ZoneType',
            'fields'  => function () { return [
                'zone_id' => [
                    'type' => Type::id ()
                ],
                'country' => [
                    'type' => self::$CountryType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ZoneType_country ($root, $args, $ctx);
                    }
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'code' => [
                    'type' => Type::string ()
                ],
                'status' => [
                    'type' => Type::int ()
                ]
            ]; }
        ]);

        static::$RewardType = new ObjectType ([
            'name' => 'RewardType',
            'fields'  => function () { return [
                'customer_reward_id' => [
                    'type' => Type::id ()
                ],
                'customer' => [
                    'type' => self::$CustomerType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RewardType_customer ($root, $args, $ctx);
                    }
                ],
                'order_id' => [
                    'type' => Type::int ()
                ],
                'description' => [
                    'type' => Type::string ()
                ],
                'points' => [
                    'type' => Type::int ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$TransactionType = new ObjectType ([
            'name' => 'TransactionType',
            'fields'  => function () { return [
                'customer_transaction_id' => [
                    'type' => Type::id ()
                ],
                'customer' => [
                    'type' => self::$CustomerType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->TransactionType_customer ($root, $args, $ctx);
                    }
                ],
                'order_id' => [
                    'type' => Type::int ()
                ],
                'description' => [
                    'type' => Type::string ()
                ],
                'amount' => [
                    'type' => Type::float ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$BannerType = new ObjectType ([
            'name' => 'BannerType',
            'fields'  => function () { return [
                'banner_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'status' => [
                    'type' => Type::int ()
                ],
                'banner_image_id' => [
                    'type' => Type::id ()
                ],
                'language_id' => [
                    'type' => Type::id ()
                ],
                'title' => [
                    'type' => Type::string ()
                ],
                'link' => [
                    'type' => Type::string ()
                ],
                'image' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$AccountActivityInput = new InputObjectType ([
            'name' => 'AccountActivityInput',
            'fields'  => function () { return [
                'account_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'data' => [
                    'type' => Type::listOf (Type::string ())
                ]
            ]; }
        ]);

        static::$AffiliateActivityInput = new InputObjectType ([
            'name' => 'AffiliateActivityInput',
            'fields'  => function () { return [
                'affiliate_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'data' => [
                    'type' => Type::listOf (Type::string ())
                ]
            ]; }
        ]);

        static::$OrderInput = new InputObjectType ([
            'name' => 'OrderInput',
            'fields'  => function () { return [
                'invoice_prefix' => [
                    'type' => Type::string ()
                ],
                'store_id' => [
                    'type' => Type::id ()
                ],
                'store_name' => [
                    'type' => Type::string ()
                ],
                'store_url' => [
                    'type' => Type::string ()
                ],
                'customer_id' => [
                    'type' => Type::id ()
                ],
                'customer_group_id' => [
                    'type' => Type::id ()
                ],
                'firstname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'lastname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'email' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'telephone' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'fax' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'custom_field' => [
                    'type' => Type::string ()
                ],
                'payment_firstname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'payment_lastname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'payment_company' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'payment_address_1' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'payment_address_2' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'payment_city' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'payment_postcode' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'payment_country' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'payment_country_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'payment_zone' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'payment_zone_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'payment_address_format' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'payment_custom_field' => [
                    'type' => Type::string ()
                ],
                'payment_method' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'payment_code' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_firstname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_lastname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_company' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_address_1' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_address_2' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_city' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_postcode' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_country' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_country_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'shipping_zone' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_zone_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'shipping_address_format' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_custom_field' => [
                    'type' => Type::string ()
                ],
                'shipping_method' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'shipping_code' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'comment' => [
                    'type' => Type::string ()
                ],
                'total' => [
                    'type' => Type::float ()
                ],
                'affiliate_id' => [
                    'type' => Type::id ()
                ],
                'commission' => [
                    'type' => Type::float ()
                ],
                'marketing_id' => [
                    'type' => Type::id ()
                ],
                'tracking' => [
                    'type' => Type::string ()
                ],
                'language_id' => [
                    'type' => Type::id ()
                ],
                'currency_id' => [
                    'type' => Type::id ()
                ],
                'currency_code' => [
                    'type' => Type::float ()
                ],
                'currency_value' => [
                    'type' => Type::string ()
                ],
                'ip' => [
                    'type' => Type::string ()
                ],
                'forwarded_ip' => [
                    'type' => Type::string ()
                ],
                'user_agent' => [
                    'type' => Type::string ()
                ],
                'accept_language' => [
                    'type' => Type::string ()
                ],
                'products' => [
                    'type' => Type::listOf (self::$OrderProductInput)
                ],
                'vouchers' => [
                    'type' => Type::listOf (self::$OrderVoucherInput)
                ],
                'totals' => [
                    'type' => Type::listOf (self::$OrderTotalsInput)
                ]
            ]; }
        ]);

        static::$OrderProductInput = new InputObjectType ([
            'name' => 'OrderProductInput',
            'fields'  => function () { return [
                'product_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'name' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'model' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'quantity' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'price' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'total' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'tax' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'reward' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'option' => [
                    'type' => Type::listOf (self::$OrderProductOptionInput)
                ]
            ]; }
        ]);

        static::$OrderProductType = new ObjectType ([
            'name' => 'OrderProductType',
            'fields'  => function () { return [
                'order_product_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'order_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'product_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'name' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'model' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'quantity' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'price' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'total' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'tax' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'reward' => [
                    'type' => Type::nonNull (Type::int ())
                ]
            ]; }
        ]);

        static::$OrderProductOptionInput = new InputObjectType ([
            'name' => 'OrderProductOptionInput',
            'fields'  => function () { return [
                'product_option_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'product_option_value_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'name' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'value' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'type' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$OrderVoucherInput = new InputObjectType ([
            'name' => 'OrderVoucherInput',
            'fields'  => function () { return [
                'description' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'code' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'from_name' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'from_email' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'to_name' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'to_email' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'voucher_theme_id' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'message' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'amount' => [
                    'type' => Type::nonNull (Type::float ())
                ]
            ]; }
        ]);

        static::$VoucherType = new ObjectType ([
            'name' => 'VoucherType',
            'fields'  => function () { return [
                'description' => [
                    'type' => Type::string ()
                ],
                'code' => [
                    'type' => Type::string ()
                ],
                'from_name' => [
                    'type' => Type::string ()
                ],
                'from_email' => [
                    'type' => Type::string ()
                ],
                'to_name' => [
                    'type' => Type::string ()
                ],
                'to_email' => [
                    'type' => Type::string ()
                ],
                'voucher_theme_id' => [
                    'type' => Type::string ()
                ],
                'message' => [
                    'type' => Type::string ()
                ],
                'amount' => [
                    'type' => Type::float ()
                ]
            ]; }
        ]);

        static::$TotalsType = new ObjectType ([
            'name' => 'TotalsType',
            'fields'  => function () { return [
                'code' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'title' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'value' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'sort_order' => [
                    'type' => Type::nonNull (Type::int ())
                ]
            ]; }
        ]);

        static::$OrderTotalsInput = new InputObjectType ([
            'name' => 'OrderTotalsInput',
            'fields'  => function () { return [
                'code' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'title' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'value' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'sort_order' => [
                    'type' => Type::nonNull (Type::int ())
                ]
            ]; }
        ]);

        static::$OrderType = new ObjectType ([
            'name' => 'OrderType',
            'fields'  => function () { return [
                'order_id' => [
                    'type' => Type::id ()
                ],
                'invoice_no' => [
                    'type' => Type::int ()
                ],
                'invoice_prefix' => [
                    'type' => Type::string ()
                ],
                'store' => [
                    'type' => self::$StoreType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->OrderType_store ($root, $args, $ctx);
                    }
                ],
                'products' => [
                    'type' => Type::listOf (self::$OrderProductType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->OrderType_products ($root, $args, $ctx);
                    }
                ],
                'store_name' => [
                    'type' => Type::string ()
                ],
                'store_url' => [
                    'type' => Type::string ()
                ],
                'customer_id' => [
                    'type' => Type::id ()
                ],
                'firstname' => [
                    'type' => Type::string ()
                ],
                'lastname' => [
                    'type' => Type::string ()
                ],
                'email' => [
                    'type' => Type::string ()
                ],
                'telephone' => [
                    'type' => Type::string ()
                ],
                'fax' => [
                    'type' => Type::string ()
                ],
                'custom_field' => [
                    'type' => Type::string ()
                ],
                'payment_firstname' => [
                    'type' => Type::string ()
                ],
                'payment_lastname' => [
                    'type' => Type::string ()
                ],
                'payment_company' => [
                    'type' => Type::string ()
                ],
                'payment_address_1' => [
                    'type' => Type::string ()
                ],
                'payment_address_2' => [
                    'type' => Type::string ()
                ],
                'payment_postcode' => [
                    'type' => Type::string ()
                ],
                'payment_city' => [
                    'type' => Type::string ()
                ],
                'paymentZone' => [
                    'type' => self::$ZoneType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->OrderType_paymentZone ($root, $args, $ctx);
                    }
                ],
                'paymentCountry' => [
                    'type' => self::$CountryType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->OrderType_paymentCountry ($root, $args, $ctx);
                    }
                ],
                'payment_custom_field' => [
                    'type' => Type::string ()
                ],
                'payment_method' => [
                    'type' => Type::string ()
                ],
                'payment_code' => [
                    'type' => Type::string ()
                ],
                'shipping_firstname' => [
                    'type' => Type::string ()
                ],
                'shipping_lastname' => [
                    'type' => Type::string ()
                ],
                'shipping_company' => [
                    'type' => Type::string ()
                ],
                'shipping_address_1' => [
                    'type' => Type::string ()
                ],
                'shipping_address_2' => [
                    'type' => Type::string ()
                ],
                'shipping_postcode' => [
                    'type' => Type::string ()
                ],
                'shipping_city' => [
                    'type' => Type::string ()
                ],
                'shippingZone' => [
                    'type' => self::$ZoneType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->OrderType_shippingZone ($root, $args, $ctx);
                    }
                ],
                'shippingCountry' => [
                    'type' => self::$CountryType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->OrderType_shippingCountry ($root, $args, $ctx);
                    }
                ],
                'shipping_custom_field' => [
                    'type' => Type::string ()
                ],
                'shipping_method' => [
                    'type' => Type::string ()
                ],
                'shipping_code' => [
                    'type' => Type::string ()
                ],
                'comment' => [
                    'type' => Type::string ()
                ],
                'total' => [
                    'type' => Type::string ()
                ],
                'order_status_id' => [
                    'type' => Type::string ()
                ],
                'order_status' => [
                    'type' => Type::string ()
                ],
                'affiliate_id' => [
                    'type' => Type::id ()
                ],
                'commission' => [
                    'type' => Type::string ()
                ],
                'language' => [
                    'type' => self::$LanguageType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->OrderType_language ($root, $args, $ctx);
                    }
                ],
                'currency' => [
                    'type' => self::$CurrencyType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->OrderType_currency ($root, $args, $ctx);
                    }
                ],
                'ip' => [
                    'type' => Type::string ()
                ],
                'forwarded_ip' => [
                    'type' => Type::string ()
                ],
                'user_agent' => [
                    'type' => Type::string ()
                ],
                'accept_language' => [
                    'type' => Type::string ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ],
                'date_modified' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$NewCustomSearchInput = new InputObjectType ([
            'name' => 'NewCustomSearchInput',
            'fields'  => function () { return [
                'customer_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'keyword' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'category_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'sub_category' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'description' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'products' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'ip' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$CustomFieldType = new ObjectType ([
            'name' => 'CustomFieldType',
            'fields'  => function () { return [
                'custom_field_id' => [
                    'type' => Type::id ()
                ],
                'custom_field_value' => [
                    'type' => Type::string ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'type' => [
                    'type' => Type::string ()
                ],
                'value' => [
                    'type' => Type::string ()
                ],
                'validation' => [
                    'type' => Type::string ()
                ],
                'location' => [
                    'type' => Type::string ()
                ],
                'required' => [
                    'type' => Type::boolean ()
                ],
                'sort_order' => [
                    'type' => Type::int ()
                ]
            ]; }
        ]);

        static::$CartItemType = new ObjectType ([
            'name' => 'CartItemType',
            'fields'  => function () { return [
                'cart_id' => [
                    'type' => Type::id ()
                ],
                'product_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'model' => [
                    'type' => Type::string ()
                ],
                'shipping' => [
                    'type' => Type::string ()
                ],
                'image' => [
                    'type' => Type::string ()
                ],
                'option' => [
                    'type' => Type::listOf (self::$ProductOptionType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CartItemType_option ($root, $args, $ctx);
                    }
                ],
                'download' => [
                    'type' => Type::listOf (self::$DownloadType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CartItemType_download ($root, $args, $ctx);
                    }
                ],
                'quantity' => [
                    'type' => Type::int ()
                ],
                'minimum' => [
                    'type' => Type::int ()
                ],
                'subtract' => [
                    'type' => Type::int ()
                ],
                'stock' => [
                    'type' => Type::int ()
                ],
                'price' => [
                    'type' => Type::float ()
                ],
                'total' => [
                    'type' => Type::float ()
                ],
                'reward' => [
                    'type' => Type::int ()
                ],
                'points' => [
                    'type' => Type::int ()
                ],
                'tax_class_id' => [
                    'type' => Type::id ()
                ],
                'weight' => [
                    'type' => Type::float ()
                ],
                'weight_class_id' => [
                    'type' => Type::id ()
                ],
                'length' => [
                    'type' => Type::float ()
                ],
                'width' => [
                    'type' => Type::float ()
                ],
                'height' => [
                    'type' => Type::float ()
                ],
                'length_class_id' => [
                    'type' => Type::id ()
                ],
                'recurring' => [
                    'type' => Type::listOf (self::$RecurringType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CartItemType_recurring ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        static::$ReturnInput = new InputObjectType ([
            'name' => 'ReturnInput',
            'fields'  => function () { return [
                'order_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'firstname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'lastname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'email' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'telephone' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'product' => [
                    'type' => Type::string ()
                ],
                'model' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'quantity' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'opened' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'return_reason_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'comment' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'date_ordered' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$ReturnType = new ObjectType ([
            'name' => 'ReturnType',
            'fields'  => function () { return [
                'return_id' => [
                    'type' => Type::id ()
                ],
                'order' => [
                    'type' => self::$OrderType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->ReturnType_order ($root, $args, $ctx);
                    }
                ],
                'firstname' => [
                    'type' => Type::string ()
                ],
                'firstname' => [
                    'type' => Type::string ()
                ],
                'email' => [
                    'type' => Type::string ()
                ],
                'telephone' => [
                    'type' => Type::string ()
                ],
                'product' => [
                    'type' => Type::string ()
                ],
                'model' => [
                    'type' => Type::string ()
                ],
                'quantity' => [
                    'type' => Type::int ()
                ],
                'opened' => [
                    'type' => Type::boolean ()
                ],
                'reason' => [
                    'type' => Type::int ()
                ],
                'action' => [
                    'type' => Type::int ()
                ],
                'status' => [
                    'type' => Type::int ()
                ],
                'comment' => [
                    'type' => Type::string ()
                ],
                'date_ordered' => [
                    'type' => Type::string ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ],
                'date_modified' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$ReturnHistoryType = new ObjectType ([
            'name' => 'ReturnHistoryType',
            'fields'  => function () { return [
                'comment' => [
                    'type' => Type::string ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ],
                'status' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$CustomerType = new ObjectType ([
            'name' => 'CustomerType',
            'fields'  => function () { return [
                'customer_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'customer_group' => [
                    'type' => self::$CustomerGroupType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CustomerType_customer_group ($root, $args, $ctx);
                    }
                ],
                'store' => [
                    'type' => self::$StoreType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CustomerType_store ($root, $args, $ctx);
                    }
                ],
                'language' => [
                    'type' => self::$LanguageType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CustomerType_language ($root, $args, $ctx);
                    }
                ],
                'firstname' => [
                    'type' => Type::string ()
                ],
                'lastname' => [
                    'type' => Type::string ()
                ],
                'email' => [
                    'type' => Type::string ()
                ],
                'telephone' => [
                    'type' => Type::string ()
                ],
                'fax' => [
                    'type' => Type::string ()
                ],
                'password' => [
                    'type' => Type::string ()
                ],
                'salt' => [
                    'type' => Type::string ()
                ],
                'cart' => [
                    'type' => Type::string ()
                ],
                'wishlist' => [
                    'type' => Type::string ()
                ],
                'newsletter' => [
                    'type' => Type::boolean ()
                ],
                'address_id' => [
                    'type' => Type::int ()
                ],
                'custom_field' => [
                    'type' => Type::string ()
                ],
                'ip' => [
                    'type' => Type::string ()
                ],
                'status' => [
                    'type' => Type::boolean ()
                ],
                'approved' => [
                    'type' => Type::boolean ()
                ],
                'safe' => [
                    'type' => Type::boolean ()
                ],
                'token' => [
                    'type' => Type::string ()
                ],
                'code' => [
                    'type' => Type::string ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$CustomerInput = new InputObjectType ([
            'name' => 'CustomerInput',
            'fields'  => function () { return [
                'customer_group_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'firstname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'lastname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'email' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'telephone' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'address_1' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'address_2' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'city' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'country_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'zone_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'password' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'confirm' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'agree' => [
                    'type' => Type::nonNull (Type::boolean ())
                ],
                'fax' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'company' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'postcode' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$RecurringType = new ObjectType ([
            'name' => 'RecurringType',
            'fields'  => function () { return [
                'recurring_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'frequency' => [
                    'type' => Type::float ()
                ],
                'price' => [
                    'type' => Type::float ()
                ],
                'cycle' => [
                    'type' => Type::string ()
                ],
                'duration' => [
                    'type' => Type::float ()
                ],
                'trial' => [
                    'type' => Type::boolean ()
                ],
                'trial_frequency' => [
                    'type' => Type::float ()
                ],
                'trial_price' => [
                    'type' => Type::float ()
                ],
                'trial_cycle' => [
                    'type' => Type::float ()
                ],
                'trial_duration' => [
                    'type' => Type::float ()
                ]
            ]; }
        ]);

        static::$CartItemInput = new InputObjectType ([
            'name' => 'CartItemInput',
            'fields'  => function () { return [
                'product_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'quantity' => [
                    'type' => Type::int ()
                ],
                'options' => [
                    'type' => Type::listOf (self::$CartItemOptionInput)
                ],
                'recurring_id' => [
                    'type' => Type::id ()
                ]
            ]; }
        ]);

        static::$CartItemOptionInput = new InputObjectType ([
            'name' => 'CartItemOptionInput',
            'fields'  => function () { return [
                'option_id' => [
                    'type' => Type::nonNull (Type::id ())
                ],
                'value' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$CartType = new ObjectType ([
            'name' => 'CartType',
            'fields'  => function () { return [
                'weight' => [
                    'type' => Type::float ()
                ],
                'tax' => [
                    'type' => Type::float ()
                ],
                'total' => [
                    'type' => Type::float ()
                ],
                'subtotal' => [
                    'type' => Type::float ()
                ],
                'coupon_discount' => [
                    'type' => Type::float ()
                ],
                'coupon_code' => [
                    'type' => Type::id ()
                ],
                'has_stock' => [
                    'type' => Type::boolean ()
                ],
                'has_shipping' => [
                    'type' => Type::boolean ()
                ],
                'has_download' => [
                    'type' => Type::boolean ()
                ],
                'totals' => [
                    'type' => Type::listOf (self::$TotalsType)
                ],
                'items' => [
                    'type' => Type::listOf (self::$CartItemType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CartType_items ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        static::$StoreType = new ObjectType ([
            'name' => 'StoreType',
            'fields'  => function () { return [
                'store_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'url' => [
                    'type' => Type::string ()
                ],
                'ssl' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$CountType = new ObjectType ([
            'name' => 'CountType',
            'fields'  => function () { return [
                'count' => [
                    'type' => Type::int ()
                ]
            ]; }
        ]);

        static::$CustomerEdit = new InputObjectType ([
            'name' => 'CustomerEdit',
            'fields'  => function () { return [
                'firstname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'lastname' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'email' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'telephone' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'fax' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'custom_field' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$IPType = new ObjectType ([
            'name' => 'IPType',
            'fields'  => function () { return [
                'customer_ip_id' => [
                    'type' => Type::id ()
                ],
                'customer_id' => [
                    'type' => Type::string ()
                ],
                'ip' => [
                    'type' => Type::string ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$CustomerLoginType = new ObjectType ([
            'name' => 'CustomerLoginType',
            'fields'  => function () { return [
                'customer_login_id' => [
                    'type' => Type::id ()
                ],
                'email' => [
                    'type' => Type::string ()
                ],
                'ip' => [
                    'type' => Type::string ()
                ],
                'total' => [
                    'type' => Type::int ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ],
                'date_modified' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$DiscountType = new ObjectType ([
            'name' => 'DiscountType',
            'fields'  => function () { return [
                'quantity' => [
                    'type' => Type::string ()
                ],
                'price_execluding_tax' => [
                    'type' => Type::string ()
                ],
                'price' => [
                    'type' => Type::string ()
                ],
                'price_formatted' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$ShippingQuoteType = new ObjectType ([
            'name' => 'ShippingQuoteType',
            'fields'  => function () { return [
                'title' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'code' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'cost' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'tax_class_id' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'text' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$CouponType = new ObjectType ([
            'name' => 'CouponType',
            'fields'  => function () { return [
                'id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::string ()
                ],
                'code' => [
                    'type' => Type::string ()
                ],
                'discount' => [
                    'type' => Type::int ()
                ],
                'total' => [
                    'type' => Type::float ()
                ],
                'categories' => [
                    'type' => Type::listOf (self::$CategoryType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CouponType_categories ($root, $args, $ctx);
                    }
                ],
                'products' => [
                    'type' => Type::listOf (self::$ProductType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->CouponType_products ($root, $args, $ctx);
                    }
                ],
                'date_start' => [
                    'type' => Type::string ()
                ],
                'date_end' => [
                    'type' => Type::string ()
                ],
                'logged' => [
                    'type' => Type::boolean ()
                ],
                'shipping' => [
                    'type' => Type::boolean ()
                ],
                'uses_total' => [
                    'type' => Type::int ()
                ],
                'uses_customer' => [
                    'type' => Type::int ()
                ]
            ]; }
        ]);

        static::$SearchFilterType = new ObjectType ([
            'name' => 'SearchFilterType',
            'fields'  => function () { return [
                'field' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'operand' => [
                    'type' => Type::string ()
                ],
                'logical_operand' => [
                    'type' => Type::string ()
                ],
                'value' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$FaqCategoryType = new ObjectType ([
            'name' => 'FaqCategoryType',
            'fields'  => function () { return [
                'faqcategory_id' => [
                    'type' => Type::id ()
                ],
                'title' => [
                    'type' => Type::string ()
                ],
                'description' => [
                    'type' => Type::string ()
                ],
                'language_id' => [
                    'type' => Type::int ()
                ],
                'sort_order' => [
                    'type' => Type::int ()
                ],
                'status' => [
                    'type' => Type::boolean ()
                ],
                'store_id' => [
                    'type' => Type::int ()
                ],
                'layout_id' => [
                    'type' => Type::int ()
                ],
                'faqs' => [
                    'type' => Type::listOf (self::$FaqType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->FaqCategoryType_faqs ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        static::$FaqType = new ObjectType ([
            'name' => 'FaqType',
            'fields'  => function () { return [
                'faq_id' => [
                    'type' => Type::id ()
                ],
                'title' => [
                    'type' => Type::string ()
                ],
                'description' => [
                    'type' => Type::string ()
                ],
                'sort_order' => [
                    'type' => Type::int ()
                ],
                'status' => [
                    'type' => Type::boolean ()
                ],
                'store_id' => [
                    'type' => Type::int ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ],
                'date_modified' => [
                    'type' => Type::string ()
                ],
                'faqcategory_id' => [
                    'type' => Type::int ()
                ],
                'viewed' => [
                    'type' => Type::int ()
                ],
                'language_id' => [
                    'type' => Type::int ()
                ],
                'faqCategory' => [
                    'type' => self::$FaqCategoryType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->FaqType_faqCategory ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        static::$PhotoType = new ObjectType ([
            'name' => 'PhotoType',
            'fields'  => function () { return [
                'photo_id' => [
                    'type' => Type::id ()
                ],
                'image' => [
                    'type' => Type::string ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ],
                'status' => [
                    'type' => Type::boolean ()
                ],
                'description' => [
                    'type' => Type::listOf (self::$PhotoDescriptionType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->PhotoType_description ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        static::$PriceType = new ObjectType ([
            'name' => 'PriceType',
            'fields'  => function () { return [
                'price' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$PhotoDescriptionType = new ObjectType ([
            'name' => 'PhotoDescriptionType',
            'fields'  => function () { return [
                'title' => [
                    'type' => Type::string ()
                ],
                'description' => [
                    'type' => Type::string ()
                ],
                'short_description' => [
                    'type' => Type::string ()
                ],
                'language_id' => [
                    'type' => Type::int ()
                ]
            ]; }
        ]);

        static::$ProductVariationType = new ObjectType ([
            'name' => 'ProductVariationType',
            'fields'  => function () { return [
                'variation_id' => [
                    'type' => Type::id ()
                ],
                'description' => [
                    'type' => Type::string ()
                ],
                'price' => [
                    'type' => Type::string ()
                ],
                'sale_price' => [
                    'type' => Type::string ()
                ],
                'image' => [
                    'type' => Type::string ()
                ],
                'weight' => [
                    'type' => Type::float ()
                ],
                'quantity' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$NewsType = new ObjectType ([
            'name' => 'NewsType',
            'fields'  => function () { return [
                'news_id' => [
                    'type' => Type::id ()
                ],
                'title' => [
                    'type' => Type::string ()
                ],
                'description' => [
                    'type' => Type::string ()
                ],
                'short_description' => [
                    'type' => Type::string ()
                ],
                'image' => [
                    'type' => Type::string ()
                ],
                'status' => [
                    'type' => Type::boolean ()
                ],
                'language_id' => [
                    'type' => Type::int ()
                ],
                'date_added' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$SiteInfoType = new ObjectType ([
            'name' => 'SiteInfoType',
            'fields'  => function () { return [
                'phpversion' => [
                    'type' => Type::string ()
                ],
                'mysqlversion' => [
                    'type' => Type::string ()
                ],
                'phpinfo' => [
                    'type' => Type::string ()
                ],
                'pluginversion' => [
                    'type' => Type::string ()
                ]
            ]; }
        ]);

        static::$ProductInput = new InputObjectType ([
            'name' => 'ProductInput',
            'fields'  => function () { return [
                'model' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'sku' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'upc' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'ean' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'jan' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'isbn' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'mpn' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'location' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'quantity' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'minimum' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'subtract' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'stock_status_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'date_available' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'manufacturer_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'shipping' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'price' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'points' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'weight' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'weight_class_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'length' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'width' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'height' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'length_class_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'status' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'tax_class_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'sort_order' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'image' => [
                    'type' => Type::string ()
                ],
                'product_attribute' => [
                    'type' => Type::listOf (self::$ProductAttributeInput)
                ],
                'product_store' => [
                    'type' => Type::id ()
                ],
                'product_description' => [
                    'type' => Type::listOf (self::$ProductDescriptionInput)
                ],
                'product_option' => [
                    'type' => Type::listOf (self::$ProductOptionInput)
                ],
                'product_discount' => [
                    'type' => Type::listOf (self::$ProductDiscountInput)
                ],
                'product_special' => [
                    'type' => Type::listOf (self::$ProductSpecialInput)
                ],
                'product_image' => [
                    'type' => Type::listOf (self::$ProductImageInput)
                ],
                'product_download' => [
                    'type' => Type::listOf (Type::int ())
                ],
                'product_category' => [
                    'type' => Type::listOf (Type::int ())
                ],
                'product_filter' => [
                    'type' => Type::listOf (Type::int ())
                ],
                'product_related' => [
                    'type' => Type::listOf (Type::int ())
                ],
                'product_reward' => [
                    'type' => Type::listOf (self::$ProductRewardInput)
                ],
                'product_layout' => [
                    'type' => Type::listOf (self::$ProductLayoutInput)
                ],
                'keyword' => [
                    'type' => Type::string ()
                ],
                'product_recurring' => [
                    'type' => Type::listOf (self::$ProductRecurringInput)
                ]
            ]; }
        ]);

        static::$ProductDescriptionInput = new InputObjectType ([
            'name' => 'ProductDescriptionInput',
            'fields'  => function () { return [
                'language_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'product_id' => [
                    'type' => Type::id ()
                ],
                'name' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'description' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'tag' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'meta_title' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'meta_description' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'meta_keyword' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$ProductAttributeInput = new InputObjectType ([
            'name' => 'ProductAttributeInput',
            'fields'  => function () { return [
                'attribute_id' => [
                    'type' => Type::id ()
                ],
                'product_attribute_description' => [
                    'type' => Type::listOf (self::$ProductAttributeDescriptionInput)
                ]
            ]; }
        ]);

        static::$ProductAttributeDescriptionInput = new InputObjectType ([
            'name' => 'ProductAttributeDescriptionInput',
            'fields'  => function () { return [
                'language_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'text' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$ProductOptionInput = new InputObjectType ([
            'name' => 'ProductOptionInput',
            'fields'  => function () { return [
                'option_id' => [
                    'type' => Type::id ()
                ],
                'value' => [
                    'type' => Type::string ()
                ],
                'required' => [
                    'type' => Type::int ()
                ],
                'product_option_value' => [
                    'type' => Type::listOf (self::$ProductOptionValueInput)
                ]
            ]; }
        ]);

        static::$ProductOptionValueInput = new InputObjectType ([
            'name' => 'ProductOptionValueInput',
            'fields'  => function () { return [
                'option_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'option_value_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'quantity' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'subtract' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'price' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'price_prefix' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'points' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'points_prefix' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'weight' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'weight_prefix' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$ProductDiscountInput = new InputObjectType ([
            'name' => 'ProductDiscountInput',
            'fields'  => function () { return [
                'customer_group_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'quantity' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'priority' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'price' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'date_start' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'date_end' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$ProductSpecialInput = new InputObjectType ([
            'name' => 'ProductSpecialInput',
            'fields'  => function () { return [
                'customer_group_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'priority' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'price' => [
                    'type' => Type::nonNull (Type::float ())
                ],
                'date_start' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'date_end' => [
                    'type' => Type::nonNull (Type::string ())
                ]
            ]; }
        ]);

        static::$ProductImageInput = new InputObjectType ([
            'name' => 'ProductImageInput',
            'fields'  => function () { return [
                'image' => [
                    'type' => Type::nonNull (Type::string ())
                ],
                'sort_order' => [
                    'type' => Type::nonNull (Type::int ())
                ]
            ]; }
        ]);

        static::$MenuItemType = new ObjectType ([
            'name' => 'MenuItemType',
            'fields'  => function () { return [
                'item_id' => [
                    'type' => Type::id ()
                ],
                'object_id' => [
                    'type' => Type::id ()
                ],
                'object_type' => [
                    'type' => Type::string ()
                ],
                'url' => [
                    'type' => Type::string ()
                ],
                'title' => [
                    'type' => Type::string ()
                ],
                'order' => [
                    'type' => Type::int ()
                ],
            ]; }
        ]);

        static::$ProductRewardInput = new InputObjectType ([
            'name' => 'ProductRewardInput',
            'fields'  => function () { return [
                'customer_group_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'points' => [
                    'type' => Type::nonNull (Type::int ())
                ]
            ]; }
        ]);

        static::$ProductLayoutInput = new InputObjectType ([
            'name' => 'ProductLayoutInput',
            'fields'  => function () { return [
                'store_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'layout_id' => [
                    'type' => Type::nonNull (Type::int ())
                ]
            ]; }
        ]);

        static::$ProductRecurringInput = new InputObjectType ([
            'name' => 'ProductRecurringInput',
            'fields'  => function () { return [
                'customer_group_id' => [
                    'type' => Type::nonNull (Type::int ())
                ],
                'recurring_id' => [
                    'type' => Type::nonNull (Type::int ())
                ]
            ]; }
        ]);

        static::$OrderStatus = new ObjectType ([
            'name' => 'OrderStatus',
            'fields' => function () { return [
                'order_status_id' => Type::id (),
                'name' => Type::string ()
            ]; }
        ]);

        static::$RootQueryType = new ObjectType ([
            'name' => 'RootQueryType',
            'fields'  => function () { return [
                'product' => [
                    'type' => self::$ProductType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_product ($root, $args, $ctx);
                    }
                ],
                'products' => [
                    'type' => Type::listOf (self::$ProductType),
                    'args' => [
                        'filter_category_id' => Type::int (),
                        'filter_sub_category' => Type::int (),
                        'filter_filter' => Type::string (),
                        'filter_name' => Type::string (),
                        'filter_tag' => Type::string (),
                        'filter_description' => Type::string (),
                        'filter_model' => Type::string (),
                        'filter_price' => Type::string (),
                        'filter_quantity' => Type::string (),
                        'filter_status' => Type::string (),
                        'filter_image' => Type::string (),
                        'filter_manufacturer_id' => Type::int (),
                        'sort' => Type::string (),
                        'order' => Type::string (),
                        'start' => Type::int (),
                        'limit' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_products ($root, $args, $ctx);
                    }
                ],
                'compareProducts' => [
                    'type' => Type::listOf (self::$ProductType),
                    'args' => [
                        'ids' => Type::nonNull (Type::listOf (Type::id ()))
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_compareProducts ($root, $args, $ctx);
                    }
                ],
                'bestsellerProducts' => [
                    'type' => Type::listOf (self::$ProductType),
                    'args' => [
                        'limit' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_bestsellerProducts ($root, $args, $ctx);
                    }
                ],
                'relatedProducts' => [
                    'type' => Type::listOf (self::$ProductType),
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_relatedProducts ($root, $args, $ctx);
                    }
                ],
                'latestProducts' => [
                    'type' => Type::listOf (self::$ProductType),
                    'args' => [
                        'limit' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_latestProducts ($root, $args, $ctx);
                    }
                ],
                'popularProducts' => [
                    'type' => Type::listOf (self::$ProductType),
                    'args' => [
                        'limit' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_popularProducts ($root, $args, $ctx);
                    }
                ],
                'productSpecials' => [
                    'type' => Type::listOf (self::$ProductType),
                    'args' => [
                        'sort' => Type::string (),
                        'order' => Type::string (),
                        'start' => Type::int (),
                        'limit' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_productSpecials ($root, $args, $ctx);
                    }
                ],
                'menu' => [
                    'type' => Type::listOf (self::$MenuItemType),
                    'args' => [
                        'name' => Type::string ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_menu ($root, $args, $ctx);
                    }
                ],
                'reviews' => [
                    'type' => Type::listOf (self::$ReviewType),
                    'args' => [
                        'product_id' => Type::nonNull (Type::id ()),
                        'start' => Type::int (),
                        'limit' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_reviews ($root, $args, $ctx);
                    }
                ],
                'categories' => [
                    'type' => Type::listOf (self::$CategoryType),
                    'args' => [
                        'parent' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_categories ($root, $args, $ctx);
                    }
                ],
                'category' => [
                    'type' => self::$CategoryType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_category ($root, $args, $ctx);
                    }
                ],
                'manufacturers' => [
                    'type' => Type::listOf (self::$ManufacturerType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_manufacturers ($root, $args, $ctx);
                    }
                ],
                'manufacturer' => [
                    'type' => self::$ManufacturerType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_manufacturer ($root, $args, $ctx);
                    }
                ],
                'informations' => [
                    'type' => Type::listOf (self::$InformationType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_informations ($root, $args, $ctx);
                    }
                ],
                'information' => [
                    'type' => self::$InformationType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_information ($root, $args, $ctx);
                    }
                ],
                'session' => [
                    'type' => self::$SessionType,
                    'args' => [
                        'id' => Type::id ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_session ($root, $args, $ctx);
                    }
                ],
                'cart' => [
                    'type' => self::$CartType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_cart ($root, $args, $ctx);
                    }
                ],
                'address' => [
                    'type' => self::$AddressType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_address ($root, $args, $ctx);
                    }
                ],
                'addresses' => [
                    'type' => Type::listOf (self::$AddressType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_addresses ($root, $args, $ctx);
                    }
                ],
                'customerGroup' => [
                    'type' => self::$CustomerGroupType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_customerGroup ($root, $args, $ctx);
                    }
                ],
                'customerGroups' => [
                    'type' => Type::listOf (self::$CustomerGroupType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_customerGroups ($root, $args, $ctx);
                    }
                ],
                'download' => [
                    'type' => self::$DownloadType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_download ($root, $args, $ctx);
                    }
                ],
                'downloads' => [
                    'type' => Type::listOf (self::$DownloadType),
                    'args' => [
                        'start' => Type::int (),
                        'limit' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_downloads ($root, $args, $ctx);
                    }
                ],
                'language' => [
                    'type' => self::$LanguageType,
                    'args' => [
                        'id' => Type::id ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_language ($root, $args, $ctx);
                    }
                ],
                'languages' => [
                    'type' => Type::listOf (self::$LanguageType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_languages ($root, $args, $ctx);
                    }
                ],
                'zones' => [
                    'type' => Type::listOf (self::$ZoneType),
                    'args' => [
                        'country_id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_zones ($root, $args, $ctx);
                    }
                ],
                'zone' => [
                    'type' => self::$ZoneType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_zone ($root, $args, $ctx);
                    }
                ],
                'country' => [
                    'type' => self::$CountryType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_country ($root, $args, $ctx);
                    }
                ],
                'countries' => [
                    'type' => Type::listOf (self::$CountryType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_countries ($root, $args, $ctx);
                    }
                ],
                'currency' => [
                    'type' => self::$CurrencyType,
                    'args' => [
                        'code' => Type::string ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_currency ($root, $args, $ctx);
                    }
                ],
                'currencies' => [
                    'type' => Type::listOf (self::$CurrencyType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_currencies ($root, $args, $ctx);
                    }
                ],
                'banners' => [
                    'type' => Type::listOf (self::$BannerType),
                    'args' => [
                        'layout' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_banners ($root, $args, $ctx);
                    }
                ],
                'rewards' => [
                    'type' => Type::listOf (self::$RewardType),
                    'args' => [
                        'sort' => Type::string (),
                        'order' => Type::string (),
                        'start' => Type::int (),
                        'limit' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_rewards ($root, $args, $ctx);
                    }
                ],
                'transactions' => [
                    'type' => Type::listOf (self::$TransactionType),
                    'args' => [
                        'sort' => Type::string (),
                        'order' => Type::string (),
                        'start' => Type::int (),
                        'limit' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_transactions ($root, $args, $ctx);
                    }
                ],
                'wishlist' => [
                    'type' => Type::listOf (self::$ProductType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_wishlist ($root, $args, $ctx);
                    }
                ],
                'order' => [
                    'type' => self::$OrderType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_order ($root, $args, $ctx);
                    }
                ],
                'orders' => [
                    'type' => Type::listOf (self::$OrderType),
                    'args' => [
                        'start' => Type::int (),
                        'limit' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_orders ($root, $args, $ctx);
                    }
                ],
                'orderProduct' => [
                    'type' => self::$ProductType,
                    'args' => [
                        'order_id' => Type::nonNull (Type::id ()),
                        'order_product_id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_orderProduct ($root, $args, $ctx);
                    }
                ],
                'orderProducts' => [
                    'type' => Type::listOf (self::$ProductType),
                    'args' => [
                        'order_id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_orderProducts ($root, $args, $ctx);
                    }
                ],
                'orderOptions' => [
                    'type' => self::$ProductOptionType,
                    'args' => [
                        'order_id' => Type::nonNull (Type::id ()),
                        'order_product_id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_orderOptions ($root, $args, $ctx);
                    }
                ],
                'orderVouchers' => [
                    'type' => self::$VoucherType,
                    'args' => [
                        'order_id' => Type::nonNull (Type::id ()),
                        'order_product_id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_orderVouchers ($root, $args, $ctx);
                    }
                ],
                'getCustomField' => [
                    'type' => self::$CustomFieldType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_getCustomField ($root, $args, $ctx);
                    }
                ],
                'getCustomFields' => [
                    'type' => Type::listOf (self::$CustomFieldType),
                    'args' => [
                        'customer_group_id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_getCustomFields ($root, $args, $ctx);
                    }
                ],
                'paymentAddress' => [
                    'type' => self::$AddressType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_paymentAddress ($root, $args, $ctx);
                    }
                ],
                'shippingAddress' => [
                    'type' => self::$AddressType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_shippingAddress ($root, $args, $ctx);
                    }
                ],
                'paymentMethods' => [
                    'type' => Type::listOf (self::$MethodExtensionType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_paymentMethods ($root, $args, $ctx);
                    }
                ],
                'shippingMethods' => [
                    'type' => Type::listOf (self::$MethodExtensionType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_shippingMethods ($root, $args, $ctx);
                    }
                ],
                'return' => [
                    'type' => self::$ReturnType,
                    'args' => [
                        'return_id' => Type::nonNull (Type::int ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_return ($root, $args, $ctx);
                    }
                ],
                'returns' => [
                    'type' => Type::listOf (self::$ReturnType),
                    'args' => [
                        'start' => Type::int (),
                        'limit' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_returns ($root, $args, $ctx);
                    }
                ],
                'ReturnHistories' => [
                    'type' => Type::listOf (self::$ReturnHistoryType),
                    'args' => [
                        'return_id' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_ReturnHistories ($root, $args, $ctx);
                    }
                ],
                'loggedIn' => [
                    'type' => self::$CustomerType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_loggedIn ($root, $args, $ctx);
                    }
                ],
                'customer' => [
                    'type' => self::$CustomerType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_customer ($root, $args, $ctx);
                    }
                ],
                'customerByEmail' => [
                    'type' => self::$CustomerType,
                    'args' => [
                        'email' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_customerByEmail ($root, $args, $ctx);
                    }
                ],
                'customerByCode' => [
                    'type' => self::$CustomerType,
                    'args' => [
                        'code' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_customerByCode ($root, $args, $ctx);
                    }
                ],
                'customerByToken' => [
                    'type' => self::$CustomerType,
                    'args' => [
                        'token' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_customerByToken ($root, $args, $ctx);
                    }
                ],
                'Ips' => [
                    'type' => Type::listOf (self::$IPType),
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_Ips ($root, $args, $ctx);
                    }
                ],
                'LoginAttempts' => [
                    'type' => self::$CustomerLoginType,
                    'args' => [
                        'email' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_LoginAttempts ($root, $args, $ctx);
                    }
                ],
                'faqCategory' => [
                    'type' => self::$FaqCategoryType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_faqCategory ($root, $args, $ctx);
                    }
                ],
                'faqCategories' => [
                    'type' => Type::listOf (self::$FaqCategoryType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_faqCategories ($root, $args, $ctx);
                    }
                ],
                'faq' => [
                    'type' => self::$FaqType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_faq ($root, $args, $ctx);
                    }
                ],
                'faqs' => [
                    'type' => Type::listOf (self::$FaqType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_faqs ($root, $args, $ctx);
                    }
                ],
                'news' => [
                    'type' => self::$NewsType,
                    'args' => [
                        'id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_news ($root, $args, $ctx);
                    }
                ],
                'allnews' => [
                    'type' => Type::listOf (self::$NewsType),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_allnews ($root, $args, $ctx);
                    }
                ],
                'posts_category' => [
                    'type' => self::$PostsCategory,
                    'args' => [
                        'id' => Type::id ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_posts_category ($root, $args, $ctx);
                    }
                ],
                'post' => [
                    'type' => self::$PostType,
                    'args' => [
                        'id' => Type::id ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_post ($root, $args, $ctx);
                    }
                ],
                'orderStatuses' => [
                    'type' => Type::listOf (self::$OrderStatus),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_orderStatuses ($root, $args, $ctx);
                    }
                ],
                'daLoggedIn' => [
                    'type' => self::$CustomerType,
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_daLoggedIn ($root, $args, $ctx);
                    }
                ],
                'daOrders' => [
                    'type' => Type::listOf (self::$OrderType),
                    'args' => [
                        'order_by' => Type::string (),
                        'status' => Type::listOf (Type::id ()),
                        'limit' => Type::int (),
                        'start' => Type::int ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_daOrders ($root, $args, $ctx);
                    }
                ],
                'daOrder' => [
                    'type' => self::$OrderType,
                    'args' => [
                        'id' => Type::id ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_daOrders ($root, $args, $ctx);
                    }
                ],
                'productVariationPrice' => [
                    'type' => self::$PriceType,
                    'args' => [
                        'product_id' => Type::nonNull (Type::id ()),
                        'options' => Type::nonNull (Type::listOf (self::$OrderProductOptionInput))
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_productVariationPrice ($root, $args, $ctx);
                    }
                ],
                'productVariationData' => [
                    'type' => self::$ProductVariationType,
                    'args' => [
                        'product_id' => Type::nonNull (Type::id ()),
                        'options' => Type::nonNull (Type::listOf (self::$OrderProductOptionInput))
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_productVariationData ($root, $args, $ctx);
                    }
                ],
                'siteInfo' => [
                    'type' => self::$SiteInfoType,
                    'args' => [
                        'key' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->RootQueryType_siteInfo ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        static::$MutationType = new ObjectType ([
            'name' => 'MutationType',
            'fields'  => function () { return [
                'addReview' => [
                    'type' => Type::id (),
                    'args' => [
                        'product_id' => Type::nonNull (Type::id ()),
                        'input' => Type::nonNull (self::$ReviewInput)
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_addReview ($root, $args, $ctx);
                    }
                ],
                'addAddress' => [
                    'type' => Type::id (),
                    'args' => [
                        'input' => self::$AddressInput
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_addAddress ($root, $args, $ctx);
                    }
                ],
                'editAddress' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'address_id' => Type::id (),
                        'input' => self::$AddressInput
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_editAddress ($root, $args, $ctx);
                    }
                ],
                'deleteAddress' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'address_id' => Type::id ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_deleteAddress ($root, $args, $ctx);
                    }
                ],
                'addOrder' => [
                    'type' => Type::id (),
                    'args' => [
                        'input' => Type::nonNull (self::$OrderInput)
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_addOrder ($root, $args, $ctx);
                    }
                ],
                'editOrder' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'order_id' => Type::nonNull (Type::id ()),
                        'input' => Type::nonNull (self::$OrderInput)
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_editOrder ($root, $args, $ctx);
                    }
                ],
                'deleteOrder' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'order_id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_deleteOrder ($root, $args, $ctx);
                    }
                ],
                'addNewCustomSearch' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'input' => self::$NewCustomSearchInput
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_addNewCustomSearch ($root, $args, $ctx);
                    }
                ],
                'addItemToCart' => [
                    'type' => self::$CartType,
                    'args' => [
                        'input' => Type::nonNull (self::$CartItemInput)
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_addItemToCart ($root, $args, $ctx);
                    }
                ],
                'addItemsToCart' => [
                    'type' => self::$CartType,
                    'args' => [
                        'input' => Type::nonNull (Type::listOf (self::$CartItemInput))
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_addItemsToCart ($root, $args, $ctx);
                    }
                ],
                'updateCartItem' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'cart_id' => Type::nonNull (Type::id ()),
                        'quantity' => Type::nonNull (Type::int ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_updateCartItem ($root, $args, $ctx);
                    }
                ],
                'deleteCartItem' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'cart_id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_deleteCartItem ($root, $args, $ctx);
                    }
                ],
                'emptyCart' => [
                    'type' => Type::boolean (),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_emptyCart ($root, $args, $ctx);
                    }
                ],
                'addCoupon' => [
                    'type' => self::$CartType,
                    'args' => [
                        'code' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_addCoupon ($root, $args, $ctx);
                    }
                ],
                'setPaymentAddress' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'input' => Type::nonNull (self::$AddressInput)
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_setPaymentAddress ($root, $args, $ctx);
                    }
                ],
                'setPaymentAddressById' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'address_id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_setPaymentAddressById ($root, $args, $ctx);
                    }
                ],
                'setPaymentMethod' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'code' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_setPaymentMethod ($root, $args, $ctx);
                    }
                ],
                'setShippingAddress' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'input' => self::$AddressInput
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_setShippingAddress ($root, $args, $ctx);
                    }
                ],
                'setShippingAddressById' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'address_id' => Type::id ()
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_setShippingAddressById ($root, $args, $ctx);
                    }
                ],
                'setShippingMethod' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'code' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_setShippingMethod ($root, $args, $ctx);
                    }
                ],
                'addAccountActivity' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'key' => Type::nonNull (Type::string ()),
                        'input' => self::$AccountActivityInput
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_addAccountActivity ($root, $args, $ctx);
                    }
                ],
                'addAffiliateActivity' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'key' => Type::nonNull (Type::string ()),
                        'input' => self::$AffiliateActivityInput
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_addAffiliateActivity ($root, $args, $ctx);
                    }
                ],
                'addWishlist' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'product_id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_addWishlist ($root, $args, $ctx);
                    }
                ],
                'deleteWishlist' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'product_id' => Type::nonNull (Type::id ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_deleteWishlist ($root, $args, $ctx);
                    }
                ],
                'addReturn' => [
                    'type' => self::$ReturnType,
                    'args' => [
                        'input' => self::$ReturnInput
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_addReturn ($root, $args, $ctx);
                    }
                ],
                'deleteLoginAttempts' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'email' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_deleteLoginAttempts ($root, $args, $ctx);
                    }
                ],
                'editCustomer' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'input' => Type::nonNull (self::$CustomerEdit)
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_editCustomer ($root, $args, $ctx);
                    }
                ],
                'editPassword' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'password' => Type::nonNull (Type::string ()),
                        'confirm' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_editPassword ($root, $args, $ctx);
                    }
                ],
                'register' => [
                    'type' => Type::id (),
                    'args' => [
                        'input' => self::$CustomerInput
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_register ($root, $args, $ctx);
                    }
                ],
                'login' => [
                    'type' => Type::id (),
                    'args' => [
                        'email' => Type::nonNull (Type::string ()),
                        'password' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_login ($root, $args, $ctx);
                    }
                ],
                'logout' => [
                    'type' => Type::boolean (),
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_logout ($root, $args, $ctx);
                    }
                ],
                'forgotten' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'email' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_forgotten ($root, $args, $ctx);
                    }
                ],
                'contactus' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'name' => Type::nonNull (Type::string ()),
                        'email' => Type::nonNull (Type::string ()),
                        'enquiry' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_contactus ($root, $args, $ctx);
                    }
                ],
                'setLanguage' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'code' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_setLanguage ($root, $args, $ctx);
                    }
                ],
                'setCurrency' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'code' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_setCurrency ($root, $args, $ctx);
                    }
                ],
                'editCode' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'email' => Type::nonNull (Type::string ()),
                        'code' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_editCode ($root, $args, $ctx);
                    }
                ],
                'editNewsletter' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'newsletter' => Type::nonNull (Type::boolean ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_editNewsletter ($root, $args, $ctx);
                    }
                ],
                'daLogin' => [
                    'type' => Type::id (),
                    'args' => [
                        'email' => Type::nonNull (Type::string ()),
                        'password' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_daLogin ($root, $args, $ctx);
                    }
                ],
                'changeOrderStatus' => [
                    'type' => self::$OrderType,
                    'args' => [
                        'orderId' => Type::nonNull (Type::id ()),
                        'statusCode' => Type::nonNull (Type::int ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_changeOrderStatus ($root, $args, $ctx);
                    }
                ],
                'sendVerificationCode' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'countryCode' => Type::nonNull (Type::string ()),
                        'mobileNumber' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_sendVerificationCode ($root, $args, $ctx);
                    }
                ],
                'verifyMobileCode' => [
                    'type' => Type::boolean (),
                    'args' => [
                        'countryCode' => Type::nonNull (Type::string ()),
                        'mobileNumber' => Type::nonNull (Type::string ()),
                        'verificationCode' => Type::nonNull (Type::string ())
                    ],
                    'resolve' => function ($root, $args, $ctx) {
                        return self::$resolvers->MutationType_verifyMobileCode ($root, $args, $ctx);
                    }
                ]
            ]; }
        ]);

        self::$schema = new Schema ([
            'query' => self::$RootQueryType,
            'mutation' => self::$MutationType
        ]);

    }

    public static function ResolversInstance () {
        self::Instance ();
        return self::$resolvers;
    }

    public static function Instance () {

        if (!isset (static::$types)) static::$types = new Types ();
        return static::$types;
    }
}
?>
